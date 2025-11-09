<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use ArrayIterator;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorCollectionInterface;
use Override;
use Traversable;

use function count;

/**
 * Class FakeErrorCollection.
 *
 * This class is a simple implementation of the ErrorCollectionInterface for testing purposes.
 * It allows the creation of a collection containing a single fake API error with specified title, detail,
 * and status.
 * It provides methods to manipulate and access the collection of errors.
 *
 * @implements ErrorCollectionInterface<int, ApiErrorInterface>
 */
final class FakeErrorCollection implements ErrorCollectionInterface
{
    /**
     * The collection of API errors.
     *
     * @var list<ApiErrorInterface> The collection of API errors.
     */
    private array $items;

    /**
     * Constructor for the FakeErrorCollection class.
     *
     * @param string $title The title of the error.
     * @param string $detail A detailed description of the error.
     * @param string $status The HTTP status code associated with the error.
     */
    public function __construct(string $title, string $detail, string $status)
    {
        $this->items = [new FakeApiError($title, $detail, $status)];
    }

    /**
     * Get an iterator for the collection of API errors.
     *
     * @return ArrayIterator<int, ApiErrorInterface> An iterator for the collection of API errors.
     */
    #[Override]
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Check if an offset exists in the collection.
     *
     * @param mixed $offset The offset to check.
     * @return bool True if the offset exists, false otherwise.
     */
    #[Override]
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * Get the value at a specific offset in the collection.
     *
     * @param mixed $offset The offset to retrieve.
     * @return ApiErrorInterface|null The API error at the specified offset, or null if it doesn't exist.
     */
    #[Override]
    public function offsetGet(mixed $offset): ?ApiErrorInterface
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * Set a value at a specific offset in the collection.
     *
     * @param mixed $offset The offset to set. If null, the value will be appended to the collection.
     * @param mixed $value The value to set. Must be an instance of ApiErrorInterface.
     */
    #[Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!$value instanceof ApiErrorInterface) {
            return;
        }

        if ($offset === null) {
            $this->items[] = $value;

            return;
        }

        // @phpstan-ignore-next-line
        $this->items[$offset] = $value;
    }

    /**
     * Unset a value at a specific offset in the collection.
     *
     * @param mixed $offset The offset to unset.
     */
    #[Override]
    public function offsetUnset(mixed $offset): void
    {
        // @phpstan-ignore-next-line
        unset($this->items[$offset]);
    }

    /**
     * Get the count of API errors in the collection.
     *
     * @return int The number of API errors in the collection.
     */
    #[Override]
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Add an API error to the collection.
     *
     * @param ApiErrorInterface $error The API error to add.
     * @return ErrorCollectionInterface<int, ApiErrorInterface> The current instance of the ErrorCollectionInterface.
     */
    #[Override]
    public function add(ApiErrorInterface $error): ErrorCollectionInterface
    {
        $this->items[] = $error;

        return $this;
    }

    /**
     * Get all API errors in the collection as an array.
     *
     * @return list<ApiErrorInterface> An array of all API errors in the collection.
     */
    #[Override]
    public function get(): array
    {
        return $this->items;
    }

    /**
     * Clear all API errors from the collection.
     *
     * @return ErrorCollectionInterface<int, ApiErrorInterface> The current instance of the ErrorCollectionInterface.
     */
    #[Override]
    public function clear(): ErrorCollectionInterface
    {
        $this->items = [];

        return $this;
    }
}
