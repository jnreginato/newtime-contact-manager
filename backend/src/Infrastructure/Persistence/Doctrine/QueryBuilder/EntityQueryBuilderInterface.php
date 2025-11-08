<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\QueryBuilder;

use Doctrine\ORM\QueryBuilder;

/**
 * Class EntityQueryBuilder
 *
 * This class extends the Doctrine QueryBuilder to provide a fluent interface
 * for building queries specifically for entities, allowing for filtering,
 * sorting, and relationship management.
 *
 * @SuppressWarnings(PHPMD)
 */
interface EntityQueryBuilderInterface
{
    /**
     * Sets the maximum number of results to retrieve (the "limit").
     *
     * @param int|null $maxResults The maximum number of results to retrieve.
     * @return QueryBuilder The instance of QueryBuilder for method chaining.
     */
    public function setMaxResults(?int $maxResults): QueryBuilder;

    /**
     * Sets the position of the first result to retrieve (the "offset").
     *
     * @param int|null $firstResult The position of the first result to retrieve.
     * @return QueryBuilder The instance of QueryBuilder for method chaining.
     */
    public function setFirstResult(?int $firstResult): QueryBuilder;

    /**
     * Adds a DISTINCT flag to this query.
     *
     * <code>
     *     $qb = $em->createQueryBuilder()
     *         ->select('u')
     *         ->distinct()
     *         ->from('User', 'u');
     * </code>
     *
     * @param bool $flag Whether to apply the DISTINCT flag or not.
     * @return QueryBuilder The instance of QueryBuilder for method chaining.
     */
    public function distinct(bool $flag = true): QueryBuilder;
}
