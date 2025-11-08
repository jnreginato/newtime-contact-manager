<?php

declare(strict_types=1);

namespace App\Application\Message;

/**
 * Interface for command objects in the CQRS pattern.
 *
 * This interface defines methods for creating command instances from
 * associative arrays and converting command instances back to associative arrays.
 */
interface CommandInterface extends MessageInterface
{
}
