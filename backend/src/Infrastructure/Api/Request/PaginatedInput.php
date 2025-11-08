<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Abstract class for validated input.
 *
 * This class provides a base implementation for validated input, including
 * properties for resource ID, pagination, filtering, sorting, including related
 * resources, and field selection.
 *
 * @psalm-type Data = array{
 *     resourceId: null, // The identity of the resource being queried (null for paginated input).
 *     pageSize: int, // The size of the page for pagination.
 *     pageNumber: int, // The current page number for pagination.
 *    ...
 *  }
 */
abstract class PaginatedInput extends QueryInput
{
    /**
     * The number of items per page.
     *
     * This should be a numeric value between 1 and 30.
     */
    #[Assert\Type('numeric')]
    #[Assert\Range(min: 1, max: 30)]
    private readonly int $pageSize;

    /**
     * The page number to retrieve.
     *
     * This should be greater than or equal to 1.
     */
    #[Assert\Type('numeric')]
    #[Assert\GreaterThanOrEqual(1)]
    private readonly int $pageNumber;

    /**
     * Constructor to initialize the validated input.
     *
     * @param Data $data The data to initialize the input with.
     */
    public function __construct(array $data)
    {
        $this->pageSize = isset($data['pageSize'])
            ? (int) filter_var($data['pageSize'], FILTER_SANITIZE_NUMBER_INT)
            : 10;

        $this->pageNumber = isset($data['pageNumber'])
            ? (int) filter_var($data['pageNumber'], FILTER_SANITIZE_NUMBER_INT)
            : 1;

        parent::__construct($data);
    }

    /**
     * Returns the page size.
     *
     * This method returns the number of items per page for pagination.
     *
     * @return int The page size, which is a numeric value between 1 and 30.
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Returns the page number.
     *
     * This method returns the current page number for pagination.
     *
     * @return int The page number, which is a numeric value greater than or equal to 1.
     */
    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }
}
