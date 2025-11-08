<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

/**
 * Interface InputParserInterface
 *
 * This interface defines the methods required for parsing and validating input data
 * in API requests, including query parameters and request body.
 *
 * @phpstan-type QueryParameters array{page?: array{size?: int, number?: int}}
 * @phpstan-type RequestBody array<string, mixed>
 * @phpstan-type QueryData array{resourceId: mixed, pageSize: int, pageNumber: int}
 * @phpstan-type BodyData array<string, mixed>
 * @phpstan-type Data QueryData & BodyData
 */
interface InputParserInterface
{
    /**
     * Parses the query parameters and body of the request.
     *
     * This method takes the identity of the resource being queried, the query parameters,
     * and the request body, and processes them to extract relevant data.
     *
     * @param mixed $resourceId The identity of the resource being queried, it can be null.
     * @param QueryParameters $queryParams The query parameters from the request.
     * @param RequestBody $requestBody The body of the request.
     */
    public function parse(mixed $resourceId, array $queryParams, array $requestBody): void;

    /**
     * Returns the combined data from both query and body parsers.
     *
     * This method merges the data collected from the query parameters and the request body
     * into a single associative array.
     *
     * @return Data The combined data from both query and body parsers.
     */
    public function getData(): array;

    /**
     * Parses the query parameters of the request.
     *
     * This method takes the query string and query parameters,
     * validates them, and stores the attributes in a data collection.
     *
     * @param string|null $resourceId The query string from the request.
     * @param QueryParameters $queryParams The query parameters from the request.
     */
    public function parseQuery(?string $resourceId, array $queryParams): void;

    /**
     * Parses the request body and extracts data from it.
     *
     * This method takes the raw request body as a string, decodes it,
     * validates the data, and stores the attributes in a data collection.
     *
     * @param RequestBody $requestBody The raw request body to be parsed.
     */
    public function parseBody(array $requestBody): void;

    /**
     * Returns the parsed data from query parser.
     *
     * This method retrieves the data collected from the query parameters,
     * typically in an associative array format.
     *
     * @return QueryData The parsed query data.
     */
    public function getQueryData(): array;

    /**
     * Returns the parsed data from the request body.
     *
     * This method retrieves the data collected from the request body,
     * typically in an associative array format.
     *
     * @return BodyData The parsed body data.
     */
    public function getBodyData(): array;
}
