<?php

declare(strict_types=1);

namespace App\Application\Message\Command;

use App\Application\Message\Command;
use App\Application\Support\ValueChange;
use Override;

/**
 * Class representing the command to update a contact.
 *
 * This class encapsulates the data required to update an existing contact in the system.
 *
 * @SuppressWarnings("PHPMD.ShortVariable")
 */
final readonly class UpdateContactCommand extends Command
{
    /**
     * Constructor for the UpdateContactCommand class.
     *
     * @param int $id The ID of the contact to be updated.
     * @param ValueChange<string> $firstName The change object for the contact's first name.
     * @param ValueChange<string> $lastName The change object for the contact's last name.
     * @param ValueChange<string> $email The change object for the contact's email.
     */
    protected function __construct(
        public int $id,
        public ValueChange $firstName,
        public ValueChange $lastName,
        public ValueChange $email,
    ) {
        parent::__construct();
    }

    /**
     * Create an instance of UpdateContactCommand from an associative array.
     *
     * @param array<string, mixed> $data The input data.
     * @return self An instance of UpdateContactCommand.
     */
    #[Override]
    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['resourceId'] ?? 0), // @phpstan-ignore-line
            ValueChange::fromValue($data['firstName'] ?? null), // @phpstan-ignore-line
            ValueChange::fromValue($data['lastName'] ?? null), // @phpstan-ignore-line
            ValueChange::fromValue($data['email'] ?? null), // @phpstan-ignore-line
        );
    }

    /**
     * Convert the command to an associative array.
     *
     * @return array<string, mixed> The command data as an associative array.
     */
    #[Override]
    public function toArray(): array
    {
        return array_merge(
            $this->baseToArray(),
            [
                'id' => $this->id,
                'firstName' => $this->firstName->value,
                'lastName' => $this->lastName->value,
                'email' => $this->email->value,
            ],
        );
    }
}
