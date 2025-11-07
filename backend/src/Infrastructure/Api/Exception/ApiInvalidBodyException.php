<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

/**
 * Class ApiInvalidBodyException
 *
 * This exception is thrown when the body of an API request is invalid.
 * It extends from ApiException, which is a custom exception for API-related errors.
 */
final class ApiInvalidBodyException extends ApiException
{
}
