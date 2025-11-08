<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Entity\Contact;
use App\Domain\Repository\ContactRepositoryInterface;
use Psr\Container\ContainerInterface;
use Throwable;

use function assert;

/**
 * Factory class for creating instances of ReadContactUseCase.
 *
 * This class is responsible for creating a ReadContactUseCase instance with the
 * necessary dependencies.
 */
final readonly class ReadContactUseCaseFactory
{
    /**
     * Create a ReadContactUseCase instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return ReadContactUseCase The created ReadContactUseCase instance.
     * @throws Throwable If an error occurs during the creation of the use case.
     */
    public function __invoke(ContainerInterface $container): ReadContactUseCase
    {
        $entityManager = $container->get('persistence.doctrine.core_sqlite.entity_manager');
        assert($entityManager instanceof EntityManagerInterface);

        $contactRepository = $entityManager->getRepository(Contact::class);
        assert($contactRepository instanceof ContactRepositoryInterface);

        return new ReadContactUseCase($contactRepository);
    }
}
