<?php

declare(strict_types=1);

namespace App\Application\Support\Transaction;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

use function assert;

/**
 * Factory for TransactionRunner instances.
 */
final readonly class TransactionRunnerFactory
{
    /**
     * Create a TransactionRunner instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return TransactionRunner The created TransactionRunner instance.
     * @throws ContainerExceptionInterface If there is an error retrieving a dependency from the container.
     * @throws NotFoundExceptionInterface If a required dependency is not found
     */
    public function __invoke(ContainerInterface $container): TransactionRunner
    {
        $transactionManager = $container->get(TransactionManagerInterface::class);
        assert($transactionManager instanceof TransactionManagerInterface);

        $logger = $container->get('log.file');
        assert($logger instanceof LoggerInterface);

        return new TransactionRunner($transactionManager, $logger);
    }
}
