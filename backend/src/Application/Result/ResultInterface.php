<?php

declare(strict_types=1);

namespace App\Application\Result;

use App\Domain\Entity\EntityInterface;

/**
 * Interface ResultInterface
 *
 * This interface serves as a marker for result types in the application layer.
 */
interface ResultInterface
{
    /**
     * Create a Result instance from a domain entity.
     *
     * @param EntityInterface $entity The domain entity to create the result from.
     * @return self The created Result instance.
     */
    public static function fromDomain(EntityInterface $entity): self;
}
