<?php

declare(strict_types=1);

namespace App\Infrastructure\Config;

/**
 * ConfigProvider for the Config module.
 *
 * This class provides configuration for the Config module, including
 * dependencies and factories.
 *
 * @psalm-type ServiceManagerConfiguration = array{
 *     factories?: array<class-string, class-string>,
 * }
 */
final class ConfigProvider
{
    /**
     * Invokes the configuration provider.
     *
     * @return array{dependencies: ServiceManagerConfiguration}
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the dependencies for the Config module.
     *
     * @return ServiceManagerConfiguration
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                ConfigServiceInterface::class => ConfigServiceFactory::class,
            ],
        ];
    }
}
