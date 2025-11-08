<?php

declare(strict_types=1);

namespace App\Application\Message;

/**
 * Interface for paginated query objects in the CQRS pattern.
 *
 * This interface extends the MessageInterface and serves as a marker for paginated query objects.
 * It does not define any additional methods beyond those inherited from MessageInterface.
 */
interface PaginatedQueryInterface extends MessageInterface
{
    /**
     * Gets the current page number for the paginated query.
     *
     * @return int The current page number.
     */
    public function getPageSize(): int;

    /**
     * Gets the number of items per page for the paginated query.
     *
     * @return int The number of items per page.
     */
    public function getPageNumber(): int;
}
