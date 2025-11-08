<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Domain\Entity\Contact;
use App\Domain\Repository\ContactRepositoryInterface;
use App\Infrastructure\Api\Request\Mapper\ParametersMapperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Throwable;

use function assert;

/**
 * Factory class for creating instances of ListContactsUseCase.
 *
 * This class is responsible for creating a ListContactsUseCase instance with the
 * necessary dependencies.
 */
final readonly class ListContactsUseCaseFactory
{
    /**
     * Create a ListContactsUseCase instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return ListContactsUseCase The created ListContactsUseCase instance.
     * @throws Throwable If an error occurs during the creation of the use case.
     */
    public function __invoke(ContainerInterface $container): ListContactsUseCase
    {
        $entityManager = $container->get('persistence.doctrine.core_sqlite.entity_manager');
        assert($entityManager instanceof EntityManagerInterface);

        $contactRepository = $entityManager->getRepository(Contact::class);
        assert($contactRepository instanceof ContactRepositoryInterface);

        $mapper = $container->get(ParametersMapperInterface::class);
        assert($mapper instanceof ParametersMapperInterface);
        // @phpstan-ignore-next-line
        $mapper->setRepository($contactRepository);

        return new ListContactsUseCase($mapper, $contactRepository);
    }
}
