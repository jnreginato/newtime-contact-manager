<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

/**
 * Interface QueryParserInterface
 *
 * This interface defines the contract for parsing query parameters in an API request.
 *
 * @phpstan-type QueryParameters array{page?: array{size?: int, number?: int}}
 * @phpstan-type Data array{resourceId: mixed, pageSize: int, pageNumber: int}
 */
interface QueryParserInterface
{
    /**
     * Parses the query parameters and stores the data in the query parser.
     *
     * @param mixed $resourceId The identity of the resource being queried, it can be null.
     * @param QueryParameters $parameters The query parameters from the request.
     */
    public function parse(mixed $resourceId, array $parameters = []): void;

    /**
     * Returns the parsed data from the query parser.
     *
     * @return Data The parsed data from the query parser.
     */
    public function getData(): array;
}
