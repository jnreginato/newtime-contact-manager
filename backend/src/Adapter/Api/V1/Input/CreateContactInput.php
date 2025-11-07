<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Input;

use App\Application\Message\Command\CreateContactCommand;
use App\Infrastructure\Api\Request\CommandInput;
use Override;

/**
 * Input data for creating a contact.
 *
 * This class represents the input data for creating a contact in the system.
 */
final class CreateContactInput extends CommandInput
{
    /**
     * Converts the input data to a CreateContactCommand.
     *
     * @return CreateContactCommand The command to create a contact.
     */
    #[Override]
    public function toCommand(): CreateContactCommand
    {
        return CreateContactCommand::fromArray($this->data);
    }

    /**
     * Returns the allowed body fields for the CreateContactInput.
     *
     * @return list<string> The allowed request body fields.
     */
    #[Override]
    protected function allowedBodyFields(): array
    {
        return ['firstName', 'lastName', 'email'];
    }

    /**
     * Returns the required body fields for the CreateContactInput.
     *
     * @return list<string> The required request body fields.
     */
    #[Override]
    protected function requiredBodyFields(): array
    {
        return $this->allowedBodyFields();
    }
}
