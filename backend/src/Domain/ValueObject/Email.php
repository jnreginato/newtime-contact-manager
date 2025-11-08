<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainValidationException;
use Assert\Assertion;
use Throwable;

use function strtolower;

/**
 * Value object representing an email address.
 *
 * This class encapsulates an email address and ensures that the value is a
 * valid email format upon instantiation.
 */
final class Email
{
    /**
     * Constructor for the Email value object.
     *
     * This class encapsulates an email address and ensures that the value is
     * a valid email format upon instantiation.
     *
     * @param string $value The email address to validate.
     * @throws DomainValidationException if the email is not valid or is blank.
     */
    public function __construct(private string $value)
    {
        $this->value = strtolower($value);

        try {
            Assertion::notBlank($this->value, 'Email is required.');
            Assertion::email($this->value, 'Email must be a valid email address.');
        } catch (Throwable $exception) {
            throw new DomainValidationException($exception->getMessage());
        }
    }

    /**
     * Returns the email address.
     *
     * @return string The email address.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns the email address.
     *
     * @return string The email address.
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
