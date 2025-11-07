<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Override;

use function sprintf;

/**
 * SoftDeleteFilter class.
 *
 * Adds a condition to queries to exclude entities where "deleted_at" is not null.
 */
final class SoftDeleteFilter extends SQLFilter implements SoftDeleteFilterInterface
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
    #[Override]
    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        if (!$targetEntity->hasField('deletedAt')) {
            return '';
        }

        return sprintf('%s.deleted_at IS NULL', $targetTableAlias);
    }
}
