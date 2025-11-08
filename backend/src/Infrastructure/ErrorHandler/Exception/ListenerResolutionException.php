<?php

declare(strict_types=1);

namespace App\Infrastructure\ErrorHandler\Exception;

use DomainException;
use Throwable;

/**
 * Exception thrown when an error handler factory fails to resolve listeners.
 *
 * Typical use cases:
 * - Listener class not found
 * - Listener class does not implement the required interface
 * - Listener class cannot be instantiated (e.g., due to missing dependencies)
 */
final class ListenerResolutionException extends DomainException
{
    /**
     * @param string $message The error message
     * @param int $code The HTTP status code (default is 500)
     * @param Throwable|null $previous The previous exception (if any)
     */
    public function __construct(string $message = '', int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message ?: 'An error occurred while resolving error handler listeners.', $code, $previous);
    }
}
