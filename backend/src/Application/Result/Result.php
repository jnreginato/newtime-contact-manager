<?php

declare(strict_types=1);

namespace App\Application\Result;

use App\Domain\Entity\EntityInterface;
use Override;

/**
 * Class representing the result of a use case operation.
 *
 * This class encapsulates the domain entity resulting from a use case execution.
 */
final readonly class Result implements ResultInterface
{
    /**
     * Constructor for the Result class.
     *
     * @param EntityInterface $entity The domain entity associated with the result.
     */
    public function __construct(public EntityInterface $entity)
    {
    }

    /**
     * Create a Result instance from a domain entity.
     *
     * @param EntityInterface $entity The domain entity to create the result from.
     * @return self The created Result instance.
     */
    #[Override]
    public static function fromDomain(EntityInterface $entity): self
    {
        return new self($entity);
    }
}
