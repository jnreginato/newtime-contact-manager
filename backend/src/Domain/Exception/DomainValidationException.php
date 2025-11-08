<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use DomainException;

/**
 * Class DomainValidationException
 *
 * This exception is thrown when a domain validation error occurs.
 * It extends the DomainException class to provide a specific type of exception
 * for validation errors within the domain layer.
 */
final class DomainValidationException extends DomainException
{
}
