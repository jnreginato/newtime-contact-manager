<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use Override;

/**
 * Class FakeApiError.
 *
 * This class is a simple implementation of the ApiErrorInterface for testing purposes.
 * It allows the creation of fake API error objects with specified title, detail, and status.
 */
final readonly class FakeApiError implements ApiErrorInterface
{
    /**
     * Constructor for the FakeApiError class.
     *
     * @param string $title The title of the error.
     * @param string $detail A detailed description of the error.
     * @param string $status The HTTP status code associated with the error.
     */
    public function __construct(private string $title, private string $detail, private string $status)
    {
    }

    /**
     * Get the title of the error.
     *
     * @return string The title of the error.
     */
    #[Override]
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get a detailed description of the error.
     *
     * @return string The detailed description of the error.
     */
    #[Override]
    public function getDetail(): string
    {
        return $this->detail;
    }

    /**
     * Get the HTTP status code associated with the error.
     *
     * @return string The HTTP status code.
     */
    #[Override]
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Get the error code.
     *
     * @return string|null Always returns null in this implementation.
     */
    #[Override]
    public function getCode(): ?string
    {
        return null;
    }

    /**
     * Get the source of the error.
     *
     * @return array<array-key, mixed>|null Always returns an empty array in this implementation.
     */
    #[Override]
    public function getSource(): ?array
    {
        return null;
    }
}
