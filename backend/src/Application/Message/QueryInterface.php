<?php

declare(strict_types=1);

namespace App\Application\Message;

/**
 * Interface for query objects in the CQRS pattern.
 *
 * This interface extends the MessageInterface and serves as a marker for query objects.
 * It does not define any additional methods beyond those inherited from MessageInterface.
 */
interface QueryInterface extends MessageInterface
{
}
