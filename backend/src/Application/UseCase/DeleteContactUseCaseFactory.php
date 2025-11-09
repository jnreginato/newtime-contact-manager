<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Support\Transaction\TransactionRunner;
use App\Domain\Entity\Contact;
use App\Domain\Repository\ContactRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;
use Throwable;

use function assert;

/**
 * Factory class for creating instances of DeleteContactUseCase.
 *
 * This class is responsible for creating a DeleteContactUseCase instance with the
 * necessary dependencies.
 */
final readonly class DeleteContactUseCaseFactory
{
    /**
     * Create a DeleteContactUseCase instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return DeleteContactUseCase The created DeleteContactUseCase instance.
     * @throws Throwable If an error occurs during the creation of the use case.
     */
    public function __invoke(ContainerInterface $container): DeleteContactUseCase
    {
        $entityManager = $container->get('persistence.doctrine.core_sqlite.entity_manager');
        assert($entityManager instanceof EntityManagerInterface);

        $contactRepository = $entityManager->getRepository(Contact::class);
        assert($contactRepository instanceof ContactRepositoryInterface);

        $transactionRunner = $container->get(TransactionRunner::class);
        assert($transactionRunner instanceof TransactionRunner);

        $clock = $container->get(ClockInterface::class);
        assert($clock instanceof ClockInterface);

        return new DeleteContactUseCase($contactRepository, $transactionRunner, $clock);
    }
}
