<?php

declare(strict_types=1);

namespace App\Infrastructure\ErrorHandler\Exception;

use InvalidArgumentException;

/**
 * Exception thrown when an error handler factory receives invalid or missing
 * configuration values.
 *
 * Typical use cases:
 * - Missing required option like 'listeners'
 * - Malformed values in the config array (e.g., listeners not being an array)
 */
final class ErrorHandlerConfigurationException extends InvalidArgumentException
{
}
