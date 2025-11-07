<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\QueryBuilder;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EntityQueryBuilderFactory
 *
 * This factory class is responsible for creating an EntityQueryBuilder with the specified parameters.
 * It applies pagination, filtering, sorting, and relationship filters and sorts to the query builder.
 */
final readonly class EntityQueryBuilderFactory
{
    /**
     * Constructor for EntityQueryBuilderFactory.
     *
     * @param EntityManagerInterface $entityManager The entity manager to be used for creating the query builder.
     * @param PagingApplier $paging The paging applier to apply pagination settings.
     */
    public function __construct(private EntityManagerInterface $entityManager, private PagingApplier $paging)
    {
    }

    /**
     * Creates an EntityQueryBuilder with the specified parameters.
     *
     * @param class-string $entityClass The class name of the entity to query.
     * @param string $alias The alias for the entity in the query.
     * @param int|null $limit The maximum number of results to return.
     * @param int|null $offset The offset from which to start returning results.
     *
     * @return EntityQueryBuilder The configured EntityQueryBuilder instance.
     */
    public function create(
        string $entityClass,
        string $alias,
        ?int $limit = null,
        ?int $offset = null,
    ): EntityQueryBuilder {
        $queryBuilder = new EntityQueryBuilder($this->entityManager, $entityClass, $alias);

        $queryBuilder
            ->select($alias)
            ->from($entityClass, $alias);

        $this->paging->apply($queryBuilder, $limit, $offset);

        return $queryBuilder;
    }
}
