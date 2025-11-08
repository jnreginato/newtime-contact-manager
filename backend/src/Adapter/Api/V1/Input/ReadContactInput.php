<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Input;

use App\Application\Message\Query\ReadContactQuery;
use App\Infrastructure\Api\Request\QueryInput;
use Override;

/**
 * Class representing the input data for reading a contact.
 */
final class ReadContactInput extends QueryInput
{
    /**
     * Converts the input data to a ReadContactInput.
     *
     * @return ReadContactQuery The command to read a contact.
     */
    #[Override]
    public function toQuery(): ReadContactQuery
    {
        return ReadContactQuery::fromArray($this->data);
    }
}
