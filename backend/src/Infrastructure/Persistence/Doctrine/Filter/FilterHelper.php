<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Filter;

use Doctrine\ORM\EntityManagerInterface;

/**
 * FilterHelper class.
 *
 * This class provides utility methods for managing Doctrine filters.
 * It allows enabling and disabling filters in the EntityManager.
 *
 * @SuppressWarnings(PHPMD)
 */
final class FilterHelper
{
    /**
     * Disables all filters in the EntityManager.
     *
     * @param EntityManagerInterface $entityManager The EntityManager instance.
     */
    public static function disableAll(EntityManagerInterface $entityManager): void
    {
        foreach (array_keys($entityManager->getFilters()->getEnabledFilters()) as $name) {
            $entityManager->getFilters()->disable($name);
        }
    }

    /**
     * Enables a filter in the EntityManager.
     *
     * @param EntityManagerInterface $entityManager The EntityManager instance.
     * @param string $name The name of the filter to enable.
     */
    public static function enable(EntityManagerInterface $entityManager, string $name): void
    {
        if ($entityManager->getFilters()->isEnabled($name)) {
            return;
        }

        $entityManager->getFilters()->enable($name);
    }
}
