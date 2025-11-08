<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use InvalidArgumentException;
use App\Domain\Exception\DomainDuplicatedResourceException;
use App\Domain\Exception\DomainResourceNotFoundException;
use App\Domain\Exception\DomainValidationException;
use App\UnitTestCase;
use Override;
use PHPUnit\Framework\MockObject\Exception;
use RuntimeException;
use Throwable;

use function assert;

/**
 * Feature: ThrowableConverter unit tests.
 *
 * This test suite verifies the functionality of the ThrowableConverter class,
 * ensuring it correctly converts various types of Throwable instances into
 * ApiExceptionInterface instances.
 *
 * @psalm-suppress PropertyNotSetInConstructor
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
 */
final class ThrowableConverterTest extends UnitTestCase
{
    /**
     * The ThrowableConverter instance used for testing.
     */
    private ThrowableConverter $converter;

    /**
     * Sets up the test environment.
     *
     * This method initializes the ThrowableConverter instance before each test.
     */
    #[Override]
    protected function setUp(): void
    {
        $aggregator = new FakeErrorAggregator();
        $this->converter = new ThrowableConverter($aggregator);

        parent::setUp();
    }

    /**
     * Scenario: Convert AuthenticationException to ApiConvertedException
     *
     * Given: An AuthenticationExceptionInterface instance,
     * When: The convert method is called,
     * Then: It should return an ApiConvertedException with a 401 status code
     *
     * @throws Exception If the mock object cannot be created.
     * @throws Throwable If the conversion fails.
     */
    public function testConvertAuthenticationException(): void
    {
        $exception = $this->createMock(AuthenticationExceptionInterface::class);
        assert($exception instanceof AuthenticationExceptionInterface);

        $result = $this->converter->convert($exception);

        self::assertInstanceOf(ApiConvertedException::class, $result);
        self::assertSame(401, $result->getCode());

        $errors = iterator_to_array($result->getErrors()->getIterator());
        $error = $errors[0];

        self::assertSame('Authentication failed', $error->getDetail());
        self::assertSame('401', $error->getStatus());
    }

    /**
     * Scenario: Convert AuthorizationException to ApiConvertedException
     *
     * Given: An AuthorizationExceptionInterface instance,
     * When: The convert method is called,
     * Then: It should return an ApiConvertedException with a 403 status code
     *
     * @throws Exception If the mock object cannot be created.
     * @throws Throwable If the conversion fails.
     */
    public function testConvertAuthorizationException(): void
    {
        $exception = $this->createMock(AuthorizationExceptionInterface::class);
        assert($exception instanceof AuthorizationExceptionInterface);

        $exception
            ->expects($this->once())
            ->method('getAction')
            ->willReturn('edit_profile');

        $result = $this->converter->convert($exception);

        self::assertInstanceOf(ApiConvertedException::class, $result);
        self::assertSame(403, $result->getCode());

        $errors = iterator_to_array($result->getErrors()->getIterator());
        $error = $errors[0];

        self::assertSame('403', $error->getStatus());
        self::assertStringContainsString('edit_profile', (string) $error->getDetail());
    }

    /**
     * Scenario: Convert DomainResourceNotFoundException to ApiConvertedException
     *
     * Given: An DomainResourceNotFoundException instance,
     * When: The convert method is called,
     * Then: It should return an ApiConvertedException with a 404 status code
     *
     * @throws Throwable If the conversion fails.
     */
    public function testConvertEntityNotFound(): void
    {
        $exception = new DomainResourceNotFoundException('x');

        $result = $this->converter->convert($exception);
        self::assertInstanceOf(ApiConvertedException::class, $result);
        self::assertSame(404, $result->getCode());

        $error = iterator_to_array($result->getErrors()->getIterator())[0];
        self::assertSame('404', $error->getStatus());
        self::assertSame('Resource not found', (string) $error->getDetail());
    }

