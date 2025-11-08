<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

use Exception;
use App\UnitTestCase;

/**
 * Feature: ApiThrowableResponseTest
 *
 * This test class verifies the behavior of the ApiThrowableResponse class,
 * ensuring it correctly wraps a Throwable and exposes the proper HTTP response content.
 */
final class ApiThrowableResponseTest extends UnitTestCase
{
    /**
     * Scenario: Create an ApiThrowableResponse and validate its properties
     *
     * Given a Throwable, content string, and status code,
     * When the ApiThrowableResponse is instantiated,
     * Then it should store the Throwable, return the correct content and headers, and expose the correct status code.
     */
    public function testCreatesResponseWithThrowable(): void
    {
        // Arrange
        $throwable = new Exception('Something went wrong');
        $content = '{"error":"Something went wrong"}';
        $statusCode = 500;

        // Act
        $response = new ApiThrowableResponse($throwable, $content, $statusCode);

        // Assert body content
        self::assertSame($content, (string) $response->getBody());

        // Assert status code
        self::assertSame($statusCode, $response->getStatusCode());

        // Assert content type header
        self::assertSame('application/json', $response->getHeaderLine('Content-Type'));

        // Assert throwable reference
        self::assertSame($throwable, $response->getThrowable());
    }
}
