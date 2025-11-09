<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Input;

use App\Application\Message\Command\DeleteContactCommand;
use App\Infrastructure\Api\Request\CommandInput;
use Override;

/**
 * Class representing the input data for deleting a contact.
 */
final class DeleteContactInput extends CommandInput
{
    /**
     * Converts the input data to a DeleteContactCommand.
     *
     * @return DeleteContactCommand The command to delete a contact.
     */
    #[Override]
    public function toCommand(): DeleteContactCommand
    {
        return DeleteContactCommand::fromArray($this->data);
    }

    /**
     * Returns the allowed body fields for the DeleteContactInput.
     *
     * @return list<string> The allowed request body fields.
     */
    #[Override]
    protected function allowedBodyFields(): array
    {
        return [];
    }

    /**
     * Returns the required body fields for the DeleteContactInput.
     *
     * @return list<string> The required request body fields.
     */
    #[Override]
    protected function requiredBodyFields(): array
    {
        return [];
    }
}
