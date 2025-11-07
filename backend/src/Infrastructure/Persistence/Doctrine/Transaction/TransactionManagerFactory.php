<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Transaction;

use Doctrine\ORM\EntityManagerInterface;
use App\Application\Support\Transaction\TransactionManagerInterface;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Throwable;

/**
 * Factory class for creating instances of TransactionManager.
 *
 * This class is responsible for creating a TransactionManager instance with the
 * necessary dependencies.
 */
final class TransactionManagerFactory
{
    /**
     * Create a TransactionManager instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return TransactionManagerInterface The created TransactionManager instance.
     * @throws Throwable If an error occurs during the creation of the transaction manager.
     */
    public function __invoke(ContainerInterface $container): TransactionManagerInterface
    {
        $entityManager = $container->get('persistence.doctrine.core_sqlite.entity_manager');

        if (!$entityManager instanceof EntityManagerInterface) {
            throw new RuntimeException("Entity manager isn't found or invalid.");
        }

        return new TransactionManager($entityManager);
    }
}
