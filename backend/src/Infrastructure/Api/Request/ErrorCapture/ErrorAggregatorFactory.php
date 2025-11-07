<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\ErrorCapture;

use Psr\Container\ContainerInterface;
use Throwable;

use function assert;

/**
 * Factory class for creating instances of ErrorAggregator.
 *
 * This class is responsible for creating an ErrorAggregator instance with the
 * necessary dependencies.
 */
final class ErrorAggregatorFactory
{
    /**
     * Create an ErrorAggregator instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return ErrorAggregatorInterface The created ErrorAggregator instance.
     * @throws Throwable If an error occurs during the creation of the aggregator.
     */
    public function __invoke(ContainerInterface $container): ErrorAggregatorInterface
    {
        $errorCollection = $container->get(ErrorCollectionInterface::class);
        assert($errorCollection instanceof ErrorCollectionInterface);

        return new ErrorAggregator($errorCollection);
    }
}
