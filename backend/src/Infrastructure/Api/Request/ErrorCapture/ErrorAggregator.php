<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\ErrorCapture;

use App\Infrastructure\Api\Exception\ApiError;
use App\Infrastructure\Api\Exception\ApiErrorInterface;
use App\Infrastructure\Api\Exception\ErrorMessage;
use App\Infrastructure\Api\Exception\SimpleError;
use App\Infrastructure\Api\Response\HttpStatusCode;
use MessageFormatter;
use Override;

use function assert;

/**
 * ErrorAggregator is a class that aggregates errors and provides methods to
 * add and retrieve them.
 *
 * It allows adding API errors, query parameter errors, and body errors,
 * as well as managing error statuses.
 */
final class ErrorAggregator implements ErrorAggregatorInterface
{
    /**
     * An instance of ErrorCollectionInterface to hold errors.
     *
     * This collection is used to store all errors added to the aggregator.
     *
     * @var ErrorCollectionInterface<int, ApiErrorInterface>
     */
    private ErrorCollectionInterface $errorCollection;

    /**
     * The HTTP status code for the first error added.
     * This will be used as the response status unless overridden by subsequent errors.
     */
    private ?int $responseStatusCode = null;

    /**
     * Constructor for the ErrorAggregator class.
     *
     * @param ErrorCollectionInterface<int, ApiErrorInterface>|null $errorCollection An optional error collection.
     * If not provided, a new ErrorCollection instance will be created.
     */
    public function __construct(?ErrorCollectionInterface $errorCollection = null)
    {
        $this->errorCollection = $errorCollection ?? new ErrorCollection();
    }

    /**
     * Clears the error aggregator, resetting the response status code and
     * clearing the error collection.
     *
     * This method is useful for reusing the aggregator in different contexts
     * without retaining previous errors.
     *
     * @return self Returns the current instance for method chaining.
     */
    #[Override]
    public function clear(): self
    {
        $this->responseStatusCode = null;
        $this->errorCollection->clear();

        return $this;
    }

    /**
     * Returns the number of errors in the error collection.
     *
     * This method implements the Countable interface, allowing the use of
     * `count($errorAggregator)` to get the number of errors.
     *
     * @return int The number of errors in the error collection.
     */
    #[Override]
    public function count(): int
    {
        return $this->errorCollection->count();
    }

    /**
     * Adds an API error with a title, detail, and optional status.
     *
     * @param string $title The title of the error.
     * @param string|null $detail The detail message of the error.
     * @param string|null $status The HTTP status code associated with the error.
     */
    #[Override]
    public function addApiError(string $title, ?string $detail = null, ?string $status = null): void
    {
        $this->errorCollection->add(new ApiError((string) $status, null, $title, $detail));
        $this->applyResponseStatusCode(HttpStatusCode::BadRequest->value);
    }

    /**
     * Adds an error caused by malformed query parameters to the error collection.
     *
     * This method adds an error to the query parameters of the API response,
     * using the provided SimpleError object. It sets the parameter name as the
     * source of the error.
     *
     * @param SimpleError $error The error to add to the query parameters.
     * @param int $errorStatus The HTTP status code for this error (default is 422 Unprocessable Entity).
     */
    #[Override]
    public function addQueryApiError(
        SimpleError $error,
        int $errorStatus = HttpStatusCode::UnprocessableEntity->value,
    ): void {
        $this->errorCollection->add(
            new ApiError(
                (string) $errorStatus,
                $error->internalErrorCode,
                ErrorMessage::InvalidValue->value,
                $this->formatMessage($error),
                [ApiErrorInterface::SOURCE_PARAMETER => $error->parameterName ?? 'unknown'],
            ),
        );

        $this->applyResponseStatusCode($errorStatus);
    }

    /**
     * Adds an error caused by invalid body attributes to the error collection.
     *
     * This method adds an error to the body of the API response, using the
     * provided SimpleError object. It sets the pointer to the specific attribute
     * or data field that caused the error.
     *
     * @param SimpleError $error The error to add to the body.
     * @param int $errorStatus The HTTP status code for this error (default is 422 Unprocessable Entity).
     */
    #[Override]
    public function addBodyApiError(SimpleError $error, int $errorStatus): void
    {
        $pointer = $error->parameterName !== null
            ? "$error->parameterName"
            : '/data';

        $this->errorCollection->add(
            new ApiError(
                (string) $errorStatus,
                $error->internalErrorCode,
                ErrorMessage::InvalidValue->value,
                $this->formatMessage($error),
                [ApiErrorInterface::SOURCE_POINTER => $pointer],
            ),
        );

        $this->applyResponseStatusCode($errorStatus);
    }

    /**
     * Returns the collection of errors aggregated by this ErrorAggregator.
     *
     * This method provides access to the ErrorCollectionInterface instance
     * that holds all the errors added to this aggregator.
     *
     * @return ErrorCollectionInterface<int, ApiErrorInterface> The collection of API errors.
     */
    #[Override]
    public function getErrorCollection(): ErrorCollectionInterface
    {
        return $this->errorCollection;
    }

    /**
     * Returns the response status code based on the first error added.
     *
     * This method retrieves the HTTP status code that should be returned in the API response.
     * It asserts that the response status code has been set before returning it.
     *
     * @return int The HTTP status code for the response.
     */
    #[Override]
    public function getResponseStatusCode(): int
    {
        if ($this->responseStatusCode === null) {
            // If no errors were added, we default to 400 Bad Request.
            $this->responseStatusCode = HttpStatusCode::BadRequest->value;
        }

        return $this->responseStatusCode;
    }

    /**
     * Applies the response status code based on the first error added.
     *
     * This method sets the response status code based on the first error's status.
     * If subsequent errors have different status codes, it defaults to 400 Bad Request.
     *
     * @param int $status The HTTP status code to apply.
     */
    private function applyResponseStatusCode(int $status): void
    {
        // Currently (at the moment of writing) the spec is vague about how error status should be set.
        // On the one side it says, for example, 'A server MUST return 409 Conflict when processing a POST
        // request to create a resource with a client-generated ID that already exists.'
        // So you might think 'simple, that should be HTTP status, right?'
        // But on the other
        // - 'it [server] MAY continue processing and encounter multiple problems.'
        // - 'When a server encounters multiple problems for a single request, the most generally applicable
        //    HTTP error code SHOULD be used in the response. For instance, 400 Bad Request might be appropriate
        //    for multiple 4xx errors.'

        // So, as we might return multiple errors, we have to figure out what is the best status for response.

        // The strategy is the following: for the first error its error code becomes the Response's status.
        // If any following error code does not match the previous, the status becomes generic 400.
        if ($this->responseStatusCode === null) {
            $this->responseStatusCode = $status;

            return;
        }

        $this->responseStatusCode = HttpStatusCode::BadRequest->value;
    }

    /**
     * Formats the error message using the MessageFormatter.
     *
     * This method uses the MessageFormatter to format the error message
     * based on the message template and parameters provided in the SimpleError.
     *
     * @param SimpleError $error The error to format.
     */
    private function formatMessage(SimpleError $error): string
    {
        $formatter = MessageFormatter::create('en', $error->messageTemplate);
        assert($formatter !== null, 'MessageFormatter should be created successfully');

        $formattedMessage = $formatter->format($error->messageParameters);
        assert($formattedMessage !== false, $formatter->getErrorMessage());

        return $formattedMessage;
    }
}
