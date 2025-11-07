<?php

declare(strict_types=1);

namespace App\Infrastructure\Config\Exception;

use RuntimeException;

/**
 * InvalidTypeConfigException class.
 *
 * This exception is thrown when a configuration value has an invalid type.
 */
final class InvalidTypeConfigException extends RuntimeException
{
}
