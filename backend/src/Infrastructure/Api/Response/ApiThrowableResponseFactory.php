<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

use App\Infrastructure\Api\Exception\ApiErrorInterface;
use App\Infrastructure\Api\Exception\ApiExceptionInterface;
use App\Infrastructure\Api\Exception\ThrowableConverterInterface;
use JsonException;
use Override;
use Psr\Log\LoggerAwareTrait;
use stdClass;
use Throwable;

use function assert;

/**
 * Class JsonApiThrowableResponseFactory.
 *
 * This class is responsible for creating responses from thrown exceptions.
 * It implements the JsonApiThrowableResponseFactoryInterface and provides methods to
 * convert exceptions into JSON API compliant responses.
 *
 * @phpstan-type ErrorSource array{
 *      pointer?: string,
 *      parameter?: string,
 *  }
 * @phpstan-type EncodedErrorItem array{
 *     status?: non-falsy-string,
 *     code?: non-falsy-string,
 *     title?: non-falsy-string,
 *     detail?: non-falsy-string,
 *     source?: ErrorSource,
 * }
 * @phpstan-type EncodedError array{errors: list<EncodedErrorItem|stdClass>}
 */
final class ApiThrowableResponseFactory implements ApiThrowableResponseFactoryInterface
{
    use LoggerAwareTrait;

    /**
     * Constructor.
     *
     * Initializes the JsonApiThrowableResponseFactory with a ThrowableConverterInterface.
     */
    public function __construct(public readonly ThrowableConverterInterface $throwableConverter)
    {
    }

    /**
     * Creates a response for the given Throwable.
     *
     * This method handles the conversion of a Throwable into a JSON API response,
     * logging the error if necessary, and returning an appropriate response object.
     *
     * @param Throwable $throwable The Throwable to convert into a response.
     * @return ApiThrowableResponseInterface The created response object.
     * @throws JsonException If encoding errors occur, a JsonException will be thrown.
     */
    #[Override]
    public function createResponse(Throwable $throwable): ApiThrowableResponseInterface
    {
        $this->logError($throwable, 'Internal Server Error');

        // If the exception is not an ApiExceptionInterface, convert it to one.
        if (!$throwable instanceof ApiExceptionInterface) {
            $throwable = $this->throwableConverter->convert($throwable);
        }

        $content = $this->encodeErrors($throwable->getErrors());
        $httpCode = $throwable->getHttpCode();

        return $this->createThrowableJsonApiResponse($throwable, $content, $httpCode);
    }

    /**
     * Logs an error message if the logger is set.
     *
     * @param Throwable $throwable The Throwable to log.
     * @param string $message The message to log.
     */
    private function logError(Throwable $throwable, string $message): void
    {
        if ($this->logger === null) {
            return;
        }

        try {
            $this->logger->error($message, ['error' => $throwable]);
        } catch (Throwable) {
            // On error (e.g., no permission to write on disk or etc.) ignore.
        }
    }

    /**
     * Encodes the given iterable of errors into a JSON string.
     *
     * This method converts the errors into a JSON API compliant format.
     *
     * @param iterable<ApiErrorInterface> $errors The errors to encode.
     * @return string The JSON encoded errors.
     * @throws JsonException If encoding fails, a JsonException will be thrown.
     */
    public function encodeErrors(iterable $errors): string
    {
        $array = $this->encodeErrorsToArray($errors);

        return $this->encodeToJson($array);
    }

    /**
     * Encodes the given iterable of errors into an array format.
     *
     * This method converts the errors into a JSON API compliant array structure.
     *
     * @param iterable<ApiErrorInterface> $errors The errors to encode.
     * @return EncodedError The encoded errors as an array.
     */
    private function encodeErrorsToArray(iterable $errors): array
    {
        $data = ['errors' => []];

        foreach ($errors as $error) {
            assert($error instanceof ApiErrorInterface);
            $representation = array_filter([
                JsonApiKeyword::ErrorsStatus->value => $error->getStatus(),
                JsonApiKeyword::ErrorsCode->value => $error->getCode(),
                JsonApiKeyword::ErrorsTitle->value => $error->getTitle(),
                JsonApiKeyword::ErrorsDetail->value => $error->getDetail(),
                JsonApiKeyword::ErrorsSource->value => $error->getSource(),
            ]);

            // There is a special case when error representation is an empty array.
            // Due to further JSON transform, it must be an object otherwise it will be an empty array in JSON.
            $representation = $representation !== []
                ? $representation
                : new stdClass();

            $data['errors'][] = $representation;
        }

        return $data;
    }

    /**
     * Encodes the given document into a JSON string.
     *
     * This method is used to convert the document into a JSON format for API responses.
     *
     * @param EncodedError $document The document to encode.
     * @return string The JSON encoded document.
     * @throws JsonException If encoding fails, a JsonException will be thrown.
     */
    private function encodeToJson(array $document): string
    {
        try {
            // Use JSON_THROW_ON_ERROR to throw an exception on encoding errors.
            return json_encode($document, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (JsonException $e) {
            // If encoding fails, log the error and rethrow the exception.
            $this->logError($e, 'Failed to encode errors to JSON');

            throw $e;
        }
    }

    /**
     * Creates a JsonApiThrowableResponseInterface from the given Throwable and content.
     *
     * This method constructs a response object that includes the throwable and its content,
     * allowing for proper handling of exceptions in API responses.
     *
     * @param Throwable $throwable The Throwable to include in the response.
     * @param string $content The content of the response.
     * @param int $status The HTTP status code for the response.
     * @return ApiThrowableResponseInterface The created response object.
     */
    private function createThrowableJsonApiResponse(
        Throwable $throwable,
        string $content,
        int $status,
    ): ApiThrowableResponseInterface {
        return new ApiThrowableResponse($throwable, $content, $status);
    }
}
