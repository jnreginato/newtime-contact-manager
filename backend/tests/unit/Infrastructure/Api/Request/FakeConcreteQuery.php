<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

use App\Application\Message\PaginatedQuery;
use Override;

/**
 * Class representing a fake concrete query for testing purposes.
 */
final readonly class FakeConcreteQuery extends PaginatedQuery
{
    /**
     * Creates an instance of FakeConcreteQuery from an associative array.
     *
     * @param array<string, mixed> $data The input data.
     * @return self An instance of FakeConcreteQuery.
     */
    #[Override]
    public static function fromArray(array $data): self
    {
        return new self(
            $data['resourceId'] ?? null, // @phpstan-ignore-line
            (int) ($data['pageSize'] ?? 20), // @phpstan-ignore-line
            (int) ($data['pageNumber'] ?? 1), // @phpstan-ignore-line
        );
    }
}
