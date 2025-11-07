<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use App\Domain\Exception\DomainDuplicatedResourceException;
use App\Domain\Exception\DomainResourceNotFoundException;
use App\Domain\Exception\DomainValidationException;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorCollectionInterface;
use App\Infrastructure\Api\Response\HttpStatusCode;
use InvalidArgumentException;
use Override;
use Throwable;

/**
 * ThrowableConverter class.
 *
 * This class implements the ThrowableConverterInterface and provides a method
 * to convert a Throwable into an ApiExceptionInterface instance.
 * It is designed to handle various types of exceptions, including
 * application-specific, authorization, and third-party exceptions, and convert
 * them into a standardized API error format.
 */
final readonly class ThrowableConverter implements ThrowableConverterInterface
{
    /**
     * Constructor for the ThrowableConverter class.
     *
     * @param ErrorAggregatorInterface $errorAggregator An instance of ErrorAggregatorInterface to aggregate errors.
     */
    public function __construct(private ErrorAggregatorInterface $errorAggregator)
    {
    }

    /**
     * Converts a Throwable into an ApiExceptionInterface instance.
     *
     * This code provides an ability to transform various exceptions in API
     * (application specific, authorization, 3rd party, etc.) and convert it to
     * API error.
     *
     * @param Throwable $throwable The throwable to convert.
     * @return ApiExceptionInterface Returns an ApiExceptionInterface instance.
     */
    #[Override]
    public function convert(Throwable $throwable): ApiExceptionInterface
    {
        return match (true) {
            $throwable instanceof AuthenticationExceptionInterface => $this->unauthorized($throwable),
            $throwable instanceof AuthorizationExceptionInterface => $this->forbidden($throwable),
            $throwable instanceof DomainResourceNotFoundException => $this->notFound($throwable),
            $throwable instanceof DomainDuplicatedResourceException => $this->conflicted($throwable),
            $throwable instanceof DomainValidationException,
            $throwable instanceof InvalidArgumentException => $this->unprocessable($throwable),
            $throwable instanceof ApiExceptionInterface => $throwable,
            default => $this->others($throwable),
        };
    }

    /**
     * Creates an ErrorCollectionInterface with a single error.
     *
     * @param string $detail The detail message for the error.
     * @param int $httpCode The HTTP status code associated with the error.
     * @return ErrorCollectionInterface<int, ApiErrorInterface> Returns an instance of ErrorCollectionInterface.
     */
    private function createErrorWith(string $detail, int $httpCode): ErrorCollectionInterface
    {
        $httpCode = $this->normalizeHttpCode($httpCode);
        $this->errorAggregator->addApiError('Error', $detail, (string) $httpCode);

        return $this->errorAggregator->getErrorCollection();
    }

    /**
     * Normalizes the HTTP status code to ensure it is within the valid range.
     *
     * @param int $code The HTTP status code to normalize.
     * @return int Returns the normalized HTTP status code.
     */
    private function normalizeHttpCode(int $code): int
    {
        return $code >= 100 && $code <= 599
            ? $code
            : HttpStatusCode::InternalServerError->value;
    }

    /**
     * Creates an ApiConvertedException for unauthorized access.
     *
     * @param AuthenticationExceptionInterface $throwable The throwable that caused the unauthorized access.
     * @return ApiConvertedException Returns an ApiConvertedException with a 401 Unauthorized status code.
     */
    private function unauthorized(AuthenticationExceptionInterface $throwable): ApiConvertedException
    {
        $httpCode = HttpStatusCode::Unauthorized->value;
        $errors = $this->createErrorWith('Authentication failed', $httpCode);

        return new ApiConvertedException($errors, $httpCode, $throwable);
    }

    /**
     * Converts an AuthorizationExceptionInterface into an ApiConvertedException.
     *
     * @param AuthorizationExceptionInterface $throwable The throwable to convert.
     * @return ApiConvertedException Returns an ApiConvertedException with a 403 Forbidden status code.
     */
    private function forbidden(AuthorizationExceptionInterface $throwable): ApiConvertedException
    {
        $httpCode = HttpStatusCode::Forbidden->value;
        $action = $throwable->getAction();
        $errors = $this->createErrorWith("You are not authorized for action `$action`.", $httpCode);

        return new ApiConvertedException($errors, $httpCode, $throwable);
    }

    /**
     * Converts an DomainResourceNotFoundException into an ApiConvertedException.
     *
     * @param DomainResourceNotFoundException $throwable The throwable to convert.
     * @return ApiConvertedException Returns an ApiConvertedException with a 404 Not Found status code.
     */
    private function notFound(DomainResourceNotFoundException $throwable): ApiConvertedException
    {
        $httpCode = HttpStatusCode::NotFound->value;
        $errors = $this->createErrorWith('Resource not found', $httpCode);

        return new ApiConvertedException($errors, $httpCode, $throwable);
    }

    /**
     * Converts a DomainDuplicatedResourceException into an ApiConvertedException.
     *
     * @param DomainDuplicatedResourceException $throwable The throwable to convert.
     * @return ApiConvertedException Returns an ApiConvertedException with a 409 Conflict status code.
     */
    private function conflicted(DomainDuplicatedResourceException $throwable): ApiConvertedException
    {
        $httpCode = HttpStatusCode::Conflict->value;
        $errors = $this->createErrorWith('Resource already exists', $httpCode);

        return new ApiConvertedException($errors, $httpCode, $throwable);
    }

    /**
     * Converts an InvalidArgumentException into an ApiConvertedException.
     *
     * @param InvalidArgumentException|DomainValidationException $throwable The throwable to convert.
     * @return ApiConvertedException Returns an ApiConvertedException with a 422 Unprocessable Entity status code.
     */
    private function unprocessable(
        InvalidArgumentException | DomainValidationException $throwable,
    ): ApiConvertedException {
        $httpCode = HttpStatusCode::UnprocessableEntity->value;
        $errors = $this->createErrorWith('Unprocessable Entity', $httpCode);

        return new ApiConvertedException($errors, $httpCode, $throwable);
    }

    /**
     * Converts any other Throwable into an ApiConvertedException.
     *
     * @param Throwable $throwable The throwable to convert.
     * @return ApiConvertedException Returns an ApiConvertedException with a normalized HTTP status code.
     */
    private function others(Throwable $throwable): ApiConvertedException
    {
        $rawCode = (int) $throwable->getCode();
        $httpCode = $this->normalizeHttpCode($rawCode);
        $errors = $this->createErrorWith($throwable->getMessage(), $httpCode);

        return new ApiConvertedException($errors, $httpCode, $throwable);
    }
}
