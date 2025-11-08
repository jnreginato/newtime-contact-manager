<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\QueryBuilder;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Class EntityQueryBuilder
 */
final class EntityQueryBuilder extends QueryBuilder implements EntityQueryBuilderInterface
{
    /**
     * Constructs a new EntityQueryBuilder instance.
     *
     * @param EntityManagerInterface $entityManager The entity manager to be used for the query.
     * @param string $entityName The name of the entity being queried.
     * @param string $mainAlias The main alias for the entity in the query.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        public readonly string $entityName,
        public readonly string $mainAlias,
    ) {
        parent::__construct($entityManager);
    }
}
