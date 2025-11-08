<?php

declare(strict_types=1);

namespace App\Adapter\Api\Middleware;

use App\Infrastructure\Api\Request\Parser\InputParserInterface;
use App\Infrastructure\Api\Request\Validation\InputValidatorInterface;
use App\Infrastructure\Api\Response\ApiThrowableResponseFactoryInterface;
use App\UnitTestCase;
use AssertionError;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use stdClass;

/**
 * Feature: ValidatedInputMiddlewareFactoryTest.
 *
 * Tests the factory that creates ValidatedInputMiddleware instances from a container.
 */
final class ValidatedInputMiddlewareFactoryTest extends UnitTestCase
{
    /**
     * Scenario: Factory creates middleware with dependencies fetched from container.
     *
     * Given a container that returns proper parser, validator and response factory,
     * When invoking the factory,
     * Then a ValidatedInputMiddleware instance must be returned and container->get must be called for each dependency.
     */
    public function testInvokeCreatesMiddleware(): void
    {
        $inputParser = $this->createMock(InputParserInterface::class);
        $inputValidator = $this->createMock(InputValidatorInterface::class);
        $responseFactory = $this->createMock(ApiThrowableResponseFactoryInterface::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::exactly(3))
            ->method('get')
            ->willReturnMap([
                [InputParserInterface::class, $inputParser],
                [InputValidatorInterface::class, $inputValidator],
                [ApiThrowableResponseFactoryInterface::class, $responseFactory],
            ]);

        $factory = new ValidatedInputMiddlewareFactory(TestInput::class);

        $middleware = ($factory)($container);

        self::assertInstanceOf(ValidatedInputMiddleware::class, $middleware);
    }

    /**
     * Scenario: NotFoundException from container is propagated.
     *
     * Given a container that throws NotFoundExceptionInterface when retrieving a dependency,
     * When invoking the factory,
     * Then the NotFoundExceptionInterface must be propagated.
     *
     * @throws ContainerExceptionInterface If the test fails due to an unexpected exception.
     * @throws NotFoundExceptionInterface If the test fails due to an unexpected exception.
     */
    public function testInvokePropagatesNotFoundException(): void
    {
        $this->expectException(NotFoundExceptionInterface::class);

        $exception = $this->createMock(NotFoundExceptionInterface::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->willThrowException($exception);

        $factory = new ValidatedInputMiddlewareFactory(TestInput::class);

        ($factory)($container);
    }

    /**
     * Scenario: Container exception is propagated.
     *
     * Given a container that throws ContainerExceptionInterface when retrieving a dependency,
     * When invoking the factory,
     * Then the ContainerExceptionInterface must be propagated.
     *
     * @throws NotFoundExceptionInterface If the test fails due to an unexpected exception.
     * @throws ContainerExceptionInterface If the test fails due to an unexpected exception.
     */
    public function testInvokePropagatesContainerException(): void
    {
        $this->expectException(ContainerExceptionInterface::class);

        $exception = $this->createMock(ContainerExceptionInterface::class);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->willThrowException($exception);

        $factory = new ValidatedInputMiddlewareFactory(TestInput::class);

        ($factory)($container);
    }

    /**
     * Scenario: Wrong type returned by container triggers assertion.
     *
     * Given a container that returns an object not implementing the expected interface,
     * When invoking the factory,
     * Then an AssertionError should be raised due to failed runtime assertion.
     */
    public function testInvokeWithWrongTypeTriggersAssertionError(): void
    {
        $this->expectException(AssertionError::class);

        $container = $this->createMock(ContainerInterface::class);
        // Return a stdClass (wrong type) on first get() call so the assert fails
        $container->method('get')->willReturn(new stdClass());

        $factory = new ValidatedInputMiddlewareFactory(TestInput::class);

        ($factory)($container);
    }
}
