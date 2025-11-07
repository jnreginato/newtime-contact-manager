<?php

declare(strict_types=1);

namespace App\Application\Exception;

use RuntimeException;
use Throwable;

/**
 * Class ResourcePersistenceException
 *
 * Represents a failure during a persistence operation (e.g., insert, update, delete, transaction).
 * This exception is meant to abstract lower-level errors from infrastructure.
 */
final class ResourcePersistenceException extends RuntimeException
{
    /**
     * Constructs a new ResourcePersistenceException.
     *
     * @param string $message The error message.
     * @param int $code The error code (default is 0).
     * @param Throwable|null $previous The previous throwable used for the exception chaining (default is null).
     */
    public function __construct(
        string $message = 'An error occurred while saving data.',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
