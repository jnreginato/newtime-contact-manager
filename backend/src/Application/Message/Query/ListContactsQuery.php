<?php

declare(strict_types=1);

namespace App\Application\Message\Query;

use App\Application\Message\PaginatedQuery;
use Override;

/**
 * Class representing the query to list contacts with pagination support.
 *
 * This class encapsulates the data required to list contacts, including pagination
 * parameters and any related entities to include in the response.
 */
final readonly class ListContactsQuery extends PaginatedQuery
{
    /**
     * Creates an instance of ListContactsQuery from an associative array.
     *
     * @param array<string, mixed> $data The input data.
     * @return self An instance of ListContactsQuery.
     */
    #[Override]
    public static function fromArray(array $data): self
    {
        // @phpstan-ignore-next-line
        return new self($data['resourceId'] ?? null, (int) ($data['pageSize'] ?? 20), (int) ($data['pageNumber'] ?? 1));
    }
}
