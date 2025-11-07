<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

/**
 * Factory class for creating instances of QueryParser.
 *
 * This class is responsible for creating a QueryParser instance with the
 * necessary dependencies.
 */
final class QueryParserFactory
{
    /**
     * Create a QueryParser instance.
     *
     * @return QueryParser The created QueryParser instance.
     */
    public function __invoke(): QueryParserInterface
    {
        return new QueryParser();
    }
}
