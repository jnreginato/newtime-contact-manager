<?php

declare(strict_types=1);

namespace App\Infrastructure\Config;

/**
 * ValidatorOptions class.
 *
 * This class is used to define options for validating configuration values.
 * It includes options for specifying whether a configuration key is required,
 * whether the value should not be empty, and the expected type of the value.
 *
 * @SuppressWarnings("BooleanArgumentFlag")
 */
final readonly class ValidatorOptions
{
    /**
     * ValidatorOptions constructor.
     *
     * @param bool $required Indicates if the configuration key is required
     * @param bool $notEmpty Indicates if the configuration value should not be empty
     * @param ConfigTypes|null $type The expected type of the configuration value (e.g., 'string', 'int', etc.)
     */
    public function __construct(
        public bool $required = false,
        public bool $notEmpty = false,
        public ?ConfigTypes $type = null,
    ) {
    }
}
