<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response\Encoder\Writer;

use App\Infrastructure\Api\Exception\ApiErrorInterface;
use App\Infrastructure\Api\Response\Encoder\FieldSet\FieldSetFilterInterface;
use App\Infrastructure\Api\Response\JsonApiKeyword;
use App\Infrastructure\Api\Response\Link\LinkInterface;
use App\Infrastructure\Api\Response\OutputInterface;
use App\UnitTestCase;
use PHPUnit\Framework\MockObject\Exception;
use stdClass;
use UnexpectedValueException;

use function assert;
use function fopen;

/**
 * Feature: DocumentWriterTest
 *
 * This class tests the DocumentWriter functionality, ensuring that it correctly
 * adds resources, errors, links, and metadata to the JSON:API document structure.
 */
final class DocumentWriterTest extends UnitTestCase
{
    /**
     * Scenario: Add a single resource to the document.
     *
     * Given a resource and a filter,
     * When the resource is added to the document,
     * Then it should be present in the document data.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testAddSingleResource(): void
    {
        $resource = $this->createMock(OutputInterface::class);
        assert($resource instanceof OutputInterface);

        $filter = $this->createMock(FieldSetFilterInterface::class);
        assert($filter instanceof FieldSetFilterInterface);

        $resource->method('getType')->willReturn('foo');
        $filter->method('getFields')->willReturn(['name' => 'value']);
        $filter->method('getRelationships')->willReturn([]);

        $writer = new DocumentWriter();
        $writer->addResourceToDocument($resource, $filter);

        $doc = $writer->getDocument();

        self::assertArrayHasKey(JsonApiKeyword::Data->value, $doc);
        // @phpstan-ignore-next-line
        self::assertSame(['name' => 'value'], $doc[JsonApiKeyword::Data->value]);
    }

    /**
     * Scenario: Add multiple resources to the document.
     *
     * Given a resource and a filter,
     * When the resource is added multiple times to the document,
     * Then it should contain multiple entries in the data array.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testAddMultipleResources(): void
    {
        $resource = $this->createMock(OutputInterface::class);
        assert($resource instanceof OutputInterface);

        $filter = $this->createMock(FieldSetFilterInterface::class);
        assert($filter instanceof FieldSetFilterInterface);

        $resource->method('getType')->willReturn('bar');
        $filter->method('getFields')->willReturn(['x' => 1]);
        $filter->method('getRelationships')->willReturn([]);

        $writer = (new DocumentWriter())->withDataAsArray();
        $writer->addResourceToDocument($resource, $filter);
        $writer->addResourceToDocument($resource, $filter);

        $doc = $writer->getDocument();

        self::assertArrayHasKey(JsonApiKeyword::Data->value, $doc);
        // @phpstan-ignore-next-line
        self::assertCount(2, $doc[JsonApiKeyword::Data->value]);
    }

    /**
     * Scenario: Add an error to the document.
     *
     * Given an API error,
     * When the error is added to the document,
     * Then it should be present in the errors array.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testAddErrorWithDetails(): void
    {
        $error = $this->createMock(ApiErrorInterface::class);
        assert($error instanceof ApiErrorInterface);
        $error->method('getCode')->willReturn('E123');
        $error->method('getDetail')->willReturn('Something went wrong');
        $error->method('getSource')->willReturn(['pointer' => '/data']);
        $error->method('getStatus')->willReturn('400');
        $error->method('getTitle')->willReturn('Invalid Request');

        $writer = new DocumentWriter();
        $writer->addErrorToDocument($error);

        $doc = $writer->getDocument();
        self::assertArrayHasKey(JsonApiKeyword::Errors->value, $doc);
        // @phpstan-ignore-next-line
        self::assertSame('E123', $doc[JsonApiKeyword::Errors->value][0]['code']);
    }

    /**
     * Scenario: Add an empty error to the document.
     *
     * Given an API error with no details,
     * When the error is added to the document,
     * Then it should still be present as an empty object in the errors array.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testAddEmptyError(): void
    {
        $error = $this->createMock(ApiErrorInterface::class);
        assert($error instanceof ApiErrorInterface);
        $error->method('getCode')->willReturn(null);
        $error->method('getDetail')->willReturn(null);
        $error->method('getSource')->willReturn(null);
        $error->method('getStatus')->willReturn(null);
        $error->method('getTitle')->willReturn(null);

        $writer = new DocumentWriter();
        $writer->addErrorToDocument($error);

        $doc = $writer->getDocument();
        self::assertArrayHasKey(JsonApiKeyword::Errors->value, $doc);
        // @phpstan-ignore-next-line
        self::assertEquals([new stdClass()], $doc[JsonApiKeyword::Errors->value]);
    }

    /**
     * Scenario: Add links to the document.
     *
     * Given a link that can be shown as a string,
     * When the link is added to the document,
     * Then it should be present in the links array with its string representation.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testWithLinks(): void
    {
        $link = $this->createMock(LinkInterface::class);
        assert($link instanceof LinkInterface);
        $link->method('canBeShownAsString')->willReturn(true);
        $link->method('getStringRepresentation')->willReturn('/link');

        $writer = new DocumentWriter();
        $writer->withLinks(['self' => $link]);

        $doc = $writer->getDocument();
        self::assertArrayHasKey(JsonApiKeyword::Links->value, $doc);
        // @phpstan-ignore-next-line
        self::assertSame(['/link'], array_values($doc[JsonApiKeyword::Links->value]));
    }

    /**
     * Scenario: Add metadata to the document.
     *
     * Given metadata,
     * When the metadata is added to the document,
     * Then it should be present in the meta array.
     */
    public function testWithMeta(): void
    {
        $writer = new DocumentWriter();
        $writer->withMeta(['page' => 1]);

        $doc = $writer->getDocument();
        self::assertArrayHasKey(JsonApiKeyword::Meta->value, $doc);
        // @phpstan-ignore-next-line
        self::assertSame(['page' => 1], $doc[JsonApiKeyword::Meta->value]);
    }

    /**
     * Scenario: Initialize the document with an empty data array.
     *
     * Given a new DocumentWriter instance,
     * When it is initialized with data as an array,
     * Then the data array should be empty.
     */
    public function testWithDataAsArrayInitializesArray(): void
    {
        $writer = new DocumentWriter();
        $writer->withDataAsArray();

        $doc = $writer->getDocument();
        self::assertArrayHasKey(JsonApiKeyword::Data->value, $doc);
        // @phpstan-ignore-next-line
        self::assertSame([], $doc[JsonApiKeyword::Data->value]);
    }

    /**
     * Scenario: Throws an exception on invalid JSON serialization.
     *
     * Given a resource with non-serializable fields,
     * When the resource is added to the document,
     * Then it should throw an UnexpectedValueException.
     *
     * @throws Exception If the mock object creation fails.
     */
    public function testThrowsOnInvalidJsonSerialization(): void
    {
        $resource = $this->createMock(OutputInterface::class);
        assert($resource instanceof OutputInterface);

        $filter = $this->createMock(FieldSetFilterInterface::class);
        assert($filter instanceof FieldSetFilterInterface);

        $resource->method('getType')->willReturn('faulty');

        // resource contains non-serializable value
        $filter->method('getFields')->willReturn(['invalid' => fopen(__FILE__, 'rb')]);
        $filter->method('getRelationships')->willReturn([]);

        $writer = new DocumentWriter();

        $this->expectException(UnexpectedValueException::class);
        $writer->addResourceToDocument($resource, $filter);
    }
}
