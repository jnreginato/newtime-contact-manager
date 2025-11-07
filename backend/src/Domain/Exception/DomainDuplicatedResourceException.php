<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Infrastructure\Api\Response\HttpStatusCode;
use RuntimeException;
use Throwable;

use function sprintf;

/**
 * Class DomainDuplicatedResourceException
 *
 * Represents an error that occurs when trying to save a resource with a unique
 * key that already exists.
 */
final class DomainDuplicatedResourceException extends RuntimeException
{
    /**
     * Constructs a new DomainDuplicatedResourceException.
     *
     * This exception is thrown when a resource with the specified unique key
     * already exists.
     *
     * @param string $value The unique key of the duplicated resource.
     * @param int $code The HTTP status code (default is 409 Conflict).
     * @param Throwable|null $previous The previous throwable used for the exception chaining (default is null).
     */
    public function __construct(
        string $value,
        int $code = HttpStatusCode::Conflict->value,
        ?Throwable $previous = null,
    ) {
        parent::__construct(sprintf('The resource with unique-key <%s> is duplicated', $value), $code, $previous);
    }
}
