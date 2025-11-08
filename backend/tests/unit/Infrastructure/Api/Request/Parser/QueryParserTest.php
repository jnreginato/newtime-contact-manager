<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

use Generator;
use App\Infrastructure\Api\Request\Mapper\Filter\FilterJoinType;
use App\Infrastructure\Api\Request\Parser\Query\FieldsParserInterface;
use App\Infrastructure\Api\Request\Parser\Query\FilterParserInterface;
use App\Infrastructure\Api\Request\Parser\Query\IncludeParserInterface;
use App\Infrastructure\Api\Request\Parser\Query\PageParserInterface;
use App\Infrastructure\Api\Request\Parser\Query\SortParserInterface;
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
     * When the parse method is called
     * Then it should delegate parsing to each specific parser
     * (PageParser, FilterParser, SortParser, IncludeParser, FieldsParser)
     *
     * @throws Exception If there is an error creating the mock objects.
     */
    public function testParseDelegatesToEachParser(): void
    {
        $pageParser = $this->createMock(PageParserInterface::class);
        assert($pageParser instanceof PageParserInterface);

        $filterParser = $this->createMock(FilterParserInterface::class);
        assert($filterParser instanceof FilterParserInterface);

        $sortParser = $this->createMock(SortParserInterface::class);
        assert($sortParser instanceof SortParserInterface);

        $includeParser = $this->createMock(IncludeParserInterface::class);
        assert($includeParser instanceof IncludeParserInterface);

        $fieldsParser = $this->createMock(FieldsParserInterface::class);
        assert($fieldsParser instanceof FieldsParserInterface);

        $queryParser = new QueryParser($pageParser, $filterParser, $sortParser, $includeParser, $fieldsParser);

        $params = ['page' => ['size' => 10]];

        $pageParser->expects($this->once())->method('parse')->with($params);
        $filterParser->expects($this->once())->method('parse')->with($params);
        $sortParser->expects($this->once())->method('parse')->with($params);
        $includeParser->expects($this->once())->method('parse')->with($params);
        $fieldsParser->expects($this->once())->method('parse')->with($params);

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

        $filterParser = $this->createMock(FilterParserInterface::class);
        assert($filterParser instanceof FilterParserInterface);
        $filterParser->method('getFilter')->willReturn($this->generatorFromArray([
            'status' => $this->generatorFromArray([
                0 => $this->generatorFromArray(['active']),
            ]),
        ]));
        $filterParser->method('getFilterJoinType')->willReturn(FilterJoinType::And->value);

        $sortParser = $this->createMock(SortParserInterface::class);
        assert($sortParser instanceof SortParserInterface);
        $sortParser->method('getSort')->willReturn($this->generatorFromArray([
            'name' => 'asc',
        ]));

        $includeParser = $this->createMock(IncludeParserInterface::class);
        assert($includeParser instanceof IncludeParserInterface);
        $includeParser->method('getInclude')->willReturn($this->generatorFromArray([
            'user' => $this->generatorFromArray(['profile']),
        ]));

        $fieldsParser = $this->createMock(FieldsParserInterface::class);
        assert($fieldsParser instanceof FieldsParserInterface);
        $fieldsParser->method('getFields')->willReturn($this->generatorFromArray([
            'user' => $this->generatorFromArray(['id', 'name']),
        ]));

        $queryParser = new QueryParser($pageParser, $filterParser, $sortParser, $includeParser, $fieldsParser);

        $queryParser->parse('abc123', []);

        $result = $queryParser->getData();

        $expected = [
            'resourceId' => 'abc123',
            'pageSize' => 25,
            'pageNumber' => 2,
            'filterJoinType' => FilterJoinType::And->value,
            'filter' => [
                'status' => [
                    0 => ['active'],
                ],
            ],
            'sort' => ['name' => 'asc'],
            'include' => ['user' => ['profile']],
            'fields' => ['user' => ['id', 'name']],
        ];

        self::assertSame($expected, $result);
    }

    /**
     * Helper function to create a generator from an array.
     *
     * This function yields key-value pairs from the provided array.
     *
     * @param array<array-key, mixed> $parameters The array to convert into a generator.
     * @return Generator<mixed, mixed> A generator yielding key-value pairs from the array.
     */
    private function generatorFromArray(array $parameters): Generator
    {
        foreach ($parameters as $field => $value) {
            yield $field => $value;
        }
    }
}
