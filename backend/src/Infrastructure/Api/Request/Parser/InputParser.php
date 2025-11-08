<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

use Override;

use function array_merge;

/**
 * InputParser is responsible for parsing the query parameters and body of an
 * API request.
 *
 * It combines the functionality of QueryParser and BodyParser to provide a
 * unified interface for extracting data from both sources.
 *
 * @phpstan-type QueryParameters array{page?: array{size?: int, number?: int}}
 * @phpstan-type RequestBody array<string, mixed>
 * @phpstan-type QueryData array{resourceId: mixed, pageSize: int, pageNumber: int}
 * @phpstan-type BodyData array<string, mixed>
 * @phpstan-type Data QueryData & BodyData
 */
final readonly class InputParser implements InputParserInterface
{
    /**
     * Constructor for InputParser.
     *
     * This constructor initializes the InputParser with instances of QueryParser and BodyParser.
     *
     * @param QueryParserInterface $queryParser An instance of QueryParser to handle query parameters.
     * @param BodyParserInterface $bodyParser An instance of BodyParser to handle the request body.
     */
    public function __construct(private QueryParserInterface $queryParser, private BodyParserInterface $bodyParser)
    {
    }

    /**
     * Parses the query parameters and body of the request.
     *
     * @param mixed $resourceId The identity of the resource being queried, it can be null.
     * @param QueryParameters $queryParams The query parameters from the request.
     * @param RequestBody $requestBody The body of the request.
     */
    #[Override]
    public function parse(mixed $resourceId, array $queryParams, array $requestBody): void
    {
        $this->queryParser->parse($resourceId, $queryParams);
        $this->bodyParser->parse($requestBody);
    }

    /**
     * Returns the combined data from both query and body parsers.
     *
     * This method merges the data collected from the query parameters and the request body
     * into a single associative array.
     *
     * @return Data The combined data from both query and body parsers.
     */
    #[Override]
    public function getData(): array
    {
        // @phpstan-ignore-next-line
        return array_merge($this->queryParser->getData(), $this->bodyParser->getData());
    }

    /**
     * Parses the query parameters and body of the request.
     *
     * This method is a convenience method that allows parsing of query parameters
     * and body separately, without needing to pass an identity.
     *
     * @param string|null $resourceId The query string from the request.
     * @param QueryParameters $queryParams The query parameters from the request.
     */
    #[Override]
    public function parseQuery(?string $resourceId, array $queryParams): void
    {
        $this->queryParser->parse($resourceId, $queryParams);
    }

    /**
     * Parses the body of the request.
     *
     * This method decodes the JSON body, validates it, and stores the attributes
     * in the body parser's data collection.
     *
     * @param RequestBody $requestBody The raw request body to be parsed.
     */
    #[Override]
    public function parseBody(array $requestBody): void
    {
        $this->bodyParser->parse($requestBody);
    }

    /**
     * Returns the parsed data from the query parser.
     *
     * This method retrieves the data collected from the query parameters,
     * allowing access to the structured data extracted from the API request query.
     *
     * @return QueryData The parsed data from the query parser.
     */
    #[Override]
    public function getQueryData(): array
    {
        return $this->queryParser->getData();
    }

    /**
     * Returns the parsed data from the body parser.
     *
     * This method retrieves the data collected from the request body,
     * allowing access to the structured data extracted from the API request body.
     *
     * @return BodyData The parsed data from the body parser.
     */
    #[Override]
    public function getBodyData(): array
    {
        return $this->bodyParser->getData();
    }
}
