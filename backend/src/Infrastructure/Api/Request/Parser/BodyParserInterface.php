<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

/**
 * Interface BodyParserInterface
 *
 * This interface defines the methods required for parsing and validating the
 * request body in API requests.
 */
interface BodyParserInterface
{
    /**
     * Parses the request body and extracts data from it.
     *
     * This method takes the raw request body as a string, decodes it,
     * validates the data, and stores the attributes in a data collection.
     *
     * @param array<string, mixed> $requestBody The raw request body to be parsed.
     */
    public function parse(array $requestBody): void;

    /**
     * Retrieves the parsed data from the request body.
     *
     * This method returns an associative array containing the parsed data
     * extracted from the request body.
     *
     * @return array<array-key, mixed> The parsed data from the request body.
     */
    public function getData(): array;
}
