<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Mapper;

use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use Psr\Container\ContainerInterface;
use Throwable;

use function assert;

/**
 * Factory class for creating instances of ParametersMapper.
 *
 * This class is responsible for creating a ParametersMapper instance with the
 * necessary dependencies, specifically the ErrorAggregatorInterface.
 */
final class ParametersMapperFactory
{
    /**
     * Create a ParametersMapper instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return ParametersMapperInterface The created ParametersMapper instance.
     * @throws Throwable If an error occurs during the creation of the mapper.
     */
    public function __invoke(ContainerInterface $container): ParametersMapperInterface
    {
        $errorAggregator = $container->get(ErrorAggregatorInterface::class);
        assert($errorAggregator instanceof ErrorAggregatorInterface);

        return new ParametersMapper(new QueryParameterApplier());
    }
}
