<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

/**
 * Enum ErrorMessages
 *
 * This enum defines a set of constant error messages used in the API.
 * These messages are used for validation and error handling throughout the API.
 */
enum ErrorMessage: string
{
    case InvalidValue = 'The value is invalid.';

    // Request body errors
    case RequestBodyInvalidJson = 'The request body must be a valid JSON object.';
    case RequestBodyMustBeJsonObject = 'The request body must be a JSON object with string keys.';
}
