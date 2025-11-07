<?php

declare(strict_types=1);

namespace App\Infrastructure\Config;

use Psr\Container\ContainerInterface;
use Throwable;

/**
 * Factory responsible for creating a ConfigService instance.
 *
 * This factory retrieves the configuration from the container and passes it
 * to the ConfigService constructor.
 */
final class ConfigServiceFactory
{
    /**
     * Invokes the factory to create a ConfigService instance.
     *
     * @param ContainerInterface $container The PSR-11 container
     * @return ConfigServiceInterface The created ConfigService instance
     * @throws Throwable If any error occurs during ConfigService creation
     */
    public function __invoke(ContainerInterface $container): ConfigServiceInterface
    {
        return new ConfigService((array) $container->get('config'));
    }
}
