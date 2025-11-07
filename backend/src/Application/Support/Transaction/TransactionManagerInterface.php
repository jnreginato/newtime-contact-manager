<?php

declare(strict_types=1);

namespace App\Application\Support\Transaction;

/**
 * TransactionManagerInterface defines the contract for transaction management.
 *
 * This interface provides a method to execute a function within a transaction context,
 * ensuring that the transaction is committed if the function completes successfully,
 * or rolled back in case of an exception.
 */
interface TransactionManagerInterface
{
    /**
     * Executes a function within a transaction.
     *
     * This method ensures that the provided function is executed within a transaction context.
     * If the function completes successfully, the transaction is committed. If an exception
     * occurs, the transaction is rolled back.
     *
     * @param callable $func The function to execute within the transaction.
     * @return mixed The result of the function execution.
     */
    public function transactional(callable $func): mixed;

    /**
     * Begins a transaction for the repository.
     *
     * This method should be called before performing any operations that
     * require atomicity, such as creating, updating, or deleting entities.
     */
    public function beginTransaction(): void;

    /**
     * Commits the current transaction for the repository.
     *
     * This method should be called after successfully performing operations
     * started in a transaction.
     */
    public function commit(): void;

    /**
     * Rolls back the current transaction for the repository.
     *
     * This method should be called if an error occurs during operations
     * started in a transaction, to revert any changes made during that transaction.
     */
    public function rollback(): void;
}
