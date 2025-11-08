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
 * @phpstan-type Data array{resourceId: mixed}
 */
final class QueryParser implements QueryParserInterface
{
    /**
     * The identity of the resource being queried.
     */
    private mixed $resourceId = null;

    /**
     * Parses the query parameters and validates them against the defined rules.
     *
     * @param mixed $resourceId The identity of the resource being queried, it can be null.
     */
    #[Override]
    public function parse(mixed $resourceId): void
    {
        $this->resourceId = $resourceId;
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
        ];
    }
}
