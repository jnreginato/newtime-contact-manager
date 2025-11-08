<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Parser;

use App\Infrastructure\Api\Exception\ApiInvalidQueryException;
use App\Infrastructure\Api\Exception\ErrorCode;
use App\Infrastructure\Api\Exception\ErrorMessage;
use Override;

use function array_diff_key;
use function array_flip;
use function array_key_exists;
use function array_keys;
use function implode;
use function is_array;
use function is_numeric;

/**
 * PageParser is a class that handles the parsing of pagination parameters from a request.
 *
 * It extends the BaseParser class and provides methods to parse paging
 * parameters such as page size and page number.
 *
 * @psalm-type PageParameters = array{
 *     size?: int,
 *     number?: int,
 * }
 * @psalm-type QueryParameters = array{
 *     page?: PageParameters,
 *     ...
 * }
 */
final class PageParser extends BaseParser implements PageParserInterface
{
    public const string PARAM_PAGE = 'page';
    public const string PARAM_PAGE_SIZE = 'size';
    public const string PARAM_PAGE_NUMBER = 'number';

    /**
     * The size of the page for pagination.
     *
     * This represents the number of items to be displayed on each page.
     * Default is 10.
     */
    private int $pageSize = 10;

    /**
     * The current page number for pagination.
     *
     * This represents the page number in the pagination sequence.
     * Default is 1.
     */
    private int $pageNumber = 1;

    /**
     * Parses the paging parameters from the request.
     *
     * This method checks if the 'page' parameter exists in the request.
     * If it does, it validates that it is an array and contains the expected keys.
     * If the validation fails, it adds an error to the error aggregator and throws an API error.
     * The expected keys in the 'page' parameter are:
     * - 'size': The number of items per page (default is 10).
     * - 'number': The current page number (default is 1).
     *
     * @param QueryParameters $parameters The parameters passed in the query.
     */
    #[Override]
    public function parse(array $parameters): void
    {
        if (!array_key_exists(self::PARAM_PAGE, $parameters)) {
            return;
        }

        $this->validatePageArray($parameters[self::PARAM_PAGE]);

        $parameters[self::PARAM_PAGE][self::PARAM_PAGE_SIZE] ??= $this->pageSize;
        $parameters[self::PARAM_PAGE][self::PARAM_PAGE_NUMBER] ??= $this->pageNumber;

        $this->validateUnknownPageKey($parameters[self::PARAM_PAGE]);
        $this->validateNumericPageValue($parameters[self::PARAM_PAGE]);

        $this->pageSize = (int) $parameters[self::PARAM_PAGE][self::PARAM_PAGE_SIZE];
        $this->pageNumber = (int) $parameters[self::PARAM_PAGE][self::PARAM_PAGE_NUMBER];
    }

    /**
     * Returns the page size.
     *
     * @return int The size of the page.
     */
    #[Override]
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Returns the current page number.
     *
     * @return int The current page number.
     */
    #[Override]
    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    /**
     * Validates that the page parameter is an array and not empty.
     *
     * If the validation fails, it throws a validation error.
     *
     * @param PageParameters $pageParameter The page parameter to validate.
     * @throws ApiInvalidQueryException If the page parameter is not an array or is empty.
     */
    private function validatePageArray(mixed $pageParameter): void
    {
        if (is_array($pageParameter) && $pageParameter !== []) {
            return;
        }

        $this->throwValidationError(
            self::PARAM_PAGE,
            $pageParameter,
            ErrorCode::PageParameterNotArray,
            ErrorMessage::PageParameterNotArray,
            [implode(', ', [self::PARAM_PAGE_SIZE, self::PARAM_PAGE_NUMBER])],
        );
    }

    /**
     * Validates that the page parameter does not contain unknown keys.
     *
     * If it contains keys other than 'size' and 'number', it throws a validation error.
     *
     * @param PageParameters $pageParameter The page parameter to validate.
     */
    private function validateUnknownPageKey(array $pageParameter): void
    {
        $unknownAttributes = array_diff_key(
            $pageParameter,
            array_flip([self::PARAM_PAGE_SIZE, self::PARAM_PAGE_NUMBER]),
        );

        if (!$unknownAttributes) {
            return;
        }

        $invalidKeysString = implode(', ', array_keys($unknownAttributes));

        $this->throwValidationError(
            self::PARAM_PAGE,
            $invalidKeysString,
            ErrorCode::PageParameterKeyNotAllowed,
            ErrorMessage::PageParameterKeyNotAllowed,
            [$invalidKeysString, implode(', ', [self::PARAM_PAGE_SIZE, self::PARAM_PAGE_NUMBER])],
        );
    }

    /**
     * Validates that all values in the page parameter are numeric.
     *
     * If any value is not numeric, it throws a validation error.
     *
     * @param PageParameters $pageParameter The page parameter to validate.
     */
    private function validateNumericPageValue(array $pageParameter): void
    {
        foreach ($pageParameter as $value) {
            if (is_numeric($value)) {
                continue;
            }

            $this->throwValidationError(
                self::PARAM_PAGE,
                $value,
                ErrorCode::PageParameterValueMustBeNumeric,
                ErrorMessage::PageParameterValueMustBeNumeric,
            );
        }
    }
}
