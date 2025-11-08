<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

use App\UnitTestCase;
use Override;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;

use function assert;

/**
 * Feature: InputParserTest
 *
 * This class tests the InputParser functionality, ensuring that it correctly
 * parses query parameters and request body, and retrieves the combined data.
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class InputParserTest extends UnitTestCase
{
    /**
     * The QueryParser instance used for parsing query parameters.
     */
    private MockObject $queryParser;

    /**
     * The BodyParser instance used for parsing the request body.
     */
    private MockObject $bodyParser;

    /**
     * The InputParser instance being tested.
     */
    private InputParser $inputParser;

    /**
     * Sets up the test environment by creating mock instances of QueryParser and BodyParser.
     *
     * This method is called before each test method to ensure a clean state.
     *
     * @throws Exception If there is an error creating the mock objects.
     */
    #[Override]
    protected function setUp(): void
    {
        $this->queryParser = $this->createMock(QueryParserInterface::class);
        assert($this->queryParser instanceof QueryParserInterface);

        $this->bodyParser = $this->createMock(BodyParserInterface::class);
        assert($this->bodyParser instanceof BodyParserInterface);

        $this->inputParser = new InputParser($this->queryParser, $this->bodyParser);

        parent::setUp();
    }

    /**
     * Scenario: Parse query and body
     *
     * Given a resource ID, query parameters, and a request body,
     * When the parse method is called,
     * Then it should delegate parsing to both QueryParser and BodyParser.
     */
    public function testParseDelegatesToQueryAndBodyParsers(): void
    {
        assert($this->queryParser instanceof QueryParserInterface);
        assert($this->bodyParser instanceof BodyParserInterface);

        $resourceId = 'abc';
        $queryParams = ['page' => ['number' => 1]];
        $requestBody = ['key' => 'value'];

        $this->queryParser
            ->expects($this->once())
            ->method('parse')
            ->with($resourceId, $queryParams);

        $this->bodyParser
            ->expects($this->once())
            ->method('parse')
            ->with($requestBody);

        $this->inputParser->parse($resourceId, $queryParams, $requestBody);
    }

    /**
     * Scenario: Parse query only
     *
     * Given a resource ID and query parameters,
     * When the parseQuery method is called,
     * Then it should delegate parsing to QueryParser.
     */
    public function testParseQueryOnly(): void
    {
        assert($this->queryParser instanceof QueryParserInterface);

        $resourceId = 'xyz';
        $queryParams = ['page' => ['number' => 1]];

        $this->queryParser
            ->expects($this->once())
            ->method('parse')
            ->with($resourceId, $queryParams);

        $this->inputParser->parseQuery($resourceId, $queryParams);
    }

    /**
     * Scenario: Parse body only
     *
     * Given a request body,
     * When the parseBody method is called,
     * Then it should delegate parsing to BodyParser.
     */
    public function testParseBodyOnly(): void
    {
        assert($this->bodyParser instanceof BodyParserInterface);

        $requestBody = ['field' => 'value'];

        $this->bodyParser
            ->expects($this->once())
            ->method('parse')
            ->with($requestBody);

        $this->inputParser->parseBody($requestBody);
    }

    /**
     * Scenario: Get query data
     *
     * Given a QueryParser instance,
     * When getQueryData is called,
     * Then it should return the expected query data.
     */
    public function testGetQueryDataReturnsExpected(): void
    {
        assert($this->queryParser instanceof QueryParserInterface);

        $expected = ['resourceId' => 1, 'pageSize' => 10];

        $this->queryParser
            ->method('getData')
            ->willReturn($expected);

        $result = $this->inputParser->getQueryData();
        self::assertSame($expected, $result);
    }

    /**
     * Scenario: Get body data
     *
     * Given a BodyParser instance,
     * When getBodyData is called,
     * Then it should return the expected body data.
     */
    public function testGetBodyDataReturnsExpected(): void
    {
        assert($this->bodyParser instanceof BodyParserInterface);

        $expected = ['name' => 'Jonatan'];

        $this->bodyParser
            ->method('getData')
            ->willReturn($expected);

        $result = $this->inputParser->getBodyData();
        self::assertSame($expected, $result);
    }

    /**
     * Scenario: Get data merges query and body
     *
     * Given a QueryParser and BodyParser with specific data,
     * When getData is called,
     * Then it should return the merged data from both parsers.
     */
    public function testGetDataMergesQueryAndBody(): void
    {
        assert($this->queryParser instanceof QueryParserInterface);
        assert($this->bodyParser instanceof BodyParserInterface);

        $queryData = ['id' => 1, 'pageSize' => 20];
        $bodyData = ['name' => 'Jonatan'];

        $this->queryParser
            ->method('getData')
            ->willReturn($queryData);

        $this->bodyParser
            ->method('getData')
            ->willReturn($bodyData);

        $expected = array_merge($queryData, $bodyData);

        $result = $this->inputParser->getData();
        self::assertSame($expected, $result);
    }
}
