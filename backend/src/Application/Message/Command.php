<?php

declare(strict_types=1);

namespace App\Application\Message;

/**
 * Abstract base class for commands in the CQRS pattern.
 *
 * This class provides a common structure for all command classes, including a
 * timestamp indicating when the command was created.
 */
abstract readonly class Command extends Message implements CommandInterface
{
}
