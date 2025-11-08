<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Transaction;

use App\Application\Support\Transaction\TransactionManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Override;

/**
 * TransactionManager class.
 *
 * This class implements the TransactionManagerInterface and provides methods
 * for managing transactions in a Doctrine EntityManager.
 * It allows for transactional operations, including beginning, committing,
 * and rolling back transactions.
 */
final readonly class TransactionManager implements TransactionManagerInterface
{
    /**
     * Constructor for TransactionManager.
     *
     * This constructor initializes the transaction manager with an EntityManagerInterface,
     * which is used to manage transactions in the database.
     *
     * @param EntityManagerInterface $entityManager The EntityManager instance used for transaction management.
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Executes a function within a transaction.
     *
     * This method ensures that the provided function is executed within a
     * transaction context.
     * If the function completes successfully, the transaction is committed.
     * If an exception occurs, the transaction is rolled back.
     *
     * @template TReturn of mixed
     * @param callable():TReturn $func The function to execute within the transaction.
     * @return TReturn The result of the function execution.
     */
    #[Override]
    public function transactional(callable $func): mixed
    {
        return $this->entityManager->wrapInTransaction($func);
    }

    /**
     * Begins a transaction for the repository.
     *
     * This method starts a new transaction, allowing multiple operations
     * to be executed atomically.
     */
    #[Override]
    public function beginTransaction(): void
    {
        $this->entityManager->beginTransaction();
    }

    /**
     * Commits the current transaction.
     *
     * This method commits all changes made in the current transaction to the database.
     */
    #[Override]
    public function commit(): void
    {
        $this->entityManager->commit();
    }

    /**
     * Rolls back the current transaction.
     *
     * This method undoes all changes made in the current transaction, reverting
     * the database to its state before the transaction began.
     */
    #[Override]
    public function rollback(): void
    {
        $this->entityManager->rollback();
    }
}
