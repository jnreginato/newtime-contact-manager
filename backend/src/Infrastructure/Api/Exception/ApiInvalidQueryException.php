<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

/**
 * Class ApiInvalidQueryException
 *
 * This exception is thrown when an API request contains an invalid query.
 * It extends from ApiException, which is a custom exception for API-related errors.
 */
final class ApiInvalidQueryException extends ApiException
{
}
