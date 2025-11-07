<?php

declare(strict_types=1);

namespace App\Application\Message\Command;

use App\Application\Message\Command;
use Override;

/**
 * Command to create a new contact.
 *
 * This command is used to create a new contact in the system.
 */
final readonly class CreateContactCommand extends Command
{
    /**
     * Constructor for the CreateContactCommand class.
     *
     * @param string $firstName The first name of the contact.
     * @param string $lastName The last name of the contact.
     * @param string $email The email address of the contact.
     */
    protected function __construct(public string $firstName, public string $lastName, public string $email)
    {
        parent::__construct();
    }

    /**
     * Create an instance of CreateContactCommand from an associative array.
     *
     * @param array<string, mixed> $data The input data.
     *
     * @return self An instance of CreateContactCommand.
     */
    #[Override]
    public static function fromArray(array $data): self
    {
        return new self(
            (string)($data['firstName'] ?? ''),
            (string)($data['lastName'] ?? ''),
            (string)($data['email'] ?? ''),
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
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'email' => $this->email,
            ],
        );
    }
}
