<?php

declare(strict_types=1);

namespace App\Infrastructure\Config\Exception;

use RuntimeException;

/**
 * EmptyConfigException class.
 *
 * This exception is thrown when a configuration value is empty.
 */
final class EmptyConfigException extends RuntimeException
{
}
