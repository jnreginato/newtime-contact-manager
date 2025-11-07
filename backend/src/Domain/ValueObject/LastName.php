<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainValidationException;
use Assert\Assertion;
use Throwable;

/**
 * Value object representing a last name.
 *
 * This class encapsulates a last name and ensures that the value is not blank
 * and has a length between 2 and 100 characters upon instantiation.
 */
final readonly class LastName
{
    /**
     * Constructor for the LastName value object.
     *
     * This class encapsulates a last name and ensures that the value is not blank
     * and has a length between 2 and 100 characters upon instantiation.
     *
     * @param string $value The last name to validate.
     * @throws DomainValidationException if the last name is blank or does not meet length requirements.
     */
    public function __construct(public string $value)
    {
        try {
            Assertion::notBlank($this->value, 'The last name is required.');
            Assertion::betweenLength($this->value, 2, 100, 'The last name must be between 2 and 100 characters long.');
        } catch (Throwable $exception) {
            throw new DomainValidationException($exception->getMessage());
        }
    }

    /**
     * Returns the last name.
     *
     * @return string The last name.
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
