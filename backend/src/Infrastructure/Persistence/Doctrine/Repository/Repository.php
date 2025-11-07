<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityRepository;
use App\Domain\Entity\EntityInterface;
use Override;

use function assert;

/**
 * Class Repository
 *
 * This abstract class serves as a base repository for managing entities.
 *
 * @extends EntityRepository<EntityInterface>
 */
abstract class Repository extends EntityRepository implements RepositoryInterface
{
    /**
     * Finds an entity by its primary key / identifier.
     *
     * This method retrieves an entity by its identifier, optionally applying
     * lock modes and versions for concurrency control.
     *
     * @param mixed $id The identifier of the entity to find.
     * @param LockMode|int|null $lockMode The lock mode to apply.
     * @param int|null $lockVersion The lock version to apply for optimistic locking.
     * @return EntityInterface|null The found entity or null if no entity is found.
     */
    #[Override]
    public function find(mixed $id, mixed $lockMode = null, ?int $lockVersion = null): ?EntityInterface
    {
        $entity = parent::find($id, $lockMode, $lockVersion);

        if ($entity === null) {
            return null;
        }

        assert($entity instanceof EntityInterface);

        return $entity;
    }

    /**
     * Finds a single entity by a set of criteria.
     *
     * This method retrieves a single entity based on the provided criteria and
     * optional ordering parameters.
     *
     * @param array<string, mixed> $criteria
     * @param array<string, string>|null $orderBy
     * @return EntityInterface|null The found entity or null if no entity matches the criteria.
     */
    #[Override]
    public function findOneBy(array $criteria, ?array $orderBy = null): ?EntityInterface
    {
        $entity = parent::findOneBy($criteria, $orderBy);

        if ($entity === null) {
            return null;
        }

        assert($entity instanceof EntityInterface);

        return $entity;
    }

    /**
     * Saves an entity to the repository.
     *
     * This method persists the provided entity and flushes the changes to the database.
     *
     * @param EntityInterface $entity The entity to save.
     * @return EntityInterface The saved entity.
     */
    #[Override]
    public function save(EntityInterface $entity): EntityInterface
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    /**
     * Removes an entity from the repository.
     *
     * This method deletes the provided entity from the database.
     *
     * @param EntityInterface $entity The entity to remove.
     */
    #[Override]
    public function delete(EntityInterface $entity): void
    {
        $this->getEntityManager()->remove($entity);
    }
}
