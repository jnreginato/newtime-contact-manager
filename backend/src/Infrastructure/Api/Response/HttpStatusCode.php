<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

/**
 * Enum HttpStatusCode
 *
 * Represents HTTP status codes used in API responses.
 * Each case corresponds to a specific HTTP status code.
 */
enum HttpStatusCode: int
{
    // Successful responses
    case Ok = 200;
    case Created = 201;
    case Accepted = 202;
    case NoContent = 204;

    // Client error responses
    case BadRequest = 400;
    case Unauthorized = 401;
    case Forbidden = 403;
    case NotFound = 404;
    case NotAcceptable = 406;
    case Conflict = 409;
    case UnsupportedMediaType = 415;
    case UnprocessableEntity = 422;

    // Server error responses
    case InternalServerError = 500;
}
