<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

use App\Application\Result\PaginatedResultInterface;
use App\Infrastructure\Api\Exception\ApiErrorInterface;
use App\Infrastructure\Api\Response\Encoder\EncoderInterface;
use App\UnitTestCase;
use Override;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

use function assert;

/**
 * Feature: ApiResponseBuilder
 *
 * This class provides unit tests for the ApiResponseBuilder class, which is
 * responsible for building API responses.
 * It tests the functionality of responding with paginated resources, single
 * resources, created resources, updated resources, deleted resources, and errors.
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ApiResponseBuilderTest extends UnitTestCase
{
    /**
     * The encoder used to encode data into a response format.
     */
    private MockObject $encoder;

    /**
     * The factory used to create API responses.
     */
    private MockObject $responseFactory;

    /**
     * The ApiResponseBuilder instance being tested.
     */
    private ApiResponseBuilder $builder;

    /**
     * Sets up the test environment by creating mock objects for the encoder
     * and response factory, and initializing the ApiResponseBuilder.
     *
     * @throws Exception If the mock object creation fails.
     */
    #[Override]
    protected function setUp(): void
    {
        $this->encoder = $this->createMock(EncoderInterface::class);
        assert($this->encoder instanceof EncoderInterface);

        $this->responseFactory = $this->createMock(ApiResponseFactoryInterface::class);
        assert($this->responseFactory instanceof ApiResponseFactoryInterface);

        $this->builder = new ApiResponseBuilder($this->encoder, $this->responseFactory);

        parent::setUp();
    }

    /**
     * Scenario: Responds with paginated resources
     *
     * Given paginated data and a request URI,
     * When the respondWithPaginatedResources method is called,
     * Then it should return a response with the encoded data.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testRespondWithPaginatedResources(): void
    {
        $data = $this->createMock(PaginatedResultInterface::class);
        assert($data instanceof PaginatedResultInterface);

        $uri = $this->createMock(UriInterface::class);
        assert($uri instanceof UriInterface);

        $encoded = '{"data":[]}';
        $response = $this->createMock(ResponseInterface::class);
        assert($response instanceof ResponseInterface);

        $this->encoder->expects($this->once())->method('withOriginalUri')->with($uri)->willReturnSelf();
        $this->encoder->expects($this->once())->method('encodeData')->with($data)->willReturn($encoded);
        $this->responseFactory->expects($this->once())
            ->method('createOkResponse')
            ->with($encoded, [])
            ->willReturn($response);

        $actual = $this->builder->respondWithPaginatedResources($data, $uri);

        self::assertSame($response, $actual);
    }

    /**
     * Scenario: Responds with a single resource
     *
     * Given an entity and a request URI,
     * When the respondWithSingleResource method is called,
     * Then it should return a response with the encoded entity.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testRespondWithSingleResource(): void
    {
        $output = $this->createMock(OutputInterface::class);
        assert($output instanceof OutputInterface);

        $uri = $this->createMock(UriInterface::class);
        assert($uri instanceof UriInterface);

        $this->encoder->method('withOriginalUri')->willReturnSelf();
        $this->encoder->method('encodeData')->willReturn('{"data":{}}');

        $responseInterface = $this->createMock(ResponseInterface::class);
        assert($responseInterface instanceof ResponseInterface);
        $this->responseFactory->method('createOkResponse')->willReturn($responseInterface);

        $response = $this->builder->respondWithSingleResource($output, $uri);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    /**
     * Scenario: Responds with a created resource
     *
     * Given an entity and a request URI,
     * When the respondWithCreatedResource method is called,
     * Then it should return a response indicating that the resource was created.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testRespondWithCreatedResource(): void
    {
        $output = $this->createMock(OutputInterface::class);
        assert($output instanceof OutputInterface);

        $uri = $this->createMock(UriInterface::class);
        assert($uri instanceof UriInterface);

        $encoded = '{"data":{"id":"1"}}';

        $response = $this->createMock(ResponseInterface::class);
        assert($response instanceof ResponseInterface);

        $output->method('getId')->willReturn(1);

        $uri->method('getPath')->willReturn('/entity');
        $uri->method('withPath')->with('')->willReturnSelf();
        $uri->method('withQuery')->with('')->willReturnSelf();
        $uri->method('withFragment')->with('')->willReturnSelf();
        $uri->method('__toString')->willReturn('https://api.example.com');

        $this->encoder->expects($this->once())->method('withOriginalUri')->with($uri)->willReturnSelf();
        $this->encoder->expects($this->once())->method('encodeData')->with($output)->willReturn($encoded);

        $this->responseFactory->expects($this->once())
            ->method('createCreatedResponse')
            ->with($encoded, 'https://api.example.com/entity/1', [])
            ->willReturn($response);

        $actual = $this->builder->respondWithCreatedResource($output, $uri);

        self::assertSame($response, $actual);
    }

    /**
     * Scenario: Responds with an updated resource
     *
     * Given an entity and a request URI,
     * When the respondWithUpdatedResource method is called,
     * Then it should return a response indicating that the resource was updated.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testRespondWithUpdatedResource(): void
    {
        $output = $this->createMock(OutputInterface::class);
        assert($output instanceof OutputInterface);

        $uri = $this->createMock(UriInterface::class);
        assert($uri instanceof UriInterface);

        $encoded = '{"data":{"id":"1"}}';

        $response = $this->createMock(ResponseInterface::class);
        assert($response instanceof ResponseInterface);

        $this->encoder->method('withOriginalUri')->with($uri)->willReturnSelf();
        $this->encoder->method('encodeData')->with($output)->willReturn($encoded);
        $this->responseFactory->method('createOkResponse')->with($encoded, [])->willReturn($response);

        $actual = $this->builder->respondWithUpdatedResource($output, $uri);

        self::assertSame($response, $actual);
    }

    /**
     * Scenario: Responds with a deleted resource
     *
     * Given a request URI,
     * When the respondWithDeletedResource method is called,
     * Then it should return a response indicating that the resource was deleted.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testRespondWithDeletedResource(): void
    {
        $uri = $this->createMock(UriInterface::class);
        assert($uri instanceof UriInterface);

        $response = $this->createMock(ResponseInterface::class);
        assert($response instanceof ResponseInterface);

        $this->encoder->expects($this->once())->method('withOriginalUri')->with($uri)->willReturnSelf();
        $this->responseFactory->expects($this->once())->method('createNoContentResponse')->willReturn($response);

        $actual = $this->builder->respondWithDeletedResource($uri);

        self::assertSame($response, $actual);
    }

    /**
     * Scenario: Responds with an error
     *
     * Given an API error,
     * When the respondWithError method is called,
     * Then it should return a response with the encoded error.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testRespondWithError(): void
    {
        $error = $this->createMock(ApiErrorInterface::class);
        assert($error instanceof ApiErrorInterface);

        $encodedError = '{"errors":[{"detail":"Invalid"}]}';

        $response = $this->createMock(ResponseInterface::class);
        assert($response instanceof ResponseInterface);

        $this->encoder->expects($this->once())->method('encodeError')->with($error)->willReturn($encodedError);
        $this->responseFactory->expects($this->once())
            ->method('createApiResponse')
            ->with($encodedError, HttpStatusCode::BadRequest->value, [])
            ->willReturn($response);

        $actual = $this->builder->respondWithError($error);

        self::assertSame($response, $actual);
    }
}
