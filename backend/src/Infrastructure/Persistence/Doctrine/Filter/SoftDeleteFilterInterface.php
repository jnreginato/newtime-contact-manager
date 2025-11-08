<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Filter;

/**
 * Interface for soft delete filters in Doctrine.
 *
 * This interface defines the contract for soft delete filters that can be
 * applied to entities. Soft delete filters are used to exclude entities that
 * have been "softly deleted" (i.e., marked as deleted without actually
 * removing them from the database).
 */
interface SoftDeleteFilterInterface extends FilterInterface
{
}
