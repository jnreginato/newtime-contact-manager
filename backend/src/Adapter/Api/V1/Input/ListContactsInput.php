<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Input;

use App\Application\Message\Query\ListContactsQuery;
use App\Infrastructure\Api\Request\PaginatedInput;
use Override;

/**
 * Class representing the input data for listing contacts.
 */
final class ListContactsInput extends PaginatedInput
{
    /**
     * Converts the input data to a ListContactsQuery.
     *
     * @return ListContactsQuery The query to list contacts.
     */
    #[Override]
    public function toQuery(): ListContactsQuery
    {
        return ListContactsQuery::fromArray($this->data);
    }
}
