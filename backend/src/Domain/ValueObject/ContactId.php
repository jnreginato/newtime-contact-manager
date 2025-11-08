<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainValidationException;
use Assert\Assertion;
use Override;
use Stringable;
use Throwable;

/**
 * Value object representing a contact identifier.
 */
final readonly class ContactId implements Stringable
{
    /**
     * Constructor for the UserId value object.
     *
     * This class encapsulates a user identifier and ensures that the value is
     * a positive integer.
     *
     * @param int $value The identifier value (must be a positive integer).
     * @throws DomainValidationException if the value is not a positive integer.
     */
    public function __construct(public int $value)
    {
        try {
            Assertion::greaterThan($this->value, 0, 'User ID must be a positive integer.');
        } catch (Throwable $exception) {
            throw new DomainValidationException($exception->getMessage());
        }
    }

    /**
     * Returns the positive integer identifier value.
     *
     * @return int The positive integer identifier value.
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Returns the string representation of the identifier value.
     *
     * This method allows the UserId object to be used as a string,
     * which is useful for logging or displaying the identifier.
     *
     * @return string The string representation of the identifier value.
     */
    #[Override]
    public function __toString(): string
    {
        return (string) $this->value;
    }
}
