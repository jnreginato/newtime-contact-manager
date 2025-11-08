<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

/**
 * PageParserInterface defines the contract for parsing paging parameters from a request.
 *
 * It extends the BaseParserInterface and provides a method to parse
 * paging parameters such as page size and page number.
 *
 * @psalm-type PageParameters = array{
 *     size?: int,
 *     number?: int,
 * }
 * @psalm-type QueryParameters = array{
 *     page?: PageParameters,
 *     ...
 * }
 */
interface PageParserInterface
{
    /**
     * Parses the paging parameters from the request.
     *
     * This method checks if the 'page' parameter exists in the request.
     * If it does, it validates that it is an array and contains the expected keys.
     * If the validation fails, it adds an error to the error aggregator and throws an API error.
     * The expected keys in the 'page' parameter are:
     * - 'size': The number of items per page (default is 10).
     * - 'number': The current page number (default is 1).
     *
     * @param QueryParameters $parameters The parameters passed in the query.
     */
    public function parse(array $parameters): void;

    /**
     * Returns the page size.
     *
     * @return int The size of the page.
     */
    public function getPageSize(): int;

    /**
     * Returns the current page number.
     *
     * @return int The current page number.
     */
    public function getPageNumber(): int;
}
