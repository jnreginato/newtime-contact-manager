<?php

declare(strict_types=1);

namespace App\Infrastructure\Log\Exception;

use InvalidArgumentException;

/**
 * Exception thrown when a logger factory receives invalid or missing
 * configuration values.
 *
 * Typical use cases:
 * - Level not being a valid Logger level
 * - Stream not being a string or a resource
 */
final class InvalidLoggerConfigurationException extends InvalidArgumentException
{
}
