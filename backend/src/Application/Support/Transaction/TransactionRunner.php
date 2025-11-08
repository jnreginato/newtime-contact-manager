<?php

declare(strict_types=1);

namespace App\Application\Support\Transaction;

use App\Application\Exception\ResourcePersistenceException;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Utility class to run operations within a transaction.
 */
final readonly class TransactionRunner
{
    /**
     * Constructor for TransactionRunner.
     *
     * @param TransactionManagerInterface $transactionManager The transaction manager to handle transactions.
     * @param LoggerInterface $logger The logger for logging errors and information.
     */
    public function __construct(
        private TransactionManagerInterface $transactionManager,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @template TReturn of mixed
     * @param callable():TReturn $func The function to execute within the transaction.
     * @return TReturn The result of the function execution.
     * @throws ResourcePersistenceException If an error occurs during the transaction.
     */
    public function run(callable $func): mixed
    {
        try {
            $result = $this->transactionManager->transactional($func);
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), compact('exception'));

            throw new ResourcePersistenceException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }

        return $result;
    }
}
