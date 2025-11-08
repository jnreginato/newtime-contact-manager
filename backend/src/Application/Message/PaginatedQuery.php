<?php

declare(strict_types=1);

namespace App\Application\Message;

use Override;

/**
 * Abstract base class for queries in the CQRS pattern.
 *
 * This class provides a common structure for all query classes, including pagination.
 *
 * @SuppressWarnings("PHPMD.ShortVariable")
 */
abstract readonly class PaginatedQuery extends Message implements PaginatedQueryInterface
{
    /**
     * Constructor for the Query class.
     *
     * @param string|int|null $id The ID of the resource to be read.
     * @param int $pageSize The number of resources to return per page (default is 20).
     * @param int $pageNumber The page number to return (default is 1).
     */
    protected function __construct(public string | int | null $id, public int $pageSize, public int $pageNumber)
    {
        parent::__construct();
    }

    /**
     * Create an instance of the specific Query subclass from an associative array.
     *
     * @param array<string, mixed> $data The input data.
     * @return self An instance of the specific Query subclass.
     */
    #[Override]
    abstract public static function fromArray(array $data): self;

    /**
     * Convert the query to an associative array.
     *
     * @return array<string, mixed> The query data as an associative array.
     */
    #[Override]
    public function toArray(): array
    {
        return array_merge(
            $this->baseToArray(),
            [
                'id' => $this->id,
                'pageSize' => $this->pageSize,
                'pageNumber' => $this->pageNumber,
            ],
        );
    }

    /**
     * Get the page size.
     *
     * @return int The number of resources per page.
     */
    #[Override]
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Get the page number.
     *
     * @return int The current page number.
     */
    #[Override]
    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }
}
