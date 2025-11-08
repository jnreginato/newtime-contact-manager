<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use App\Infrastructure\Api\Request\ErrorCapture\ErrorCollectionInterface;
use App\Infrastructure\Api\Response\HttpStatusCode;
use Override;
use RuntimeException;
use Throwable;

/**
 * Class ApiException.
 *
 * Represents an exception that encapsulates API errors and an associated HTTP
 * status code. This class implements the ApiExceptionInterface, allowing for
 * structured error handling in API responses.
 */
abstract class ApiException extends RuntimeException implements ApiExceptionInterface
{
    /**
     * The collection of API errors associated with this exception.
     *
     * @var ErrorCollectionInterface<int, ApiErrorInterface>
     */
    private ErrorCollectionInterface $errors;

    /**
     * The HTTP status code associated with this exception.
     */
    private int $httpCode;

    /**
     * Constructs a new ApiException.
     *
     * @param ErrorCollectionInterface<int, ApiErrorInterface> $errors The error(s) to associate with the exception.
     * @param int $httpCode The HTTP status code associated with the exception.
     * @param Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct(
        ErrorCollectionInterface $errors,
        int $httpCode = HttpStatusCode::BadRequest->value,
        ?Throwable $previous = null,
    ) {
        if ($httpCode < 100 || $httpCode > 599) {
            $httpCode = HttpStatusCode::BadRequest->value;
        }

        parent::__construct('API error', $httpCode, $previous);

        $this->errors = clone $errors;
        $this->httpCode = $httpCode;
    }

    /**
     * Adds a single error to the exception.
     *
     * @param ApiErrorInterface $error The error to add.
     */
    #[Override]
    public function addError(ApiErrorInterface $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * Adds multiple errors to the exception.
     *
     * @param iterable<ApiErrorInterface> $errors The errors to add.
     */
    #[Override]
    public function addErrors(iterable $errors): void
    {
        foreach ($errors as $error) {
            $this->addError($error);
        }
    }

    /**
     * Retrieves the collection of errors associated with the exception.
     *
     * @return ErrorCollectionInterface<int, ApiErrorInterface> The collection of errors.
     */
    #[Override]
    public function getErrors(): ErrorCollectionInterface
    {
        return $this->errors;
    }

    /**
     * Retrieves the HTTP status code associated with the exception.
     *
     * @return int The HTTP status code.
     */
    #[Override]
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
