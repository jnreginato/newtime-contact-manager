<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

use App\UnitTestCase;
use PHPUnit\Framework\MockObject\Exception;

use function assert;

/**
 * Feature: QueryParserTest
 *
 * This class tests the QueryParser functionality, ensuring that it correctly
 * parses query parameters and retrieves the expected data structure.
 */
final class QueryParserTest extends UnitTestCase
{
    /**
     * Scenario: Parse delegates to each parser
     *
     * Given a set of query parameters
     * When the parse method is called,
     * Then it should delegate parsing to each specific parser
     * (PageParser, FilterParser, SortParser, IncludeParser, FieldsParser)
     *
     * @throws Exception If there is an error creating the mock objects.
     */
    public function testParseDelegatesToEachParser(): void
    {
        $pageParser = $this->createMock(PageParserInterface::class);
        assert($pageParser instanceof PageParserInterface);

        $queryParser = new QueryParser($pageParser);

        $params = ['page' => ['size' => 10]];

        $pageParser->expects($this->once())->method('parse')->with($params);

        $queryParser->parse('abc123', $params);
    }

    /**
     * Scenario: GetData returns the correct structure
     *
     * Given a QueryParser instance with mocked parsers
     * When getData is called after parsing
     * Then it should return the expected data structure
     *
     * @throws Exception If there is an error creating the mock objects.
     */
    public function testGetDataReturnsCorrectStructure(): void
    {
        $pageParser = $this->createMock(PageParserInterface::class);
        assert($pageParser instanceof PageParserInterface);
        $pageParser->method('getPageSize')->willReturn(25);
        $pageParser->method('getPageNumber')->willReturn(2);

        $queryParser = new QueryParser($pageParser);
        $queryParser->parse('abc123', []);

        $result = $queryParser->getData();

        $expected = [
            'resourceId' => 'abc123',
            'pageSize' => 25,
            'pageNumber' => 2,
        ];

        self::assertSame($expected, $result);
    }
}
