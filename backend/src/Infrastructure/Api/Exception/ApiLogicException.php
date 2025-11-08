<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use App\Infrastructure\Api\Response\HttpStatusCode;
use RuntimeException;
use Throwable;

/**
 * Class ApiLogicException
 *
 * Represents an error that occurs when there is a logic error in the API request.
 * This exception is thrown when the API request cannot be processed due to a logic error.
 */
final class ApiLogicException extends RuntimeException
{
    /**
     * Constructs a new ApiLogicException.
     *
     * This exception is thrown when there is a logic error in the API request.
     *
     * @param string $message The error message (default is 'Logic error in API request').
     * @param int $code The HTTP status code (default is 500 Internal Server Error).
     * @param Throwable|null $previous The previous throwable used for the exception chaining (default is null).
     */
    public function __construct(
        string $message = 'Logic error in API request',
        int $code = HttpStatusCode::InternalServerError->value,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
