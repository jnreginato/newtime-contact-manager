<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use App\Infrastructure\Api\Request\ErrorCapture\ErrorCollectionInterface;
use Throwable;

/**
 * Interface ApiExceptionInterface
 *
 * This interface defines the structure for API exceptions.
 * It includes methods for managing errors associated with the exception,
 * as well as retrieving the HTTP status code.
 */
interface ApiExceptionInterface extends Throwable
{
    /**
     * Adds an error to the exception.
     */
    public function addError(ApiErrorInterface $error): void;

    /**
     * Adds multiple errors to the exception.
     *
     * @param iterable<ApiErrorInterface> $errors
     */
    public function addErrors(iterable $errors): void;

    /**
     * Retrieves the collection of errors associated with the exception.
     *
     * @return ErrorCollectionInterface<int, ApiErrorInterface> The collection of errors.
     */
    public function getErrors(): ErrorCollectionInterface;

    /**
     * Retrieves the HTTP status code associated with the exception.
     */
    public function getHttpCode(): int;
}
