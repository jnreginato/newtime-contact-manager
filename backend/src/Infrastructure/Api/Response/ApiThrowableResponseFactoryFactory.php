<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

use App\Infrastructure\Api\Exception\ThrowableConverterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

use function assert;

/**
 * Class ApiThrowableResponseFactoryFactory.
 *
 * This factory class is responsible for creating instances of JsonApiThrowableResponseFactoryInterface.
 * It retrieves the necessary dependencies from the container and initializes the factory.
 */
final class ApiThrowableResponseFactoryFactory
{
    /**
     * Invokes the factory to create a JsonApiThrowableResponseFactoryInterface instance.
     *
     * This method retrieves the logger and throwable converter from the container,
     * initializes the JsonApiThrowableResponseFactory, and sets the logger.
     *
     * @param ContainerInterface $container The container from which dependencies are retrieved.
     * @return ApiThrowableResponseFactoryInterface The created response factory instance.
     * @throws ContainerExceptionInterface If there is an error retrieving a dependency from the container.
     */
    public function __invoke(ContainerInterface $container): ApiThrowableResponseFactoryInterface
    {
        $logger = $container->get('log.file');
        assert($logger instanceof LoggerInterface, 'Expected logger to be an instance of Psr\Log\LoggerInterface');

        $converter = $container->get(ThrowableConverterInterface::class);
        assert($converter instanceof ThrowableConverterInterface);

        $throwableResponseFactory = new ApiThrowableResponseFactory($converter);
        $throwableResponseFactory->setLogger($logger);

        return $throwableResponseFactory;
    }
}
