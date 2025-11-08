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

    // Page query parameters
    case PageParameterNotArray = 'The `page` query parameter must be an object with the following keys: [{0}].';
    case PageParameterKeyNotAllowed = 'Unknown key `{0}` in the `page` query parameter. Allowed keys are [{1}].';
    case PageParameterValueMustBeNumeric = 'The value in the `page` query parameter must be numeric.';

    // Request body errors
    case RequestBodyInvalidJson = 'The request body must be a valid JSON object.';
    case RequestBodyMustBeJsonObject = 'The request body must be a JSON object with string keys.';

    // Mapping error
    case RepositoryIsNotSet = 'Repository is not set.';
}
