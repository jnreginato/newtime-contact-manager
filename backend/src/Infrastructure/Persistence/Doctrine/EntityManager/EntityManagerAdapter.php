<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\EntityManager;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Cache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\UnitOfWork;
use Override;

/**
 * EntityManagerAdapter class.
 *
 * This class serves as an adapter for the Doctrine EntityManager.
 * It provides a way to interact with the EntityManager instance and perform
 * database operations.
 *
 * @SuppressWarnings("PHPMD.ShortVariable")
 */
final readonly class EntityManagerAdapter implements EntityManagerAdapterInterface
{
    use EntityManagerHydrationAccess;
    use EntityManagerFilterAccess;
    use EntityManagerPersistenceAccess;
    use EntityManagerProxyAccess;
    use EntityManagerQueryAccess;
    use EntityManagerTransactionAccess;

    /**
     * Constructor for EntityManagerAdapter.
     *
     * @param EntityManager $entityManager The Doctrine EntityManager instance
     */
    public function __construct(public EntityManager $entityManager)
    {
    }

    /**
     * Gets the EntityManager instance.
     *
     * @return EntityManager The EntityManager instance
     */
    #[Override]
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * Returns the cache API for managing the second level cache regions or
     * NULL if the cache is not enabled.
     *
     * @return Cache|null The cache API or null if not enabled
     */
    #[Override]
    public function getCache(): ?Cache
    {
        return $this->entityManager->getCache();
    }

    /**
     * Gets the ClassMetadata descriptor for a class.
     *
     * @param string|class-string<T> $className The class name
     * @phpstan-return ($className is class-string<T> ? ClassMetadata<T> : ClassMetadata<object>) ClassMetadata instance
     * @template T of object
     */
    #[Override]
    public function getClassMetadata(string $className): ClassMetadata
    {
        return $this->entityManager->getClassMetadata($className);
    }

    /**
     * Gets the Configuration used by the EntityManager.
     *
     * @return Configuration The Configuration instance
     */
    #[Override]
    public function getConfiguration(): Configuration
    {
        return $this->entityManager->getConfiguration();
    }

    /**
     * Gets the database connection object used by the EntityManager.
     *
     * @return Connection The database connection object
     */
    #[Override]
    public function getConnection(): Connection
    {
        return $this->entityManager->getConnection();
    }

    /**
     * Gets the EventManager used by the EntityManager.
     *
     * @return EventManager The EventManager instance
     */
    #[Override]
    public function getEventManager(): EventManager
    {
        return $this->entityManager->getEventManager();
    }

    /**
     * Gets the ClassMetadataFactory used by the EntityManager.
     *
     * @return ClassMetadataFactory The ClassMetadataFactory instance
     * @phpstan-ignore-next-line
     */
    #[Override]
    public function getMetadataFactory(): ClassMetadataFactory
    {
        return $this->entityManager->getMetadataFactory();
    }

    /**
     * Gets a reference to an entity by its identified by the given type and
     * identifier without actually loading it if the entity is not yet loaded.
     *
     * @param class-string<T> $entityName The name of the entity type
     * @param mixed $id The entity identifier
     * @phpstan-return T|null The entity reference
     * @throws ORMException
     * @template T of object
     */
    #[Override]
    public function getReference(string $entityName, mixed $id): object | null
    {
        return $this->entityManager->getReference($entityName, $id);
    }

    /**
     * Gets the repository for an entity class.
     *
     * @param class-string<T> $className The name of the entity.
     * @return EntityRepository<T> The repository class.
     * @template T of object
     */
    #[Override]
    public function getRepository(string $className): EntityRepository
    {
        return $this->entityManager->getRepository($className);
    }

    /**
     * Gets the UnitOfWork used by the EntityManager.
     *
     * @return UnitOfWork The UnitOfWork instance
     */
    #[Override]
    public function getUnitOfWork(): UnitOfWork
    {
        return $this->entityManager->getUnitOfWork();
    }

    /**
     * Checks if the EntityManager is open or closed.
     *
     * @return bool True if the EntityManager is open, false otherwise
     */
    #[Override]
    public function isOpen(): bool
    {
        return $this->entityManager->isOpen();
    }

    /**
     * Closes the EntityManager.
     *
     * All entities that are currently managed by this EntityManager become
     * detached. The EntityManager may no longer be used after it is closed.
     */
    #[Override]
    public function close(): void
    {
        $this->entityManager->close();
    }
}
