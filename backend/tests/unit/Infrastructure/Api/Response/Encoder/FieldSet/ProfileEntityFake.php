<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response\Encoder\FieldSet;

use App\Domain\Entity\EntityInterface;
use Override;

/**
 * Class ProfileEntityFake.
 *
 * This class is a fake implementation of the EntityInterface for testing purposes.
 * It provides hardcoded data for profile entities.
 */
final class ProfileEntityFake implements EntityInterface
{
    /**
     * Returns a hardcoded ID for the profile entity.
     *
     * @return string The hardcoded ID of the profile entity.
     */
    #[Override]
    public function id(): string
    {
        return '123';
    }

    /**
     * Returns a hardcoded name for the profile entity.
     *
     * @return string The hardcoded name of the profile entity.
     */
    public function bio(): string
    {
        return 'Developer';
    }
}
