<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use Psr\Container\ContainerInterface;
use Throwable;

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
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return QueryParser The created QueryParser instance.
     * @throws Throwable If an error occurs during the creation of the parser.
     */
    public function __invoke(ContainerInterface $container): QueryParserInterface
    {
        $errorAggregator = $container->get(ErrorAggregatorInterface::class);
        assert($errorAggregator instanceof ErrorAggregatorInterface);

        $errorAggregator->clear();

        return new QueryParser(new PageParser($errorAggregator));
    }
}
