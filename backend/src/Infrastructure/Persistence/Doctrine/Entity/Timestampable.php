<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Infrastructure\Persistence\Doctrine\Types\CustomTypes;
use App\Infrastructure\Time\SystemClockUTC;

/**
 * Timestampable
 *
 * This trait provides timestamp functionality for entities.
 * It automatically sets the created and updated timestamps.
 */
trait Timestampable
{
    /**
     * The created timestamp.
     *
     * This property stores the timestamp when the entity was created.
     * It is automatically set when the entity is persisted.
     */
    #[ORM\Column(name: 'created_at', type: CustomTypes::DATETIME_IMMUTABLE_UTC)]
    protected DateTimeImmutable $createdAt;

    /**
     * The updated timestamp.
     *
     * This property stores the timestamp when the entity was last updated.
     * It is automatically set when the entity is updated.
     */
    #[ORM\Column(name: 'updated_at', type: CustomTypes::DATETIME_IMMUTABLE_UTC)]
    protected DateTimeImmutable $updatedAt;

    /**
     * Get the created timestamp.
     *
     * @return DateTimeImmutable The created timestamp.
     */
    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Get the updated timestamp.
     *
     * @return DateTimeImmutable The updated timestamp.
     */
    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Set the created timestamp when the entity is created.
     *
     * This method sets the created timestamp to the current time when the
     * entity is created.
     * It is called automatically by Doctrine when the entity is persisted.
     */
    #[ORM\PrePersist]
    public function onCreate(): void
    {
        $now = (new SystemClockUTC())->now();

        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    /**
     * Set the updated timestamp when the entity is updated.
     *
     * This method sets the updated timestamp to the current time when the
     * entity is updated.
     * It is called automatically by Doctrine when the entity is updated.
     */
    #[ORM\PreUpdate]
    public function onUpdate(): void
    {
        $this->updatedAt = (new SystemClockUTC())->now();
    }
}
