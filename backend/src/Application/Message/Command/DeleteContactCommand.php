<?php

declare(strict_types=1);

namespace App\Application\Message\Command;

use App\Application\Message\Command;
use Override;

/**
 * Class representing the command to delete a contact.
 *
 * This class encapsulates the data required to delete an existing contact in the system.
 *
 * @SuppressWarnings("PHPMD.ShortVariable")
 */
final readonly class DeleteContactCommand extends Command
{
    /**
     * Constructor for DeleteContactCommand.
     *
     * @param int $id The ID of the contact to be deleted.
     */
    protected function __construct(public int $id)
    {
        parent::__construct();
    }

    /**
     * Create an instance of DeleteContactCommand from an associative array.
     *
     * @param array<string, mixed> $data The input data.
     * @return self An instance of DeleteContactCommand.
     */
    #[Override]
    public static function fromArray(array $data): self
    {
        // @phpstan-ignore-next-line
        return new self((int) ($data['resourceId'] ?? 0));
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
            ],
        );
    }
}
