<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

use App\Infrastructure\Api\Response\MediaType\MediaTypeInterface;
use App\UnitTestCase;
use Override;
use PHPUnit\Framework\MockObject\Exception;

use function assert;

/**
 * Feature: ApiResponseFactoryTest
 *
 * This class tests the ApiResponseFactory for creating various types of API responses.
 * It verifies that the responses are created with the correct status codes, headers,
 * and body content.
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ApiResponseFactoryTest extends UnitTestCase
{
    private const string MEDIA_TYPE = 'application/json';

    /**
     * The ApiResponseFactory instance used for testing.
     */
    private ApiResponseFactory $factory;

    /**
     * Sets up the test environment by creating a mock MediaTypeInterface
     * and initializing the ApiResponseFactory with it.
     *
     * @throws Exception If the mock cannot be created.
     */
    #[Override]
    protected function setUp(): void
    {
        $mediaType = $this->createMock(MediaTypeInterface::class);
        assert($mediaType instanceof MediaTypeInterface);

        $mediaType
            ->method('getMediaType')
            ->willReturn(self::MEDIA_TYPE);

        $this->factory = new ApiResponseFactory($mediaType);

        parent::setUp();
    }

    /**
     * Scenario: Create an OK response
     *
     * Given a valid JSON string,
     * When the createOkResponse method is called,
     * Then an HTTP 200 OK response should be returned
     * With the correct content type and body.
     */
    public function testCreateOkResponse(): void
    {
        $response = $this->factory->createOkResponse('{"ok":true}');

        self::assertSame(200, $response->getStatusCode());
        self::assertSame(self::MEDIA_TYPE, $response->getHeaderLine('Content-Type'));
        self::assertSame('{"ok":true}', (string) $response->getBody());
    }

    /**
     * Scenario: Create a Created response
     *
     * Given a valid JSON string and a URL,
     * When the createCreatedResponse method is called,
     * Then an HTTP 201 Created response should be returned
     * With the correct content type, body, and Location header.
     */
    public function testCreateCreatedResponse(): void
    {
        $url = '/resource/123';
        $response = $this->factory->createCreatedResponse('{"created":true}', $url);

        self::assertSame(201, $response->getStatusCode());
        self::assertSame(self::MEDIA_TYPE, $response->getHeaderLine('Content-Type'));
        self::assertSame($url, $response->getHeaderLine('Location'));
        self::assertSame('{"created":true}', (string) $response->getBody());
    }

    /**
     * Scenario: Create an Accepted response
     *
     * Given a valid JSON string,
     * When the createAcceptedResponse method is called,
     * Then an HTTP 202 Accepted response should be returned
     * With the correct content type and body.
     */
    public function testCreateAcceptedResponse(): void
    {
        $response = $this->factory->createAcceptedResponse('{"accepted":true}');

        self::assertSame(202, $response->getStatusCode());
        self::assertSame(self::MEDIA_TYPE, $response->getHeaderLine('Content-Type'));
        self::assertSame('{"accepted":true}', (string) $response->getBody());
    }

    /**
     * Scenario: Create a No Content response
     *
     * When the createNoContentResponse method is called,
     * Then an HTTP 204 No Content response should be returned
     * With the correct content type and an empty body.
     */
    public function testCreateNoContentResponse(): void
    {
        $response = $this->factory->createNoContentResponse();

        self::assertSame(204, $response->getStatusCode());
        self::assertSame(self::MEDIA_TYPE, $response->getHeaderLine('Content-Type'));
        self::assertSame('', (string) $response->getBody());
    }

    /**
     * Scenario: Create an API response with custom status code and headers
     *
     * Given a valid JSON string, a custom status code, and additional headers,
     * When the createApiResponse method is called,
     * Then the response should have the correct status code, headers, and body.
     */
    public function testCreateApiResponseWithAddContentTypeTrue(): void
    {
        $response = $this->factory->createApiResponse('{"custom":true}', 299, ['X-Test' => 'abc'], true);

        self::assertSame(299, $response->getStatusCode());
        self::assertSame(self::MEDIA_TYPE, $response->getHeaderLine('Content-Type'));
        self::assertSame('abc', $response->getHeaderLine('X-Test'));
        self::assertSame('{"custom":true}', (string) $response->getBody());
    }

    /**
     * Scenario: Create an API response without adding Content-Type header
     *
     * Given a valid JSON string, a custom status code, and additional headers,
     * When the createApiResponse method is called with addContentType set to false,
     * Then the response should not have the Content-Type header added.
     */
    public function testCreateApiResponseWithAddContentTypeFalse(): void
    {
        $response = $this->factory->createApiResponse('{"noContentType":true}', 206, ['X-Test' => 'def'], false);

        self::assertSame(206, $response->getStatusCode());
        self::assertSame(self::MEDIA_TYPE, $response->getHeaderLine('Content-Type'));
        self::assertSame('def', $response->getHeaderLine('X-Test'));
        self::assertSame('{"noContentType":true}', (string) $response->getBody());
    }
}
