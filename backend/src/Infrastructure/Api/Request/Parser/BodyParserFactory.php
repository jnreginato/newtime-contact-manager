<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

use App\Infrastructure\Api\Request\DataCapture\DataCollectionInterface;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use Psr\Container\ContainerInterface;
use Throwable;

use function assert;

/**
 * Factory class for creating instances of BodyParser.
 *
 * This class is responsible for creating a BodyParser instance with the
 * necessary dependencies.
 */
final class BodyParserFactory
{
    /**
     * Create a BodyParser instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return BodyParser The created BodyParser instance.
     * @throws Throwable If an error occurs during the creation of the parser.
     */
    public function __invoke(ContainerInterface $container): BodyParserInterface
    {
        $captureAggregator = $container->get(DataCollectionInterface::class);
        assert($captureAggregator instanceof DataCollectionInterface);

        $errorCollection = $container->get(ErrorAggregatorInterface::class);
        assert($errorCollection instanceof ErrorAggregatorInterface);

        return new BodyParser($captureAggregator, $errorCollection);
    }
}
