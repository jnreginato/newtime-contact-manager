<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

/**
 * Interface AuthorizationExceptionInterface
 *
 * This interface represents an authorization-related exception in the API.
 * It extends from ApiExceptionInterface, which is a custom exception for API-related errors.
 */
interface AuthorizationExceptionInterface extends ApiExceptionInterface
{
    /**
     * Get the action that was attempted.
     *
     * @return string The action that was attempted.
     */
    public function getAction(): string;

    /**
     * Get the resource type that was attempted to be accessed.
     *
     * @return string|null The resource type, or null if not applicable.
     */
    public function getResourceType(): ?string;

    /**
     * Get the identity of the resource that was attempted to be accessed.
     *
     * @return int|string|null The resource identity, or null if not applicable.
     */
    public function getResourceIdentity(): int | string | null;

    /**
     * Get any extra parameters that may be relevant to the authorization failure.
     *
     * @return array<array-key, mixed> An associative array of extra parameters.
     */
    public function getExtraParameters(): array;
}
