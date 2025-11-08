<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Interface for custom Doctrine filters.
 *
 * This interface defines the contract for custom filters that can be applied
 * to Doctrine queries.
 */
interface FilterInterface
{
    /**
     * Adds a filter condition to the query.
     *
     * Doctrine calls this method when the filter is applied to a query.
     *
     * @phpstan-param ClassMetadata<object> $targetEntity The metadata of the target entity.
     * @param string $targetTableAlias The alias of the target table in the query.
     * @return string The SQL condition to be added to the query.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string;
}
