<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

/**
 * Enum representing the status of a contact.
 *
 * This enum defines the possible statuses for a contact in the system.
 * It includes 'active' for active contacts and 'deleted' for contacts who have been deleted.
 */
enum ContactStatus: string
{
    case Active = 'active';
    case Deleted = 'deleted';
}
