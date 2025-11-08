<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\EntityManager;

use Override;

/**
 * EntityManagerTransactionAccess trait.
 *
 * This trait provides methods for managing transactions in the Doctrine
 * EntityManager.
 * It includes methods for starting, committing, rolling back transactions, and
 * executing functions within a transaction.
 */
trait EntityManagerTransactionAccess
{
    /**
     * Executes a function in a transaction.
     *
     * The function gets passed this EntityManager instance as an (optional)
     * parameter.
     *
     * {@link flush} is invoked prior to transaction commit.
     *
     * If an exception occurs during execution of the function or flushing or
     * transaction commit, the transaction is rolled back, the EntityManager
     * closed, and the exception re-thrown.
     *
     * @param callable $func The function to execute in a transaction
     * @return mixed The result of the function execution
     */
    #[Override]
    public function wrapInTransaction(callable $func): mixed
    {
        return $this->entityManager->wrapInTransaction($func);
    }

    /**
     * Starts a transaction on the underlying database connection.
     */
    #[Override]
    public function beginTransaction(): void
    {
        $this->entityManager->beginTransaction();
    }

    /**
     * Commits a transaction on the underlying database connection.
     */
    #[Override]
    public function commit(): void
    {
        $this->entityManager->commit();
    }

    /**
     * Performs a rollback on the underlying database connection.
     */
    #[Override]
    public function rollback(): void
    {
        $this->entityManager->rollback();
    }
}
