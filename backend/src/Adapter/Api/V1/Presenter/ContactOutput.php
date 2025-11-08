<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Presenter;

use App\Application\Result\Result;
use App\Domain\Entity\Contact;
use App\Infrastructure\Api\Response\OutputInterface;
use InvalidArgumentException;
use Override;

use function get_debug_type;

/**
 * ContactOutput class.
 *
 * This class is responsible for presenting the contact entity in a structured format.
 * It extends the Output class and provides methods to retrieve the attributes
 * and relationships of the contact entity.
 */
final readonly class ContactOutput implements OutputInterface
{
    /**
     * Constructor.
     *
     * Initializes the ContactOutput with a Domain Contact entity.
     *
     * @param Contact $entity The contact entity to be represented.
     */
    private function __construct(private Contact $entity)
    {
    }

    /**
     * Create a ContactOutput instance from a Result object.
     *
     * This static method creates a new instance of ContactOutput from the provided Result object.
     * It ensures that the entity within the Result is of type Contact.
     *
     * @param Result $result The result containing the Contact entity.
     * @return self A new instance of ContactOutput.
     * @throws InvalidArgumentException If the provided entity is not of type Contact.
     */
    #[Override]
    public static function fromResult(Result $result): self
    {
        if (!$result->entity instanceof Contact) {
            throw new InvalidArgumentException(
                'Expected entity of type Contact, got ' . get_debug_type($result->entity),
            );
        }

        return new self($result->entity);
    }

    /**
     * Get the fields of the ContactOutput.
     *
     * This method returns an array of fields that represent the contact data.
     *
     * @return array<string, mixed> The fields of the ContactOutput.
     */
    #[Override]
    public function getFields(): array
    {
        return [
            'id' => $this->entity->id(),
            'firstName' => $this->entity->firstName(),
            'lastName' => $this->entity->lastName(),
            'email' => $this->entity->email(),
            'createdAt' => $this->entity->createdAt()->format(DATE_ATOM),
            'updatedAt' => $this->entity->updatedAt()->format(DATE_ATOM),
        ];
    }
}
