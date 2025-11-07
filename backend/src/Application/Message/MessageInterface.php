<?php

declare(strict_types=1);

namespace App\Application\Message;

/**
 * Interface for message objects in the CQRS pattern.
 *
 * This interface defines methods for creating message instances from
 * associative arrays and converting message instances back to associative arrays.
 */
interface MessageInterface
{
    /**
     * Create an instance of the message from an associative array.
     *
     * @param array<string, mixed> $data The input data.
     * @return self An instance of the message.
     */
    public static function fromArray(array $data): self;

    /**
     * Convert the message to an associative array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
