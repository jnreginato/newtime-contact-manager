<?php

declare(strict_types=1);

namespace App\Application\Support;

/**
 * Class representing a change in a value, which can be either present or absent.
 *
 * This class is useful for scenarios where you need to track whether a value
 * has been modified or not.
 *
 * @template-covariant T
 */
final readonly class ValueChange
{
    /**
     * Constructor for the ValueChange class.
     *
     * @param bool $present Indicates whether the value is present (true) or absent (false).
     * @param T $value The value associated with the change, or null if absent.
     */
    public function __construct(public bool $present, public mixed $value)
    {
    }

    /**
     * Creates an absent ValueChange instance.
     *
     * @return self<null> An absent ValueChange instance.
     */
    public static function absent(): self
    {
        return new self(false, null);
    }

    /**
     * Creates a present ValueChange instance with the given value.
     *
     * @template TVal
     * @param TVal $value The value to be wrapped in the ValueChange instance.
     * @return self<TVal> A present ValueChange instance containing the value.
     */
    public static function present(mixed $value): self
    {
        return new self(true, $value);
    }

    /**
     * Creates a ValueChange instance from a value.
     *
     * If the value is null, it returns an absent ValueChange instance.
     * Otherwise, it returns a present ValueChange instance containing the value.
     *
     * @template TVal
     * @param TVal|null $value The value to be wrapped in the ValueChange instance.
     * @return self<TVal|null> A ValueChange instance representing the presence or absence of the value.
     */
    public static function fromValue(mixed $value): self
    {
        return $value === null
            ? self::absent()
            : self::present($value);
    }
}
