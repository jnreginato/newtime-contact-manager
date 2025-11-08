<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response\Encoder;

use App\Application\Result\PaginatedResultInterface;
use App\Infrastructure\Api\Exception\ApiErrorInterface;
use App\Infrastructure\Api\Response\Encoder\FieldSet\FieldSetFilterInterface;
use App\Infrastructure\Api\Response\Encoder\Writer\DocumentWriterInterface;
use App\Infrastructure\Api\Response\OutputInterface;
use App\UnitTestCase;
use PHPUnit\Framework\MockObject\Exception;
use Psr\Http\Message\UriInterface;
use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

use function assert;

/**
 * Feature: EncoderTest
 *
 * This class tests the Encoder functionality, ensuring that it correctly encodes
 * API errors and data into JSON format. It verifies that the encoded output is valid JSON
 * and contains the expected content based on the provided API errors or entities.
 */
final class EncoderTest extends UnitTestCase
{
    /**
     * Scenario: Encode a single API error into JSON.
     *
     * Given a single API error,
     * When the error is encoded,
     * Then it should return a valid JSON string containing the error details.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testEncodeErrorWithSingleError(): void
    {
        $error = $this->createMock(ApiErrorInterface::class);
        assert($error instanceof ApiErrorInterface);

        $writer = $this->createMock(DocumentWriterInterface::class);
        assert($writer instanceof DocumentWriterInterface);

        $writer->expects($this->once())->method('addErrorToDocument')->with($error);
        $writer->expects($this->once())->method('getDocument')->willReturn(['errors' => [['title' => 'test']]]);

        $encoder = new Encoder($writer);
        $json = $encoder->encodeError($error);

        self::assertJson($json);
        self::assertStringContainsString('test', $json);
    }

    /**
     * Scenario: Encode an array of API errors into JSON.
     *
     * Given an array of API errors,
     * When the errors are encoded,
     * Then it should return a valid JSON string containing the error details.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testEncodeErrorWithArray(): void
    {
        $error = $this->createMock(ApiErrorInterface::class);
        assert($error instanceof ApiErrorInterface);

        $writer = $this->createMock(DocumentWriterInterface::class);
        assert($writer instanceof DocumentWriterInterface);

        $writer->expects($this->once())->method('addErrorToDocument')->with($error);
        $writer->expects($this->once())->method('getDocument')->willReturn(['errors' => [['title' => 'error array']]]);

        $encoder = new Encoder($writer);
        $json = $encoder->encodeError([$error]);

        self::assertJson($json);
        self::assertStringContainsString('error array', $json);
    }

    /**
     * Scenario: Encode data with a single entity into JSON.
     *
     * Given a single entity,
     * When the entity is encoded,
     * Then it should return a valid JSON string containing the resource data.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testEncodeDataWithOutput(): void
    {
        $output = $this->createMock(OutputInterface::class);
        assert($output instanceof OutputInterface);

        $writer = $this->createMock(DocumentWriterInterface::class);
        assert($writer instanceof DocumentWriterInterface);

        $writer
            ->expects($this->once())
            ->method('addResourceToDocument')
            ->with($output, self::isInstanceOf(FieldSetFilterInterface::class));

        $writer
            ->expects($this->once())
            ->method('getDocument')
            ->willReturn(['data' => ['test']]);

        $encoder = new Encoder($writer);
        $json = $encoder->encodeData($output);

        self::assertJson($json);
        self::assertStringContainsString('test', $json);
    }

    /**
     * Scenario: Encode data with an array of entities into JSON.
     *
     * Given an array of entities,
     * When the entities are encoded,
     * Then it should return a valid JSON string containing the resource data.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testEncodeDataWithArrayOfEntities(): void
    {
        $output = $this->createMock(OutputInterface::class);
        assert($output instanceof OutputInterface);

        $writer = $this->createMock(DocumentWriterInterface::class);
        assert($writer instanceof DocumentWriterInterface);

        $writer
            ->expects($this->once())
            ->method('withDataAsArray');

        $writer
            ->expects($this->once())
            ->method('addResourceToDocument')
            ->with($output, self::isInstanceOf(FieldSetFilterInterface::class));

        $writer
            ->expects($this->once())
            ->method('getDocument')
            ->willReturn(['data' => ['item']]);

        $encoder = new Encoder($writer);
        $json = $encoder->encodeData([$output]);

        self::assertJson($json);
        self::assertStringContainsString('item', $json);
    }

    /**
     * Scenario: Encode paginated data into JSON.
     *
     * Given a paginated data object,
     * When the paginated data is encoded,
     * Then it should return a valid JSON string containing pagination metadata and resource data.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testEncodeDataWithPagination(): void
    {
        $paginated = $this->createMock(PaginatedResultInterface::class);
        assert($paginated instanceof PaginatedResultInterface);

        $output = $this->createMock(OutputInterface::class);
        assert($output instanceof OutputInterface);

        $writer = $this->createMock(DocumentWriterInterface::class);
        assert($writer instanceof DocumentWriterInterface);

        $paginated->method('getData')->willReturn([$output]);
        $paginated->method('getCurrentPage')->willReturn(1);
        $paginated->method('getTotalPages')->willReturn(2);
        $paginated->method('getTotalItems')->willReturn(10);
        $paginated->method('getCount')->willReturn(5);
        $paginated->method('getPerPage')->willReturn(5);
        $paginated->method('hasMoreItems')->willReturn(true);

        $writer = $this->createMock(DocumentWriterInterface::class);
        assert($writer instanceof DocumentWriterInterface);

        $writer
            ->expects($this->once())
            ->method('withMeta');

        $writer
            ->expects($this->once())
            ->method('addResourceToDocument')
            ->with($output, self::isInstanceOf(FieldSetFilterInterface::class));

        $writer
            ->expects($this->once())
            ->method('getDocument')
            ->willReturn(['data' => ['paginated']]);

        $uri = $this->createMock(UriInterface::class);
        assert($uri instanceof UriInterface);
        $uri->method('getQuery')->willReturn('');
        $uri->method('withQuery')->willReturn($uri);
        $uri->method('__toString')->willReturn('http://example.test');

        $encoder = new Encoder($writer);
        $encoder->withOriginalUri($uri);

        $json = $encoder->encodeData($paginated);

        self::assertJson($json);
        self::assertStringContainsString('paginated', $json);
    }

    /**
     * Scenario: Encode data with an invalid JSON structure.
     *
     * Given an invalid JSON structure,
     * When the data is encoded,
     * Then it should throw an UnexpectedValueException.
     *
     * @throws Exception If the mock object creation fails.
     * @throws UnexpectedValueException If the JSON encoding fails.
     * @throws ReflectionException If the reflection fails.
     */
    public function testEncodeToJsonThrowsException(): void
    {
        $writer = $this->createMock(DocumentWriterInterface::class);
        assert($writer instanceof DocumentWriterInterface);

        $encoder = new Encoder($writer);

        $reflection = new ReflectionClass($encoder);
        $method = $reflection->getMethod('encodeToJson');

        $this->expectException(UnexpectedValueException::class);

        $method->invoke($encoder, ['invalid' => "\xB1\x31"]);
    }
}
