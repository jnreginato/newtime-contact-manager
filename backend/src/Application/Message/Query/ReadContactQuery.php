<?php

declare(strict_types=1);

namespace App\Application\Message\Query;

use App\Application\Message\Query;
use Override;

/**
 * Class representing the query to read a contact by its ID.
 *
 * This class encapsulates the data required to read an existing contact,
 * including the contact ID and any related entities to include in the response.
 */
final readonly class ReadContactQuery extends Query
{
    /**
     * Create an instance of ReadRoleQuery from an associative array.
     *
     * @param array<string, mixed> $data The input data.
     * @return static An instance of ReadRoleQuery.
     */
    #[Override]
    public static function fromArray(array $data): self
    {
        // @phpstan-ignore-next-line
        return new self($data['resourceId'] ?? null);
    }
}
