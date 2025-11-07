<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\ErrorCapture;

use App\Infrastructure\Api\Exception\ApiErrorInterface;
use ArrayIterator;
use InvalidArgumentException;
use Override;
use Traversable;

use function count;
use function is_int;

/**
 * ErrorCollection is a class that implements a collection of API errors.
 *
 * This class implements the ErrorCollectionInterface and provides a collection
 * for managing API errors.
 * It allows adding, retrieving, clearing, and counting errors.
 *
 * @implements ErrorCollectionInterface<int, ApiErrorInterface>
 */
final class ErrorCollection implements ErrorCollectionInterface
{
    /**
     * An array to hold API errors.
     *
     * This array is used to store all errors added to the collection.
     *
     * @var array<int, ApiErrorInterface> An array of API errors.
     */
    private array $errors = [];

    /**
     * Adds an error to the collection.
     *
     * @param ApiErrorInterface $error The API error to add.
     * @return ErrorCollectionInterface<int, ApiErrorInterface> Returns the current instance for method chaining.
     */
    #[Override]
    public function add(ApiErrorInterface $error): ErrorCollectionInterface
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * Returns all errors in the collection.
     *
     * @return array<int, ApiErrorInterface> An array of API errors.
     */
    #[Override]
    public function get(): array
    {
        return $this->errors;
    }

    /**
     * Clears all errors from the collection.
     *
     * @return ErrorCollectionInterface<int, ApiErrorInterface> Returns the current instance for method chaining.
     */
    #[Override]
    public function clear(): ErrorCollectionInterface
    {
        $this->errors = [];

        return $this;
    }

    /**
     * Returns the number of errors in the collection.
     *
     * @return int The count of errors.
     */
    #[Override]
    public function count(): int
    {
        return count($this->errors);
    }

    /**
     * Returns an iterator for the errors in the collection.
     *
     * @return Traversable<int, ApiErrorInterface> An iterator for the errors.
     */
    #[Override]
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->errors);
    }

    /**
     * Checks if an error exists at the given offset.
     *
     * This method checks if the offset is an integer and if the error exists
     * at that offset in the collection.
     * The offset must be an integer; otherwise, it returns false.
     *
     * @param mixed $offset The offset to check.
     * @return bool True if the error exists, false otherwise.
     */
    #[Override]
    public function offsetExists(mixed $offset): bool
    {
        if (!is_int($offset)) {
            return false;
        }

        return isset($this->errors[$offset]);
    }

    /**
     * Gets the error at the given offset.
     *
     * @param mixed $offset The offset to retrieve.
     * @return ApiErrorInterface|null Returns the error at the offset or null if it does not exist.
     */
    #[Override]
    public function offsetGet(mixed $offset): ?ApiErrorInterface
    {
        if (!is_int($offset)) {
            throw new InvalidArgumentException('Offset must be an integer.');
        }

        return $this->errors[$offset] ?? null;
    }

    /**
     * Sets an error at the given offset.
     *
     * @param mixed $offset The offset to set.
     * @param ApiErrorInterface $value The error to set.
     */
    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof ApiErrorInterface)) {
            throw new InvalidArgumentException('Value must be an instance of ApiErrorInterface.');
        }

        if ($offset === null) {
            $this->add($value);

            return;
        }

        if (!is_int($offset)) {
            throw new InvalidArgumentException('Offset must be an integer.');
        }

        $this->errors[$offset] = $value;
    }

    /**
     * Unsets the error at the given offset.
     *
     * @param mixed $offset The offset to unset.
     */
    #[Override]
    public function offsetUnset(mixed $offset): void
    {
        unset($this->errors[$offset]);
    }
}
