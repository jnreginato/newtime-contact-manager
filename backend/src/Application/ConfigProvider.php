<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\UseCase\CreateContactUseCase;
use App\Application\UseCase\CreateContactUseCaseFactory;

/**
 * The configuration provider for the Application module.
 *
 * @phpstan-type ServiceManagerConfiguration array{factories: array<class-string, class-string>}
 */
final readonly class ConfigProvider
{
    /**
     * Returns the configuration array.
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
     * Returns the dependencies for the Application module.
     *
     * @return ServiceManagerConfiguration
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                // UseCases
                CreateContactUseCase::class => CreateContactUseCaseFactory::class,
            ],
        ];
    }
}
