<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response\Encoder\FieldSet;

use InvalidArgumentException;
use App\Application\Result\Result;
use App\Infrastructure\Api\Response\OutputInterface;
use Override;

/**
 * Class ProfileOutputFake.
 *
 * This class is a fake implementation of the OutputInterface for testing purposes.
 */
final readonly class ProfileOutputFake implements OutputInterface
{
    /**
     * Constructor.
     *
     * @param ProfileEntityFake $entity The entity object.
     */
    public function __construct(private ProfileEntityFake $entity)
    {
    }

    /**
     * Create a ProfileOutputFake instance from a Result object.
     *
     * @param Result $result The result containing the entity.
     * @return self A new instance of ProfileOutputFake.
     * @throws InvalidArgumentException If the provided entity is not of type ProfileEntityFake.
     */
    #[Override]
    public static function fromResult(Result $result): self
    {
        if (!$result->entity instanceof ProfileEntityFake) {
            throw new InvalidArgumentException(
                'Expected entity of type ProfileEntityFake, got ' . get_debug_type($result->entity),
            );
        }

        return new self($result->entity);
    }

    /**
     * Returns the type of the resource.
     *
     * @return string The type of the resource.
     */
    #[Override]
    public function getType(): string
    {
        return 'profile';
    }

    /**
     * Returns the fields of the resource.
     *
     * @return array<string, mixed> An associative array of fields.
     */
    #[Override]
    public function getFields(): array
    {
        return [
            'id' => $this->getId(),
            'bio' => 'Developer',
            'website' => 'https://example.com',
            'location' => 'Earth',
        ];
    }

    /**
     * Returns the ID of the resource.
     *
     * @return int The ID of the resource.
     */
    #[Override]
    public function getId(): int
    {
        return 123;
    }

    /**
     * Returns the entity associated with the output.
     *
     * @return ProfileEntityFake The entity object.
     */
    #[Override]
    public function getEntity(): ProfileEntityFake
    {
        return $this->entity;
    }

    /**
     * Returns the related output class for a specific field name.
     *
     * @param string $fieldName The name of the field for which to retrieve the related output class.
     * @return class-string The class name of the related output.
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    #[Override]
    public function getRelatedOutputClass(string $fieldName): string
    {
        // @phpstan-ignore-next-line
        return '';
    }
}
