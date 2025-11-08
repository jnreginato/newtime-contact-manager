<?php

declare(strict_types=1);

namespace App\Application\Result;

use App\Infrastructure\Api\Response\OutputInterface;

/**
 * Interface PaginatedResultInterface
 *
 * This interface defines the methods required for managing paginated data in an API context.
 * It includes methods for retrieving data, checking collection status, pagination details,
 * and more.
 */
interface PaginatedResultInterface
{
    /**
     * Retrieves the data contained in the paginated response.
     *
     * @return array<OutputInterface> The data as an array.
     */
    public function getData(): array;

    /**
     * Retrieves the total number of items on the current page.
     *
     * @return int The total number of items on the current page.
     */
    public function getCount(): int;

    /**
     * Retrieves the current page number.
     *
     * @return int The current page number.
     */
    public function getCurrentPage(): int;

    /**
     * Retrieves the number of items per page.
     *
     * @return int The number of items per page.
     */
    public function getPerPage(): int;

    /**
     * Retrieves the total number of pages available.
     *
     * @return int The total number of pages.
     */
    public function getTotalPages(): int;

    /**
     * Retrieves the total number of items across all pages.
     *
     * @return int The total number of items.
     */
    public function getTotalItems(): int;

    /**
     * Checks if there are more items available in the collection.
     *
     * This method returns true if the total number of pages is greater than
     * the current page number, indicating that there are additional items
     * available beyond the current page.
     *
     * @return bool True if there are more items available, false otherwise.
     */
    public function hasMoreItems(): bool;
}
