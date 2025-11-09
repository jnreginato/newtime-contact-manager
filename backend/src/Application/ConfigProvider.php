<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\UseCase\CreateContactUseCase;
use App\Application\UseCase\CreateContactUseCaseFactory;
use App\Application\UseCase\DeleteContactUseCase;
use App\Application\UseCase\DeleteContactUseCaseFactory;
use App\Application\UseCase\ListContactsUseCase;
use App\Application\UseCase\ListContactsUseCaseFactory;
use App\Application\UseCase\ReadContactUseCase;
use App\Application\UseCase\ReadContactUseCaseFactory;
use App\Application\UseCase\UpdateContactUseCase;
use App\Application\UseCase\UpdateContactUseCaseFactory;

/**
 * The configuration provider for the Application module.
 *
 * @phpstan-type ServiceManagerConfiguration array{factories: array<class-string, class-string>}
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
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
                ListContactsUseCase::class => ListContactsUseCaseFactory::class,
                ReadContactUseCase::class => ReadContactUseCaseFactory::class,
                CreateContactUseCase::class => CreateContactUseCaseFactory::class,
                UpdateContactUseCase::class => UpdateContactUseCaseFactory::class,
                DeleteContactUseCase::class => DeleteContactUseCaseFactory::class,

                // Support
                Support\Transaction\TransactionRunner::class => Support\Transaction\TransactionRunnerFactory::class,
            ],
        ];
    }
}
