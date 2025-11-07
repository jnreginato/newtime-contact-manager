<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Entity\EntityInterface;

/**
 * Interface RepositoryInterface
 *
 * This interface defines the contract for a repository that manages entities.
 */
interface RepositoryInterface
{
    /**
     * Saves an entity to the repository.
     *
     * This method persists the provided entity and flushes the changes to the database.
     *
     * @param EntityInterface $entity The entity to save.
     * @return EntityInterface The saved entity.
     */
    public function save(EntityInterface $entity): EntityInterface;

    /**
     * Deletes an entity from the repository.
     *
     * This method removes the provided entity and flushes the changes to the database.
     *
     * @param EntityInterface $entity The entity to delete.
     */
    public function delete(EntityInterface $entity): void;
}
