<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Validation;

use Throwable;

/**
 * Interface for input validation.
 *
 * This interface defines a method for validating input values.
 * It is intended to be implemented by classes that perform validation logic.
 */
interface InputValidatorInterface
{
    /**
     * Validates the given value against the defined validation rules.
     *
     * This method should be implemented to perform validation on the provided
     * value and throw an exception if validation fails.
     *
     * @param mixed $value The value to validate.
     * @throws Throwable If validation fails.
     */
    public function validate(mixed $value): void;
}
