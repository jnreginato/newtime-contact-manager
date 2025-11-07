<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function assert;

/**
 * Factory class for creating instances of InputParser.
 *
 * This class is responsible for creating an InputParser instance with the
 * necessary dependencies, specifically a QueryParser and a BodyParser.
 */
final class InputParserFactory
{
    /**
     * Create an InputParser instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return InputParserInterface The created QueryParser instance.
     * @throws ContainerExceptionInterface If an error occurs during the creation of the parser.
     * @throws NotFoundExceptionInterface If a required dependency is not found in the container.
     */
    public function __invoke(ContainerInterface $container): InputParserInterface
    {
        $queryParser = $container->get(QueryParserInterface::class);
        assert($queryParser instanceof QueryParserInterface);

        $bodyParser = $container->get(BodyParserInterface::class);
        assert($bodyParser instanceof BodyParserInterface);

        return new InputParser($queryParser, $bodyParser);
    }
}
