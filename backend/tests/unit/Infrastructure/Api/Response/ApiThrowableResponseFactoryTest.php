<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

use ArrayIterator;
use App\Infrastructure\Api\Exception\ApiErrorInterface;
use App\Infrastructure\Api\Exception\ApiExceptionInterface;
use App\Infrastructure\Api\Exception\ThrowableConverterInterface;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorCollectionInterface;
use App\UnitTestCase;
use JsonException;
use Override;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;
use Throwable;

use function assert;

/**
 * Feature: ApiThrowableResponseFactoryTest
 *
 * This test class verifies the behavior of the ApiThrowableResponseFactory,
 * ensuring it correctly creates ApiThrowableResponse instances from ApiExceptionInterface
 * and non-ApiExceptionInterface Throwable instances.
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ApiThrowableResponseFactoryTest extends UnitTestCase
{
    /**
     * The ThrowableConverterInterface mock used to convert non-ApiExceptionInterface
     */
    private MockObject $converter;

    /**
     * The ApiThrowableResponseFactory instance being tested.
     */
    private ApiThrowableResponseFactory $factory;

    /**
     * Sets up the test environment.
     *
     * This method initializes the factory with a mock ThrowableConverterInterface
     * to be used in the tests.
     *
     * @throws Exception If the setup fails.
     */
    #[Override]
    protected function setUp(): void
    {
        $this->converter = $this->createMock(ThrowableConverterInterface::class);
        assert($this->converter instanceof ThrowableConverterInterface);

        $this->factory = new ApiThrowableResponseFactory($this->converter);

        parent::setUp();
    }

    /**
     * Scenario: Create a response from an ApiExceptionInterface
     *
     * Given an ApiExceptionInterface with specific error details,
     * When the factory creates a response,
     * Then the response should be an instance of ApiThrowableResponse,
     * and contain the correct status code and error details in the body.
     *
     * @throws Exception If the mock object creation fails.
     * @throws JsonException If JSON encoding fails.
     */
    public function testCreateResponseFromApiException(): void
    {
        $error = $this->createMock(ApiErrorInterface::class);
        assert($error instanceof ApiErrorInterface);
        $error->method('getStatus')->willReturn('400');
        $error->method('getCode')->willReturn('E001');
        $error->method('getTitle')->willReturn('Invalid');
        $error->method('getDetail')->willReturn('Missing field');
        $error->method('getSource')->willReturn(['pointer' => '/data/attributes/email']);

        $errorCollection = $this->createMock(ErrorCollectionInterface::class);
        assert($errorCollection instanceof ErrorCollectionInterface);
        $errorCollection->method('get')->willReturn([$error]);
        $errorCollection->method('getIterator')->willReturn(new ArrayIterator([$error]));

        $exception = $this->createMock(ApiExceptionInterface::class);
        assert($exception instanceof ApiExceptionInterface);
        $exception->method('getErrors')->willReturn($errorCollection);
        $exception->method('getHttpCode')->willReturn(400);

        $response = $this->factory->createResponse($exception);

        self::assertSame(400, $response->getStatusCode());
        self::assertStringContainsString('"code":"E001"', (string) $response->getBody());
    }

    /**
     * Scenario: Create a response from a non-ApiExceptionInterface Throwable
     *
     * Given a Throwable that is not an ApiExceptionInterface,
     * When the factory creates a response,
     * Then the response should be an instance of ApiThrowableResponse,
     * and contain the converted error details in the body.
     *
     * @throws Exception If the mock object creation fails.
     * @throws JsonException If JSON encoding fails.
     */
    public function testCreateResponseFromNonApiException(): void
    {
        $error = $this->createMock(ApiErrorInterface::class);
        assert($error instanceof ApiErrorInterface);
        $error->method('getStatus')->willReturn('500');
        $error->method('getCode')->willReturn('E999');
        $error->method('getTitle')->willReturn('Unexpected');
        $error->method('getDetail')->willReturn('Something went wrong');
        $error->method('getSource')->willReturn([]);

        $converted = $this->createMock(ApiExceptionInterface::class);
        assert($converted instanceof ApiExceptionInterface);

        $errorCollection = $this->createMock(ErrorCollectionInterface::class);
        assert($errorCollection instanceof ErrorCollectionInterface);
        $errorCollection->method('get')->willReturn([$error]);
        $errorCollection->method('getIterator')->willReturn(new ArrayIterator([$error]));

        $converted->method('getErrors')->willReturn($errorCollection);
        $converted->method('getHttpCode')->willReturn(500);

        $original = $this->createMock(Throwable::class);
        assert($original instanceof Throwable);
        $this->converter->method('convert')->with($original)->willReturn($converted);

        $response = $this->factory->createResponse($original);

        self::assertSame(500, $response->getStatusCode());
        self::assertStringContainsString('"title":"Unexpected"', (string) $response->getBody());
    }

    /**
     * Scenario: Encode errors with empty representation
     *
     * Given an ApiErrorInterface with no details,
     * When the factory encodes the errors,
     * Then the JSON representation should contain an empty object for that error.
     *
     * @throws Exception If the mock object creation fails.
     * @throws JsonException If JSON encoding fails.
     */
    public function testEncodeErrorsWithEmptyRepresentation(): void
    {
        $error = $this->createConfiguredMock(ApiErrorInterface::class, [
            'getStatus' => null,
            'getCode' => null,
            'getTitle' => null,
            'getDetail' => null,
            'getSource' => null,
        ]);
        assert($error instanceof ApiErrorInterface);

        $json = $this->factory->encodeErrors([$error]);

        self::assertSame('{"errors":[{}]}', $json);
    }

    /**
     * Scenario: Encode errors to JSON
     *
     * Given an iterable of ApiErrorInterface,
     * When the factory encodes the errors,
     * Then it should return a JSON string representation of the errors.
     *
     * @throws Exception If the mock object creation fails.
     * @throws ReflectionException If reflection fails.
     */
    public function testEncodeToJsonThrowsAndLogsJsonException(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        assert($logger instanceof LoggerInterface);
        $this->factory->setLogger($logger);

        // Document containing invalid UTF-8 byte sequence
        $document = ['errors' => ["\xB1\x31"]];

        $logger->expects($this->once())->method('error')
            ->with('Failed to encode errors to JSON', self::arrayHasKey('error'));

        $this->expectException(JsonException::class);
        $ref = new ReflectionClass($this->factory);
        $method = $ref->getMethod('encodeToJson');
        $method->invoke($this->factory, $document);
    }
}
