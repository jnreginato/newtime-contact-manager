<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

/**
 * Enum ErrorCodes
 *
 * This enum defines a set of error codes used throughout the API.
 * These codes are used to identify specific errors that can occur during API operations.
 */
enum ErrorCode: string
{
    case InvalidValue = '0001';

    // Page query parameters - 1xxx
    case PageParameterNotArray = '1001';
    case PageParameterKeyNotAllowed = '1002';
    case PageParameterValueMustBeNumeric = '1003';
}
