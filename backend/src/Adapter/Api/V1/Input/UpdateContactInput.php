<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Input;

use App\Application\Message\Command\UpdateContactCommand;
use App\Infrastructure\Api\Request\CommandInput;
use Override;

/**
 * Class representing the input data for updating a contact.
 */
final class UpdateContactInput extends CommandInput
{
    /**
     * Converts the input data to an UpdateContactInput.
     *
     * @return UpdateContactCommand The command to update a contact.
     */
    #[Override]
    public function toCommand(): UpdateContactCommand
    {
        return UpdateContactCommand::fromArray($this->data);
    }

    /**
     * Returns the allowed body fields for the UpdateContactInput.
     *
     * @return list<string> The allowed request body fields.
     */
    #[Override]
    protected function allowedBodyFields(): array
    {
        return ['firstName', 'lastName', 'email'];
    }

    /**
     * Returns the required body fields for the UpdateContactInput.
     *
     * @return list<string> The required request body fields.
     */
    #[Override]
    protected function requiredBodyFields(): array
    {
        return [];
    }
}
