<?php

declare(strict_types=1);

namespace App\Infrastructure\Time;

use Psr\Clock\ClockInterface;

/**
 * ConfigProvider for the Time module.
 *
 * This class provides configuration for the Time module, including
 * dependencies and invokables.
 *
 * @psalm-type ServiceManagerConfiguration = array{
 *     invokables?: array<class-string, class-string>,
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
     * Returns the dependencies for the Time module.
     *
     * @return ServiceManagerConfiguration
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                ClockInterface::class => SystemClockUTC::class,
            ],
        ];
    }
}
