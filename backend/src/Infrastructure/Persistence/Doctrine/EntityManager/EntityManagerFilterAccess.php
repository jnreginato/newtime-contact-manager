<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\EntityManager;

use Doctrine\ORM\Query\FilterCollection;
use Override;

/**
 * EntityManagerFilterAccess trait.
 *
 * This trait provides methods for managing filters in the Doctrine EntityManager.
 * It includes methods for getting enabled filters, checking filter state, and
 * checking if the EntityManager has filters.
 */
trait EntityManagerFilterAccess
{
    /**
     * Gets the enabled filters.
     *
     * @return FilterCollection The FilterCollection instance
     */
    #[Override]
    public function getFilters(): FilterCollection
    {
        return $this->entityManager->getFilters();
    }

    /**
     * Checks whether the state of the filter collection is clean.
     *
     * @return bool True if the state is clean, false otherwise
     */
    #[Override]
    public function isFiltersStateClean(): bool
    {
        return $this->entityManager->isFiltersStateClean();
    }

    /**
     * Checks whether the EntityManager has filters.
     *
     * @return bool True if the EntityManager has filters, false otherwise
     */
    #[Override]
    public function hasFilters(): bool
    {
        return $this->entityManager->hasFilters();
    }
}
