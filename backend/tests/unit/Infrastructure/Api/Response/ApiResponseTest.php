<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

use App\UnitTestCase;

/**
 * Feature: ApiResponse
 *
 * This class provides unit tests for the ApiResponse class, which represents an API response.
 * It tests the functionality of creating responses with default values, content, and additional headers.
 */
final class ApiResponseTest extends UnitTestCase
{
    /**
     * Scenario: Creates response with default values
     *
     * Given no content and no status code,
     * When a new ApiResponse is created,
     * Then it should have a status code of 200, content type of application/json, and an empty body.
     */
    public function testCreatesResponseWithDefaultValues(): void
    {
        $response = new ApiResponse();

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(ApiResponse::JSON_MEDIA_TYPE, $response->getHeaderLine(ApiResponse::HEADER_CONTENT_TYPE));
        self::assertSame('', (string) $response->getBody());
    }

    /**
     * Scenario: Creates response with content
     *
     * Given a JSON content and a status code,
     * When a new ApiResponse is created,
     * Then it should have the specified status code, content type of application/json, and the provided content.
     */
    public function testCreatesResponseWithContent(): void
    {
        $response = new ApiResponse('{"message":"ok"}', 201);

        self::assertSame(201, $response->getStatusCode());
        self::assertSame(ApiResponse::JSON_MEDIA_TYPE, $response->getHeaderLine(ApiResponse::HEADER_CONTENT_TYPE));
        self::assertSame('{"message":"ok"}', (string) $response->getBody());
    }

    /**
     * Scenario: Creates response with additional headers
     *
     * Given a JSON content, a status code, and additional headers,
     * When a new ApiResponse is created,
     * Then it should have the specified status code, content type of application/json, and the provided content,
     * while allowing additional headers to be set.
     */
    public function testCreatesResponseWithAdditionalHeaders(): void
    {
        $response = new ApiResponse('{"data":{}}', 202, [
            'X-Custom-Header' => 'abc123',
            'Content-Type' => 'application/custom+json', // must be overridden by the JSON_MEDIA_TYPE
        ]);

        // Content-Type must be set to JSON_MEDIA_TYPE
        self::assertSame(ApiResponse::JSON_MEDIA_TYPE, $response->getHeaderLine(ApiResponse::HEADER_CONTENT_TYPE));
        self::assertSame('abc123', $response->getHeaderLine('X-Custom-Header'));
        self::assertSame(202, $response->getStatusCode());
        self::assertSame('{"data":{}}', (string) $response->getBody());
    }
}
