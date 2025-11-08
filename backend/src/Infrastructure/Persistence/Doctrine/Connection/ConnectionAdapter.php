<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Connection;

use Doctrine\DBAL\Connection;

/**
 * ConnectionAdapter class.
 *
 * This class serves as an adapter for the Doctrine DBAL Connection.
 * It provides a way to interact with the database connection.
 */
final readonly class ConnectionAdapter
{
    /**
     * Constructor for ConnectionAdapter.
     *
     * @param Connection $connection The Doctrine DBAL Connection instance.
     */
    public function __construct(public Connection $connection)
    {
    }
}
