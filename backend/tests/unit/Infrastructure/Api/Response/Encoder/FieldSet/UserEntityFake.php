<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response\Encoder\FieldSet;

use App\Domain\Entity\EntityInterface;
use Override;

/**
 * Class UserEntityFake.
 *
 * This class is a fake implementation of the EntityInterface for testing purposes.
 * It provides hardcoded data for user entities and their associated profiles.
 */
final class UserEntityFake implements EntityInterface
{
    /**
     * Returns a hardcoded ID for the user entity.
     *
     * @return string The hardcoded ID of the user entity.
     */
    #[Override]
    public function id(): string
    {
        return '1';
    }

    /**
     * Returns the profile associated with the user entity.
     *
     * @return ProfileEntityFake The type of the entity.
     */
    public function profile(): ProfileEntityFake
    {
        return new ProfileEntityFake();
    }
}
