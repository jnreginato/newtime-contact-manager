<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use Psr\Container\ContainerInterface;
use Throwable;

use function assert;

/**
 * Factory class for creating instances of ThrowableConverter.
 *
 * This class is responsible for creating a ThrowableConverter instance with the
 * necessary dependencies.
 */
final class ThrowableConverterFactory
{
    /**
     * Create a ThrowableConverter instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return ThrowableConverterInterface The created ThrowableConverter instance.
     * @throws Throwable If an error occurs during the creation of the converter.
     */
    public function __invoke(ContainerInterface $container): ThrowableConverterInterface
    {
        $errorAggregator = $container->get(ErrorAggregatorInterface::class);
        assert($errorAggregator instanceof ErrorAggregatorInterface);

        return new ThrowableConverter($errorAggregator);
    }
}
