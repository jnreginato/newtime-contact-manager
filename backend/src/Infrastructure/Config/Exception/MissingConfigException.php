<?php

declare(strict_types=1);

namespace App\Infrastructure\Config\Exception;

use RuntimeException;

/**
 * MissingConfigException class.
 *
 * This exception is thrown when mandatory configuration values are missing.
 */
final class MissingConfigException extends RuntimeException
{
}
