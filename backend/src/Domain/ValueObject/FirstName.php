<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainValidationException;
use Assert\Assertion;
use Throwable;

/**
 * Value object representing a first name.
 *
 * This class encapsulates a first name and ensures that the value is not blank
 * and has a length between 2 and 100 characters upon instantiation.
 */
final readonly class FirstName
{
    /**
     * Constructor for the FirstName value object.
     *
     * This class encapsulates a first name and ensures that the value is not blank
     * and has a length between 2 and 100 characters upon instantiation.
     *
     * @param string $value The first name to validate.
     * @throws DomainValidationException if the first name is blank or does not meet length requirements.
     */
    public function __construct(public string $value)
    {
        try {
            Assertion::notBlank($this->value, 'The first name is required.');
            Assertion::betweenLength($this->value, 2, 100, 'The first name must be between 2 and 100 characters long.');
        } catch (Throwable $exception) {
            throw new DomainValidationException($exception->getMessage());
        }
    }

    /**
     * Returns the first name.
     *
     * @return string The first name.
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
