<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Support\Transaction\TransactionRunner;
use App\Domain\Entity\Contact;
use App\Domain\Repository\ContactRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Throwable;

/**
 * Factory for creating instances of CreateContactUseCase.
 */
final readonly class CreateContactUseCaseFactory
{
    /**
     * Create a CreateContactUseCase instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return CreateContactUseCase The created CreateContactUseCase instance.
     * @throws Throwable If an error occurs during the creation of the use case.
     */
    public function __invoke(ContainerInterface $container): CreateContactUseCase
    {
        $entityManager = $container->get('persistence.doctrine.core_sqlite.entity_manager');
        assert($entityManager instanceof EntityManagerInterface);

        $contactRepository = $entityManager->getRepository(Contact::class);
        assert($contactRepository instanceof ContactRepositoryInterface);

        $transactionRunner = $container->get(TransactionRunner::class);
        assert($transactionRunner instanceof TransactionRunner);

        return new CreateContactUseCase($contactRepository, $transactionRunner);
    }
}
