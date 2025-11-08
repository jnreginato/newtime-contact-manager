<?php

declare(strict_types=1);

namespace App\Application\Message;

use DateTimeImmutable;
use DateTimeZone;

/**
 * Abstract base class for messages in the CQRS pattern.
 *
 * This class provides a common structure for all message classes, including a
 * timestamp indicating when the message was issued.
 */
abstract readonly class Message implements MessageInterface
{
    /**
     * The timestamp indicating when the message was issued.
     */
    // phpcs:ignore SlevomatCodingStandard.Classes.ForbiddenPublicProperty.ForbiddenPublicProperty
    public DateTimeImmutable $issuedAt;

    /**
     * Constructor for the Message class.
     *
     * Sets the issuedAt property to the provided time or the current UTC time if none is provided.
     *
     * @param DateTimeImmutable|null $issuedAt The time the message was issued, or null to use the current time.
     */
    protected function __construct(?DateTimeImmutable $issuedAt = null)
    {
        $this->issuedAt = $issuedAt ?? new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    /**
     * Convert the base properties of the message to an associative array.
     *
     * @return array<string, mixed> The base properties of the message as an associative array.
     */
    final protected function baseToArray(): array
    {
        return [
            'issuedAt' => $this->issuedAt->format(DATE_ATOM),
        ];
    }
}
