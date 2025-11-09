<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

use App\Infrastructure\Api\Exception\ApiInvalidBodyException;
use App\Infrastructure\Api\Exception\ErrorMessage;
use App\Infrastructure\Api\Request\DataCapture\DataCollectionInterface;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorCollectionInterface;
use App\UnitTestCase;
use PHPUnit\Framework\MockObject\Exception;

use function assert;

/**
 * Feature: BodyParserTest
 *
 * This class tests the BodyParser functionality, ensuring that it correctly
 * parses JSON bodies, handles errors, and stores attributes in the data collection.
 */
final class BodyParserTest extends UnitTestCase
{
    /**
     * Scenario: Parse valid JSON body
     *
     * Give a valid JSON body
     * When the parse method is called,
     * Then it should store the attributes in the data collection without errors.
     *
     * @throws Exception If there is an error creating the mock object.
     */
    public function testParseValidJsonStoresAttributes(): void
    {
        $dataCollection = $this->createMock(DataCollectionInterface::class);
        assert($dataCollection instanceof DataCollectionInterface);

        $errorAggregator = $this->createMock(ErrorAggregatorInterface::class);
        assert($errorAggregator instanceof ErrorAggregatorInterface);

        $dataCollection->expects($this->once())->method('clear');
        $errorAggregator->expects($this->once())->method('clear');

        $dataCollection->expects($this->exactly(2))
            ->method('remember')
            ->willReturn($dataCollection);

        $parser = new BodyParser($dataCollection, $errorAggregator);
        $parser->parse(['foo' => 'bar', 'baz' => 123]);
    }

    /**
     * Scenario: Parse invalid JSON body
     *
     * Given an invalid JSON body
     * When the parse method is called,
     * Then it should throw an ApiInvalidBodyException with the appropriate error message.
     *
     * @throws Exception If there is an error creating the mock object.
     */
    public function testParseThrowsExceptionOnInvalidJson(): void
    {
        $this->expectException(ApiInvalidBodyException::class);

        $dataCollection = $this->createMock(DataCollectionInterface::class);
        assert($dataCollection instanceof DataCollectionInterface);

        $errorAggregator = $this->createMock(ErrorAggregatorInterface::class);
        assert($errorAggregator instanceof ErrorAggregatorInterface);

        $errorCollection = $this->createMock(ErrorCollectionInterface::class);
        assert($errorCollection instanceof ErrorCollectionInterface);

        $errorAggregator->method('getErrorCollection')->willReturn($errorCollection);
        $errorAggregator->method('getResponseStatusCode')->willReturn(422);

        $errorAggregator->expects($this->once())
            ->method('addApiError')
            ->with(ErrorMessage::RequestBodyMustBeJsonObject->value);

        $parser = new BodyParser($dataCollection, $errorAggregator);

        // @phpstan-ignore-next-line
        $parser->parse(['invalid_json']);
    }

    /**
     * Scenario: Parse non-object JSON body
     *
     * Given a JSON body that is not an object
     * When the parse method is called,
     * Then it should throw an ApiInvalidBodyException with the appropriate error message.
     *
     * @throws Exception If there is an error creating the mock object.
     */
    public function testParseThrowsExceptionOnNonObjectJson(): void
    {
        $this->expectException(ApiInvalidBodyException::class);

        $dataCollection = $this->createMock(DataCollectionInterface::class);
        assert($dataCollection instanceof DataCollectionInterface);

        $errorAggregator = $this->createMock(ErrorAggregatorInterface::class);
        assert($errorAggregator instanceof ErrorAggregatorInterface);

        $errorCollection = $this->createMock(ErrorCollectionInterface::class);
        assert($errorCollection instanceof ErrorCollectionInterface);

        $errorAggregator->method('getErrorCollection')->willReturn($errorCollection);
        $errorAggregator->method('getResponseStatusCode')->willReturn(422);

        $errorAggregator->expects($this->once())
            ->method('addApiError')
            ->with(ErrorMessage::RequestBodyMustBeJsonObject->value);

        $parser = new BodyParser($dataCollection, $errorAggregator);

        // @phpstan-ignore-next-line
        $parser->parse([1, 2, 3]);
    }

    /**
     * Scenario: Parse JSON body with numeric keys
     *
     * Given a JSON body with numeric keys
     * When the parse method is called,
     * Then it should throw an ApiInvalidBodyException with the appropriate error message.
     *
     * @throws Exception If there is an error creating the mock object.
     */
    public function testParseThrowsExceptionOnNumericKeys(): void
    {
        $this->expectException(ApiInvalidBodyException::class);

        $dataCollection = $this->createMock(DataCollectionInterface::class);
        assert($dataCollection instanceof DataCollectionInterface);

        $errorAggregator = $this->createMock(ErrorAggregatorInterface::class);
        assert($errorAggregator instanceof ErrorAggregatorInterface);

        $errorCollection = $this->createMock(ErrorCollectionInterface::class);
        assert($errorCollection instanceof ErrorCollectionInterface);

        $errorAggregator->method('getErrorCollection')->willReturn($errorCollection);
        $errorAggregator->method('getResponseStatusCode')->willReturn(422);

        $errorAggregator->expects($this->once())
            ->method('addApiError')
            ->with(ErrorMessage::RequestBodyMustBeJsonObject->value);

        $parser = new BodyParser($dataCollection, $errorAggregator);

        // @phpstan-ignore-next-line
        $parser->parse(['0' => 'zero', '1' => 'um']);
    }

    /**
     * Scenario: Get data returns expected result
     *
     * Given a BodyParser instance with a data collection
     * When the getData method is called,
     * Then it should return the expected data.
     *
     * @throws Exception If there is an error creating the mock object.
     */
    public function testGetDataReturnsExpected(): void
    {
        $expected = ['name' => 'test'];

        $dataCollection = $this->createMock(DataCollectionInterface::class);
        assert($dataCollection instanceof DataCollectionInterface);

        $errorAggregator = $this->createMock(ErrorAggregatorInterface::class);
        assert($errorAggregator instanceof ErrorAggregatorInterface);

        $dataCollection->expects($this->once())
            ->method('get')
            ->willReturn($expected);

        $parser = new BodyParser($dataCollection, $errorAggregator);

        self::assertSame($expected, $parser->getData());
    }
}
