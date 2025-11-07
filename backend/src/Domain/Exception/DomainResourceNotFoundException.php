<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Infrastructure\Api\Response\HttpStatusCode;
use RuntimeException;
use Throwable;

use function sprintf;

/**
 * Class DomainResourceNotFoundException
 *
 * This exception is thrown when a resource is not found in the system.
 * It extends RuntimeException and provides a specific error message indicating
 * that the resource with the given URL was not found.
 */
final class DomainResourceNotFoundException extends RuntimeException
{
    /**
     * Constructs a new DomainResourceNotFoundException.
     *
     * This exception is thrown when a requested resource is not found.
     *
     * @param int|string|null $value The ID of the resource that was not found.
     * @param Throwable|null $previous The previous throwable used for the exception chaining (default is null).
     */
    public function __construct(int | string | null $value, ?Throwable $previous = null)
    {
        parent::__construct(
            sprintf('The resource identified by <%s> was not found', $value),
            HttpStatusCode::NotFound->value,
            $previous,
        );
    }
}
