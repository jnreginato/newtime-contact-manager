<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

use Override;

use function is_int;
use function is_string;

/**
 * Abstract base class for validated input data.
 *
 * This class provides a foundation for handling validated input data in API
 * requests.
 *
 * @phpstan-type Data array{
 *     resourceId: string|int|null, // The identity of the resource being queried.
 *    ...
 *  }
 */
abstract class Input implements InputInterface
{
    /**
     * The identity of the resource being queried.
     */
    private readonly string | int | null $resourceId;

    /**
     * Constructor to initialize the validated input.
     *
     * @param Data $data The data to initialize the input with.
     */
    public function __construct(public readonly array $data)
    {
        $value = $data['resourceId'] ?? null;
        $this->resourceId = is_int($value) || is_string($value)
            ? $value
            : null;
    }

    /**
     * Returns the resource ID.
     *
     * This method returns the identity of the resource being queried.
     *
     * @return string|int|null The resource ID, which can be a string, a numeric value or null.
     */
    #[Override]
    public function getResourceId(): string | int | null
    {
        return $this->resourceId;
    }
}
