<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

use Override;

/**
 * FakeConcreteInput class is a mock implementation of ValidatedInput for testing purposes.
 * It provides specific allowed filtering keys, sorting fields, related resources, and sparse fieldsets.
 */
final class FakeConcreteInput extends PaginatedInput
{
    /**
     * Converts the input data to a FakeConcreteQuery.
     *
     * @return FakeConcreteQuery The query object created from the input data.
     */
    #[Override]
    public function toQuery(): FakeConcreteQuery
    {
        return FakeConcreteQuery::fromArray($this->data);
    }
}
