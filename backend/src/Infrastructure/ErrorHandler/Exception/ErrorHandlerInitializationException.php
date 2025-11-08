<?php

declare(strict_types=1);

namespace App\Infrastructure\ErrorHandler\Exception;

use DomainException;
use Throwable;

/**
 * Exception thrown when an error handler factory fails to initialize the error
 * handler.
 *
 * Typical use cases:
 * - Error handler class not found
 * - Error handler class does not implement the required interface
 * - Error handler class cannot be instantiated (e.g., missing dependencies)
 */
final class ErrorHandlerInitializationException extends DomainException
{
    /**
     * The constructor for the ErrorHandlerInitializationException class.
     *
     * @param string $message The error message
     * @param int $code The HTTP status code (default is 500)
     * @param Throwable|null $previous The previous exception (if any)
     */
    public function __construct(string $message = '', int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message ?: 'An error occurred while initializing the ErrorHandler.', $code, $previous);
    }
}
