<?php

declare(strict_types=1);

namespace App\Domain\Entity;

/**
 * Interface EntityInterface.
 *
 * This interface defines the contract for entities in the domain layer.
 * It serves as a marker interface for all entities.
 *
 * @SuppressWarnings("PHPMD.ShortMethodName")
 */
interface EntityInterface
{
    /**
     * Get the unique identifier of the entity.
     *
     * This method returns the unique identifier of the entity, which is typically
     * used to distinguish it from other entities in the system.
     *
     * @return string The unique identifier of the entity.
     */
    public function id(): string;
}
