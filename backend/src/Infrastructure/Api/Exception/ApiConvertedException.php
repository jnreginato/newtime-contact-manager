<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

/**
 * Class ApiConvertedException
 *
 * This exception is thrown when an API request is converted from a Throwable
 * to an ApiException. It extends from ApiException, which is a custom exception
 * for API-related errors.
 */
final class ApiConvertedException extends ApiException
{
}
