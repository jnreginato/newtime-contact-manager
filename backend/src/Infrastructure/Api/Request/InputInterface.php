<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

/**
 * Interface for input handling in API requests.
 */
interface InputInterface
{
    /**
     * Returns the resource ID.
     *
     * This method returns the identity of the resource being queried.
     *
     * @return string|int|null The resource ID, which can be a string, a numeric value or null.
     */
    public function getResourceId(): string | int | null;
}
