<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

use App\Infrastructure\Api\Exception\ApiInvalidBodyException;
use App\Infrastructure\Api\Exception\ErrorMessage;
use App\Infrastructure\Api\Request\DataCapture\DataCollectionInterface;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use Override;

use function array_any;
use function array_is_list;
use function array_keys;
use function is_numeric;
use function is_string;

/**
 * Class BodyParser
 *
 * This class is responsible for parsing the body of an API request.
 * It decodes the JSON body, validates the data, and stores it in a data collection.
 * It also handles errors related to invalid JSON data.
 */
final class BodyParser implements BodyParserInterface
{
    /**
     * The data collection where parsed data will be stored.
     *
     * @var array<string, mixed>
     */
    private array $bodyData = [];

    /**
     * Constructor for the BodyParser class.
     *
     * @param DataCollectionInterface $dataCollection The data collection to store parsed data.
     * @param ErrorAggregatorInterface $errorAggregator The error aggregator to handle errors during parsing.
     */
    public function __construct(
        private readonly DataCollectionInterface $dataCollection,
        private readonly ErrorAggregatorInterface $errorAggregator,
    ) {
    }

    /**
     * Parses the request body and extracts attributes.
     *
     * This method decodes the JSON request body, validates it, and stores the attributes
     * in the data collection. If the body is invalid or empty, it throws an ApiInvalidBodyException.
     *
     * @param array<string, mixed> $requestBody The raw request body to be parsed
     * @throws ApiInvalidBodyException If the request body is not a valid JSON or contains invalid data
     */
    #[Override]
    public function parse(array $requestBody): void
    {
        $this->clear();
        $this->setBodyData($requestBody);
        $this->parseAttributes();
    }

    /**
     * Retrieves the parsed data from the request body.
     *
     * @return array<string, mixed> The parsed data
     */
    #[Override]
    public function getData(): array
    {
        return $this->dataCollection->get();
    }

    /**
     * Clears the internal state of the parser, including body data and error aggregator.
     *
     * This method is called to reset the parser's state before processing a new request.
     */
    private function clear(): void
    {
        $this->bodyData = [];
        $this->dataCollection->clear();
        $this->errorAggregator->clear();
    }

    /**
     * Sets the body data from the request body.
     *
     * This method decodes the JSON request body and stores it in the bodyData property.
     * If the body data is empty or invalid, it adds an error to the error aggregator and throws an exception.
     *
     * @param array<string, mixed> $requestBody The raw request body to be parsed
     * @throws ApiInvalidBodyException If the request body is not a valid JSON
     */
    private function setBodyData(array $requestBody): void
    {
        if ($requestBody === []) {
            $this->bodyData = $requestBody;

            return;
        }

        $this->assertJsonObject($requestBody);
        $this->bodyData = $requestBody;
    }

    /**
     * Asserts that the provided data is a JSON object.
     *
     * This method checks if the provided data is an associative array (JSON object).
     * If the data is a list or has numeric string keys, it adds an error to the
     * error aggregator and throws an ApiInvalidBodyException.
     *
     * @param array<string, mixed> $data The data to be checked
     * @throws ApiInvalidBodyException If the data is not a valid JSON object
     */
    private function assertJsonObject(array $data): void
    {
        if (!array_is_list($data) && !$this->hasNumericStringKeys($data)) {
            return;
        }

        $this->errorAggregator->addApiError(ErrorMessage::RequestBodyMustBeJsonObject->value);
        $this->throwApiError();
    }

    /**
     * Checks if the array has any string keys that are numeric.
     *
     * @param array<string, mixed> $data The array to check
     * @return bool True if the array has numeric string keys, false otherwise
     */
    private function hasNumericStringKeys(array $data): bool
    {
        $keys = array_keys($data);

        // @phpstan-ignore-next-line
        return array_any($keys, static fn ($key) => is_string($key) && is_numeric($key));
    }

    /**
     * Parses the attributes from the body data according to the defined rules.
     *
     * This method checks for required attributes, validates attribute names,
     * and adds any errors to the error aggregator if validation fails.
     *
     * @throws ApiInvalidBodyException If there are validation errors in the request body
     */
    private function parseAttributes(): void
    {
        foreach ($this->bodyData as $name => $value) {
            $this->dataCollection->remember($name, $value);
        }
    }

    /**
     * Throws an ApiInvalidBodyException with the collected errors.
     *
     * This method is called when there are validation errors in the request body.
     * It throws an exception that contains the error collection and the response status code.
     *
     * @throws ApiInvalidBodyException
     */
    private function throwApiError(): never
    {
        throw new ApiInvalidBodyException(
            $this->errorAggregator->getErrorCollection(),
            $this->errorAggregator->getResponseStatusCode(),
        );
    }
}
