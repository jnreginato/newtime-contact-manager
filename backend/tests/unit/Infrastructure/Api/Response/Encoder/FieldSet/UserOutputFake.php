<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response\Encoder\FieldSet;

use InvalidArgumentException;
use App\Application\Result\Result;
use App\Infrastructure\Api\Response\OutputInterface;
use Override;

/**
 * Class UserOutputFake.
 *
 * This class is a fake implementation of the PresenterInterface for testing purposes.
 * It provides hardcoded data for user fields and relationships.
 */
final readonly class UserOutputFake implements OutputInterface
{
    /**
     * Constructor.
     *
     * @param UserEntityFake $entity The entity object.
     */
    public function __construct(private UserEntityFake $entity)
    {
    }

    /**
     * Create a UserOutputFake instance from a Result object.
     *
     * @param Result $result The result containing the entity.
     * @return self A new instance of UserOutputFake.
     * @throws InvalidArgumentException If the provided entity is not of type UserEntityFake.
     */
    #[Override]
    public static function fromResult(Result $result): self
    {
        if (!$result->entity instanceof UserEntityFake) {
            throw new InvalidArgumentException(
                'Expected entity of type UserEntityFake, got ' . get_debug_type($result->entity),
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
        return 'user';
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
            'name' => 'Jonatan',
            'email' => 'jonatan@example.com',
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
        return 1;
    }

    /**
     * Return the entity associated with the output.
     *
     * @return UserEntityFake The entity object.
     */
    #[Override]
    public function getEntity(): UserEntityFake
    {
        return $this->entity;
    }

    /**
     * Returns the relationships of the resource.
     *
     * @param string $fieldName The name of the relationship field.
     * @return string The related Output class name.
     */
    #[Override]
    public function getRelatedOutputClass(string $fieldName): string
    {
        return match ($fieldName) {
            'profile' => ProfileOutputFake::class,
            default => throw new InvalidArgumentException("No Output class mapping for '$fieldName'"),
        };
    }
}
