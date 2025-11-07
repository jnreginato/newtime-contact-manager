<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\EntityManager;

use DateTimeInterface;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\PessimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Override;

/**
 * EntityManagerPersistenceAccess trait.
 *
 * This trait provides methods for managing entities in the Doctrine EntityManager.
 * It includes methods for persisting, removing, and finding entities, as well
 * as managing the entity lifecycle.
 */
trait EntityManagerPersistenceAccess
{
    /**
     * Finds an Entity by its identifier.
     *
     * @param class-string $className The class name of the entity to find
     * @param mixed $id The identity of the entity to find
     * @param LockMode|int|null $lockMode One of the \Doctrine\DBAL\LockMode::* constants or NULL
     * @param int|null $lockVersion The version of the entity to find when using optimistic locking
     * @phpstan-param class-string<T> $className
     * @phpstan-param LockMode::*|null $lockMode
     * @return object|null The entity instance or NULL if the entity cannot be found
     * @phpstan-return T|null
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws TransactionRequiredException
     * @throws ORMException
     * @template T of object
     */
    #[Override]
    public function find(
        string $className,
        mixed $id,
        LockMode | int | null $lockMode = null,
        int | null $lockVersion = null,
    ): object | null {
        return $this->entityManager->find($className, $id, $lockMode, $lockVersion);
    }

    /**
     * Tells the EntityManagerPersistenceAccess to make an instance managed and persistent.
     *
     * The object will be entered into the database as a result of the flush
     * operation.
     *
     * NOTE: The persist operation always considers objects that are not yet
     * known to this EntityManagerPersistenceAccess as NEW. Do not pass detached objects to the
     * persisting operation.
     *
     * @param object $object The instance to make managed and persistent.
     * @throws ORMException
     */
    #[Override]
    public function persist(object $object): void
    {
        $this->entityManager->persist($object);
    }

    /**
     * Removes an object instance.
     *
     * A removed object will be removed from the database as a result of the
     * flush operation.
     *
     * @param object $object The object instance to remove.
     * @throws ORMException
     */
    #[Override]
    public function remove(object $object): void
    {
        $this->entityManager->remove($object);
    }

    /**
     * Clears the EntityManager. All objects that are currently managed by this
     * EntityManager become detached.
     */
    #[Override]
    public function clear(): void
    {
        $this->entityManager->clear();
    }

    /**
     * Detaches an object from the EntityManager, causing a managed object to
     * become detached.
     *
     * Unflushed changes made to the object, if any (including removal of the
     * object), will not be synchronized to the database.
     * Objects which previously referenced the detached object will continue to
     * reference it.
     *
     * @param object $object The object to detach
     */
    #[Override]
    public function detach(object $object): void
    {
        $this->entityManager->detach($object);
    }

    /**
     * Refreshes the persistent state of an object from the database,
     * overriding any local changes that have not yet been persisted.
     *
     * @param object $object The object to refresh
     * @param LockMode|int|null $lockMode One of the \Doctrine\DBAL\LockMode::* constants or NULL
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     * @throws TransactionRequiredException
     */
    #[Override]
    public function refresh(object $object, LockMode | int | null $lockMode = null): void
    {
        $this->entityManager->refresh($object, $lockMode);
    }

    /**
     * Acquires a lock on the given entity.
     *
     * @param object $entity The entity to lock
     * @param LockMode|int $lockMode One of the \Doctrine\DBAL\LockMode::* constants or NULL
     * @param DateTimeInterface|int|null $lockVersion The version of the entity to lock
     * @throws OptimisticLockException
     * @throws PessimisticLockException
     */
    #[Override]
    public function lock(
        object $entity,
        LockMode | int $lockMode,
        DateTimeInterface | int | null $lockVersion = null,
    ): void {
        $this->entityManager->lock($entity, $lockMode, $lockVersion);
    }

    /**
     * Checks if the object is part of the current UnitOfWork and therefore
     * managed.
     *
     * @param object $object The object to check
     * @return bool True if the object is managed, false otherwise
     */
    #[Override]
    public function contains(object $object): bool
    {
        return $this->entityManager->contains($object);
    }

    /**
     * Flushes all changes to objects that have been queued up to-now to the
     * database.
     *
     * This effectively synchronizes the in-memory state of managed objects
     * with the database.
     *
     * @throws ORMException
     */
    #[Override]
    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
