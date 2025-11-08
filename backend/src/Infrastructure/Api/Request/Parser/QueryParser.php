<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

use Override;

/**
 * Class QueryParser
 *
 * This class is responsible for parsing and validating query parameters in an API request.
 * It stores the parsed data in a data collection.
 *
 * @phpstan-type QueryParameters array{page?: array{size?: int, number?: int}}
 * @phpstan-type Data array{resourceId: mixed, pageSize: int, pageNumber: int}
 */
final class QueryParser implements QueryParserInterface
{
    /**
     * The identity of the resource being queried.
     */
    private mixed $resourceId = null;

    /**
     * Constructor.
     *
     * Initializes the QueryParser with the necessary parsers and error aggregator.
     *
     * @param PageParserInterface $pageParser The parser for pagination parameters.
     */
    public function __construct(private readonly PageParserInterface $pageParser)
    {
    }

    /**
     * Parses the query parameters and validates them against the defined rules.
     *
     * @param mixed $resourceId The identity of the resource being queried, it can be null.
     * @param QueryParameters $parameters The query parameters from the request.
     */
    #[Override]
    public function parse(mixed $resourceId, array $parameters = []): void
    {
        $this->resourceId = $resourceId;
        $this->pageParser->parse($parameters);
    }

    /**
     * Returns the parsed data from the query parser.
     *
     * This method retrieves the data collected from the query parameters,
     * typically in an associative array format.
     *
     * @return Data The parsed query data.
     */
    #[Override]
    public function getData(): array
    {
        return [
            'resourceId' => $this->resourceId,
            'pageSize' => $this->pageParser->getPageSize(),
            'pageNumber' => $this->pageParser->getPageNumber(),
        ];
    }
}
