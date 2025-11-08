<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\EntityManager;

use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Override;

/**
 * EntityManagerQueryAccess class.
 *
 * This trait provides methods for creating and managing queries using the
 * Doctrine QueryBuilder.
 */
trait EntityManagerQueryAccess
{
    /**
     * Creates a QueryBuilder instance.
     *
     * @return QueryBuilder The created QueryBuilder object
     */
    #[Override]
    public function createQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }

    /**
     * Creates a new Query object.
     *
     * @param string $dql The DQL string to create the query
     * @return Query The created Query object
     */
    #[Override]
    public function createQuery(string $dql = ''): Query
    {
        return $this->entityManager->createQuery($dql);
    }

    /**
     * Creates a native SQL query.
     *
     * @param string $sql The SQL string to create the native query
     * @param ResultSetMapping $rsm The ResultSetMapping object
     * @return NativeQuery The created NativeQuery object
     */
    #[Override]
    public function createNativeQuery(string $sql, ResultSetMapping $rsm): NativeQuery
    {
        return $this->entityManager->createNativeQuery($sql, $rsm);
    }

    /**
     * Gets an ExpressionBuilder used for object-oriented construction of query
     * expressions.
     *
     * Example:
     * <code>
     *     $qb = $em->createQueryBuilder();
     *     $expr = $em->getExpressionBuilder();
     *     $qb->select('u')->from('User', 'u')
     *         ->where($expr->orX($expr->eq('u.id', 1), $expr->eq('u.id', 2)));
     * </code>
     */
    #[Override]
    public function getExpressionBuilder(): Expr
    {
        return $this->entityManager->getExpressionBuilder();
    }
}
