<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

use App\Infrastructure\Api\Exception\ApiInvalidQueryException;
use App\Infrastructure\Api\Exception\ErrorCode;
use App\Infrastructure\Api\Exception\ErrorMessage;
use App\Infrastructure\Api\Exception\SimpleError;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use App\Infrastructure\Api\Response\HttpStatusCode;

/**
 * BaseParser is an abstract class that provides common functionality for parsing
 * query parameters in API requests.
 * It includes methods for splitting strings, validating parameters, and throwing
 * validation errors.
 *
 * @psalm-type QueryParameters = array{ page?: array{size?: int, number?: int}}
 */
abstract class BaseParser
{
    /**
     * BaseParser constructor.
     *
     * @param ErrorAggregatorInterface $errorAggregator An instance of ErrorAggregatorInterface
     * to handle errors during query parsing.
     */
    public function __construct(private readonly ErrorAggregatorInterface $errorAggregator)
    {
    }

    /**
     * Parses the query parameters and populates the necessary fields.
     *
     * This method is abstract and must be implemented by subclasses to handle
     * specific query parameter parsing logic.
     *
     * @param QueryParameters $parameters The parameters passed in the query.
     * @throws ApiInvalidQueryException If there are validation errors during parsing.
     */
    abstract public function parse(array $parameters): void;

    /**
     * Throws a validation error with the provided parameters.
     *
     * This method is used to add an error to the error aggregator and throw
     * an ApiInvalidQueryException with the collected errors.
     *
     * @param string $paramName The name of the parameter that caused the error.
     * @param mixed $invalidValue The value that was found to be invalid.
     * @param ErrorCode $code The error code associated with the validation error.
     * @param ErrorMessage $message The error message associated with the validation error.
     * @param list<mixed> $messageParameters Additional parameters for the error message.
     * @throws ApiInvalidQueryException The exception thrown when a validation error occurs.
     */
    protected function throwValidationError(
        string $paramName,
        mixed $invalidValue,
        ErrorCode $code,
        ErrorMessage $message,
        array $messageParameters = [],
    ): never {
        $this->errorAggregator->addQueryApiError(
            new SimpleError($paramName, $invalidValue, $code->value, $message->value, $messageParameters),
            HttpStatusCode::UnprocessableEntity->value,
        );

        throw new ApiInvalidQueryException(
            $this->errorAggregator->getErrorCollection(),
            $this->errorAggregator->getResponseStatusCode(),
        );
    }
}
