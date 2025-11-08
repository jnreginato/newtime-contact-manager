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

    /**
     * Returns the allowed filtering keys for validation.
     *
     * @return array<string> The allowed filtering keys.
     */
    #[Override]
    protected function allowedFilterFields(): array
    {
        return ['firstName', 'lastName'];
    }

    /**
     * Returns the allowed sorting fields for validation.
     *
     * @return array<string> The allowed sorting fields.
     */
    #[Override]
    protected function allowedSortFields(): array
    {
        return ['firstName', 'lastName', 'createdAt'];
    }

    /**
     * Returns the allowed include relationships for validation.
     *
     * @return array<string> The allowed include relationships.
     */
    #[Override]
    protected function allowedIncludes(): array
    {
        return ['role'];
    }

    /**
     * Returns the allowed sparse fieldsets for validation.
     *
     * @return array<string, array<string>> The allowed sparse fieldsets.
     */
    #[Override]
    protected function allowedSparseFieldsets(): array
    {
        return [
            'roles' => ['id', 'description'],
            'users' => ['firstName', 'lastName'],
        ];
    }
}
