<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use Override;

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
final readonly class ApiError implements ApiErrorInterface
{
    /**
     * Constructs an ApiError instance.
     *
     * @param string|null $status HTTP status code (e.g., "404").
     * @param string|null $code Application-specific error code (e.g., "not_found").
     * @param string|null $title Short, human-readable summary of the problem.
     * @param string|null $detail Detailed explanation of the error.
     * @param ErrorSource|null $source Additional information about the error source.
     */
    public function __construct(
        private ?string $status = null,
        private ?string $code = null,
        private ?string $title = null,
        private ?string $detail = null,
        private ?array $source = null,
    ) {
    }

    /**
     * Returns the HTTP status code associated with the error.
     *
     * @return string|null The status code, or null if not applicable.
     */
    #[Override]
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Returns the application-specific error code.
     *
     * @return string|null The error code, or null if not applicable.
     */
    #[Override]
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Returns a short, human-readable summary of the problem.
     *
     * @return string|null The title, or null if not applicable.
     */
    #[Override]
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Returns a detailed explanation of the error.
     *
     * @return string|null The detail description, or null if not applicable.
     */
    #[Override]
    public function getDetail(): ?string
    {
        return $this->detail;
    }

    /**
     * Returns additional information about the source of the error.
     *
     * This method should return an array that provides context about where
     * the error occurred, such as a pointer to the specific part of the
     * request that caused the error.
     *
     * @return ErrorSource|null The source information, or null if not applicable.
     */
    #[Override]
    public function getSource(): ?array
    {
        return $this->source;
    }
}
