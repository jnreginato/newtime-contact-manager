<?php

declare(strict_types=1);

namespace App\Adapter\Api\Middleware;

use App\Infrastructure\Api\Request\Parser\InputParserInterface;
use App\Infrastructure\Api\Request\Validation\InputValidatorInterface;
use App\Infrastructure\Api\Response\ApiThrowableResponseFactoryInterface as ResponseFactory;
use App\Infrastructure\Api\Response\ApiThrowableResponseInterface;
use App\UnitTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Throwable;

/**
 * Feature: ValidatedInputMiddlewareTest.
 *
 * Tests the middleware behavior: parsing request, constructing DTO, validating
 * and delegating to handler, and converting thrown exceptions into API responses
 * via the response factory.
 */
final class ValidatedInputMiddlewareTest extends UnitTestCase
{
    /**
     * Scenario: Parser exception results in response from ResponseFactory.
     *
     * Given the parser throws an exception,
     * When middleware processes the request,
     * Then the response factory should be used to create and return an error
     * response and handler should not be invoked.
     *
     * @throws Throwable If the test fails due to an unexpected exception.
     */
    public function testProcessReturnsErrorResponseWhenParserThrows(): void
    {
        $dtoClass = TestInput::class;
        $exception = new RuntimeException('parse error');

        $inputParser = $this->createMock(InputParserInterface::class);
        $inputParser->expects(self::once())
            ->method('parse')
            ->willThrowException($exception);

        $inputValidator = $this->createMock(InputValidatorInterface::class);
        $inputValidator->expects(self::never())->method('validate');

        $errorResponse = $this->createMock(ApiThrowableResponseInterface::class);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->with('id')->willReturn(null);
        $request->method('getParsedBody')->willReturn(null);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::never())->method('handle');

        $responseFactory = $this->createMock(ResponseFactory::class);
        $responseFactory->expects(self::once())
            ->method('createResponse')
            ->with($exception)
            ->willReturn($errorResponse);

        $middleware = new ValidatedInputMiddleware($dtoClass, $inputParser, $inputValidator, $responseFactory);

        $result = $middleware->process($request, $handler);

        self::assertSame($errorResponse, $result);
    }

    /**
     * Scenario: Validator exception results in response from ResponseFactory.
     *
     * Given the validator throws an exception after parsing,
     * When middleware processes the request,
     * Then the response factory should be used to create and return an error
     * response and handler should not be invoked.
     *
     * @throws Throwable If the test fails due to an unexpected exception.
     */
    public function testProcessReturnsErrorResponseWhenValidatorThrows(): void
    {
        $dtoClass = TestInput::class;
        $data = ['x' => 'y'];
        $exception = new RuntimeException('validation error');

        $inputParser = $this->createMock(InputParserInterface::class);
        $inputParser->expects(self::once())
            ->method('parse');
        $inputParser->method('getData')->willReturn($data);

        $inputValidator = $this->createMock(InputValidatorInterface::class);
        $inputValidator->expects(self::once())
            ->method('validate')
            ->willThrowException($exception);

        $errorResponse = $this->createMock(ApiThrowableResponseInterface::class);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->with('id')->willReturn('id-value');
        $request->method('getParsedBody')->willReturn($data);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects(self::never())->method('handle');

        $responseFactory = $this->createMock(ResponseFactory::class);
        $responseFactory->expects(self::once())
            ->method('createResponse')
            ->with($exception)
            ->willReturn($errorResponse);

        $middleware = new ValidatedInputMiddleware($dtoClass, $inputParser, $inputValidator, $responseFactory);

        $result = $middleware->process($request, $handler);

        self::assertSame($errorResponse, $result);
    }
}
