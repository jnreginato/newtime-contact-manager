<?php

declare(strict_types=1);

namespace App\Application\Result;

use App\Infrastructure\Api\Response\OutputInterface;
use Override;

/**
 * Class PaginatedResult
 *
 * This class implements the PaginatedResultInterface and provides methods to manage
 * paginated data, including collection status, pagination details, and more.
 */
final readonly class PaginatedResult implements PaginatedResultInterface
{
    /**
     * Indicates whether there are more items available in the collection.
     *
     * This property is set to true if the total number of pages is greater than
     * the current page number, indicating that there are additional items
     * available beyond the current page.
     *
     * @var bool Indicates whether there are more items available in the collection.
     */
    private bool $hasMoreItems;

    /**
     * Constructs a new instance of PaginatedResult.
     *
     * @param array<OutputInterface> $data The data for the current page.
     * @param int $count The total number of items on the current page.
     * @param int $currentPage The current page number.
     * @param int $perPage The number of items per page.
     * @param int $totalPages The total number of pages available.
     * @param int $totalItems The total number of items across all pages.
     */
    public function __construct(
        private array $data,
        private int $count,
        private int $currentPage,
        private int $perPage,
        private int $totalPages,
        private int $totalItems,
    ) {
        $this->hasMoreItems = $totalPages > $currentPage;
    }

    /**
     * Retrieves the data contained in the paginated response.
     *
     * @return array<OutputInterface> The data as an array.
     */
    #[Override]
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Retrieves the total number of items on the current page.
     *
     * @return int The total number of items on the current page.
     */
    #[Override]
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Retrieves the current page number.
     *
     * @return int The current page number.
     */
    #[Override]
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Retrieves the number of items per page.
     *
     * @return int The number of items per page.
     */
    #[Override]
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Retrieves the total number of pages available.
     *
     * @return int The total number of pages.
     */
    #[Override]
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * Retrieves the total number of items across all pages.
     *
     * @return int The total number of items.
     */
    #[Override]
    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    /**
     * Checks if there are more items available in the collection.
     *
     * This method returns true if the total number of pages is greater than
     * the current page number, indicating that there are additional items
     * available beyond the current page.
     *
     * @return bool True if there are more items available, false otherwise.
     */
    #[Override]
    public function hasMoreItems(): bool
    {
        return $this->hasMoreItems;
    }
}
