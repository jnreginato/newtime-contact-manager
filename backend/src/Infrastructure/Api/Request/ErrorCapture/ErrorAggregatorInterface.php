<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\ErrorCapture;

use App\Infrastructure\Api\Exception\ApiErrorInterface;
use App\Infrastructure\Api\Exception\SimpleError;
use Countable;

/**
 * Interface ErrorAggregatorInterface
 *
 * This interface defines methods for aggregating API errors during request
 * validation.
 */
interface ErrorAggregatorInterface extends Countable
{
    /**
     * Clears all errors from the aggregator.
     */
    public function clear(): self;

    /**
     * Adds an API error to the error collection.
     *
     * This method allows you to add an error with a title, an optional detail
     * message, and an optional HTTP status code.
     *
     * @param string $title The title of the error.
     * @param string|null $detail Optional detailed message about the error.
     * @param string|null $status Optional HTTP status code associated with the error.
     */
    public function addApiError(string $title, ?string $detail = null, ?string $status = null): void;

    /**
     * Adds an API error (caused by malformed query parameters) to the error collection.
     *
     * This method allows you to add an error using a SimpleError object,
     * which encapsulates the error details.
     *
     * @param SimpleError $error The SimpleError object containing error details.
     */
    public function addQueryApiError(SimpleError $error, int $errorStatus): void;

    /**
     * Adds an API error (caused by malformed body parameters) to the error collection.
     *
     * This method allows you to add an error using a SimpleError object,
     * which encapsulates the error details for the body of the request.
     *
     * @param SimpleError $error The SimpleError object containing error details.
     * @param int $errorStatus The HTTP status code for this error (default is 422 Unprocessable Entity).
     */
    public function addBodyApiError(SimpleError $error, int $errorStatus): void;

    /**
     * Returns a collection of API errors.
     *
     * This method is used to retrieve the collection of errors that have been
     * aggregated during the request validation process.
     *
     * @return ErrorCollectionInterface<int, ApiErrorInterface> The collection of API errors.
     */
    public function getErrorCollection(): ErrorCollectionInterface;

    /**
     * Returns the response status code.
     *
     * This method is used to retrieve the HTTP status code that should be
     * returned in the API response.
     *
     * @return int The HTTP status code for the response.
     */
    public function getResponseStatusCode(): int;
}
