<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

/**
 * Interface ApiErrorInterface.
 *
 * This interface defines the structure for API error responses.
 * It includes methods to retrieve various details about the error, such as
 * status code, error code, title, detail, and source information.
 *
 * @phpstan-type ErrorSource array{
 *     pointer?: string,
 *     parameter?: string,
 * }
 */
interface ApiErrorInterface
{
    public const string SOURCE_POINTER = 'pointer';
    public const string SOURCE_PARAMETER = 'parameter';

    /**
     * Get the HTTP status code associated with the error.
     *
     * @return string|null The status code, or null if not applicable.
     */
    public function getStatus(): ?string;

    /**
     * Get the error code.
     *
     * @return string|null The error code, or null if not applicable.
     */
    public function getCode(): ?string;

    /**
     * Get the title of the error.
     *
     * @return string|null The title, or null if not applicable.
     */
    public function getTitle(): ?string;

    /**
     * Get a detailed description of the error.
     *
     * @return string|null The detail description, or null if not applicable.
     */
    public function getDetail(): ?string;

    /**
     * Get the source of the error.
     *
     * This method should return an array that provides additional context about
     * where the error occurred, such as a pointer to the specific part of the
     * request that caused the error.
     *
     * @return ErrorSource|null The source information, or null if not applicable.
     */
    public function getSource(): ?array;
}
