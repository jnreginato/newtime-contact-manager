<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use Doctrine\ORM\EntityManagerInterface;
use App\Application\Support\Transaction\TransactionRunner;
use App\Domain\Entity\Contact;
use App\Domain\Repository\ContactRepositoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

use function assert;

/**
 * Factory class for creating instances of UpdateContactUseCase.
 *
 * This class is responsible for creating an UpdateContactUseCase instance with the
 * necessary dependencies.
 */
final readonly class UpdateContactUseCaseFactory
{
    /**
     * Create an UpdateContactUseCase instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return UpdateContactUseCase The created UpdateContactUseCase instance.
     * @throws Throwable If an error occurs during the creation of the use case.
     */
    public function __invoke(ContainerInterface $container): UpdateContactUseCase
    {
        $entityManager = $container->get('persistence.doctrine.core_sqlite.entity_manager');
        assert($entityManager instanceof EntityManagerInterface);

        $contactRepository = $entityManager->getRepository(Contact::class);
        assert($contactRepository instanceof ContactRepositoryInterface);

        $transactionRunner = $container->get(TransactionRunner::class);
        assert($transactionRunner instanceof TransactionRunner);

        $logger = $container->get('log.file');
        assert($logger instanceof LoggerInterface);

        return new UpdateContactUseCase($contactRepository, $transactionRunner);
    }
}
