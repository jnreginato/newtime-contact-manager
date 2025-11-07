<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\EntityManager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * EntityManagerAdapterInterface interface.
 *
 * This interface extends the EntityManagerInterface and serves as a marker
 * interface for the EntityManagerAdapter class.
 */
interface EntityManagerAdapterInterface extends EntityManagerInterface
{
    /**
     * Gets the EntityManager instance.
     *
     * @return EntityManager The EntityManager instance
     */
    public function getEntityManager(): EntityManager;
}
