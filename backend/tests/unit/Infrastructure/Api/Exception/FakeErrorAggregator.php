<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorCollectionInterface;
use Override;

/**
 * Class FakeErrorAggregator.
 *
 * This class is a simple implementation of the ErrorAggregatorInterface for testing purposes.
 * It allows the aggregation of a single error with specified title, detail, and status.
 * It provides methods to add an error, retrieve the error collection, count errors,
 * clear errors, and get the response status code.
 */
final class FakeErrorAggregator implements ErrorAggregatorInterface
{
    /**
     * The title of the last added error.
     */
    private ?string $lastTitle = null;

    /**
     * The detail of the last added error.
     */
    private ?string $lastDetail = null;

    /**
     * The status of the last added error.
     */
    private ?string $lastStatus = null;

    /**
     * Adds an API error with the specified title, detail, and status.
     *
     * @param string $title The title of the error.
     * @param string|null $detail A detailed description of the error (optional).
     * @param string|null $status The HTTP status code associated with the error (optional).
     */
    #[Override]
    public function addApiError(string $title, ?string $detail = null, ?string $status = null): void
    {
        $this->lastTitle = $title;
        $this->lastDetail = $detail ?? '';
        $this->lastStatus = $status ?? '500';
    }

    /**
     * Retrieves the collection of aggregated errors.
     *
     * @return ErrorCollectionInterface<int, ApiErrorInterface> An instance of ErrorCollectionInterface containing the
     * last added error.
     */
    #[Override]
    public function getErrorCollection(): ErrorCollectionInterface
    {
        return new FakeErrorCollection(
            $this->lastTitle ?? 'Error',
            $this->lastDetail ?? '',
            $this->lastStatus ?? '500',
        );
    }

    /**
     * Counts the number of aggregated errors.
     *
     * @return int The count of errors (0 or 1).
     */
    #[Override]
    public function count(): int
    {
        return $this->lastStatus !== null
            ? 1
            : 0;
    }

    /**
     * Clears all aggregated errors.
     *
     * @return ErrorAggregatorInterface Returns the current instance for method chaining.
     */
    #[Override]
    public function clear(): ErrorAggregatorInterface
    {
        $this->lastStatus = null;
        $this->lastDetail = null;
        $this->lastTitle = null;

        return $this;
    }

    /**
     * Adds a query parameter related API error.
     *
     * @param SimpleError $error The error to add.
     * @param int $errorStatus The HTTP status code associated with the error.
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function addQueryApiError(SimpleError $error, int $errorStatus): void
    {
        // No implementation needed for this fake class.
    }

    /**
     * Adds a body related API error.
     *
     * @param SimpleError $error The error to add.
     * @param int $errorStatus The HTTP status code associated with the error.
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function addBodyApiError(SimpleError $error, int $errorStatus): void
    {
        // No implementation needed for this fake class.
    }

    /**
     * Retrieves the HTTP response status code based on the last added error.
     *
     * @return int The HTTP status code (default is 500 if no error has been added).
     */
    #[Override]
    public function getResponseStatusCode(): int
    {
        return (int) ($this->lastStatus ?? '500');
    }
}