    /**
     * Scenario: Convert DomainDuplicatedResourceException to ApiConvertedException
     *
     * Given: A DomainDuplicatedResourceException instance,
     * When: The convert method is called,
     * Then: It should return an ApiConvertedException with a 409 status code
     *
     * @throws Throwable If the conversion fails.
     */
    public function testConvertDuplicatedEntity(): void
    {
        $exception = new DomainDuplicatedResourceException('y');

        $result = $this->converter->convert($exception);
        self::assertInstanceOf(ApiConvertedException::class, $result);
        self::assertSame(409, $result->getCode());

        $error = iterator_to_array($result->getErrors()->getIterator())[0];
        self::assertSame('409', $error->getStatus());
        self::assertSame('Resource already exists', (string) $error->getDetail());
    }

    /**
     * Scenario: Convert InvalidArgumentException and DomainValidationException to ApiConvertedException
     *
     * Given: An InvalidArgumentException or DomainValidationException instance,
     * When: The convert method is called,
     * Then: It should return an ApiConvertedException with a 422 status code
     *
     * @throws Throwable If the conversion fails.
     */
    public function testConvertInvalidArgumentAndDomainValidation(): void
    {
        $result1 = $this->converter->convert(new InvalidArgumentException('bad'));
        self::assertInstanceOf(ApiConvertedException::class, $result1);
        self::assertSame(422, $result1->getCode());
        $error1 = iterator_to_array($result1->getErrors()->getIterator())[0];
        self::assertSame('422', $error1->getStatus());
        self::assertSame('Unprocessable Entity', (string) $error1->getDetail());

        $result2 = $this->converter->convert(new DomainValidationException('bad2'));
        self::assertInstanceOf(ApiConvertedException::class, $result2);
        self::assertSame(422, $result2->getCode());
        $error2 = iterator_to_array($result2->getErrors()->getIterator())[0];
        self::assertSame('422', $error2->getStatus());
        self::assertSame('Unprocessable Entity', (string) $error2->getDetail());
    }

    /**
     * Scenario: Convert generic Throwable to ApiConvertedException
     *
     * Given: A generic Throwable instance,
     * When: The convert method is called,
     * Then: It should return an ApiConvertedException with the appropriate status code and message.
     *
     * @throws Throwable If the conversion fails.
     */
    public function testConvertGenericThrowableWithValidCode(): void
    {
        $exception = new RuntimeException('Something went wrong', 500);

        $result = $this->converter->convert($exception);

        self::assertInstanceOf(ApiConvertedException::class, $result);
        self::assertSame(500, $result->getCode());

        $errors = iterator_to_array($result->getErrors()->getIterator());
        $error = $errors[0];

        self::assertStringContainsString('Something went wrong', (string) $error->getDetail());
        self::assertSame('500', $error->getStatus());
    }

    /**
     * Scenario: Convert generic Throwable with invalid code to ApiConvertedException
     *
     * Given: A generic Throwable instance with an invalid code,
     * When: The convert method is called,
     * Then: It should normalize the code to 400 and return an ApiConvertedException.
     *
     * @throws Throwable If the conversion fails.
     */
    public function testConvertGenericThrowableWithInvalidCode(): void
    {
        $exception = new RuntimeException('Invalid code', 9999);

        $result = $this->converter->convert($exception);

        self::assertInstanceOf(ApiConvertedException::class, $result);

        $errors = iterator_to_array($result->getErrors()->getIterator());
        $error = $errors[0];

        // Invalid code should be normalized to 400
        self::assertSame('500', $error->getStatus());
        self::assertStringContainsString('Invalid code', (string) $error->getDetail());
    }

    /**
     * Scenario: Convert generic Throwable with negative code to ApiConvertedException
     *
     * Given: A generic Throwable instance with a negative code,
     * When: The convert method is called,
     * Then: It should normalize the code to 400 and return an ApiConvertedException.
     *
     * @throws Throwable If the conversion fails.
     */
    public function testConvertGenericThrowableWithNegativeCode(): void
    {
        $exception = new RuntimeException('Negative code', -1);

        $result = $this->converter->convert($exception);

        self::assertInstanceOf(ApiConvertedException::class, $result);

        $errors = iterator_to_array($result->getErrors()->getIterator());
        $error = $errors[0];

        self::assertSame('500', $error->getStatus());
        self::assertStringContainsString('Negative code', (string) $error->getDetail());
    }
}
