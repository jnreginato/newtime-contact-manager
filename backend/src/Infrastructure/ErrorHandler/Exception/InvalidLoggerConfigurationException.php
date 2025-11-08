<?php

declare(strict_types=1);

namespace App\Infrastructure\ErrorHandler\Exception;

use InvalidArgumentException;

/**
 * Exception thrown when a logger configuration is invalid.
 *
 * Typical use cases:
 * - Logger configuration is invalid or missing.
 * - Options configuration is invalid or missing.
 */
final class InvalidLoggerConfigurationException extends InvalidArgumentException
{
}
