<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\QueryBuilder;

/**
 * Class PagingApplier
 *
 * This class is responsible for applying pagination to an EntityQueryBuilder.
 * It sets the maximum number of results and the first result offset based on the provided limit and offset.
 */
final class PagingApplier
{
    /**
     * Applies pagination to the given EntityQueryBuilder.
     *
     * @param EntityQueryBuilderInterface $queryBuilder The query builder to which pagination will be applied.
     * @param int|null $limit The maximum number of results to return, or null for no limit.
     * @param int|null $offset The offset from which to start returning results, or null for no offset.
     */
    public function apply(EntityQueryBuilderInterface $queryBuilder, ?int $limit, ?int $offset): void
    {
        if ($limit === null || $offset === null) {
            return;
        }

        $queryBuilder->setMaxResults($limit);
        $queryBuilder->setFirstResult($offset);
    }
}
