<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\DataCapture;

use Override;

use function count;

/**
 * Class DataCollection
 *
 * This class implements the DataCollectionInterface and provides a collection
 * for managing remembered values.
 * It allows storing, retrieving, counting, and clearing remembered values.
 */
final class DataCollection implements DataCollectionInterface
{
    /**
     * This array uses string keys to identify each remembered value.
     *
     * @var array<string, mixed> An associative array to store remembered values.
     */
    private array $remembered = [];

    /**
     * Remember a value with a specific key.
     *
     * This method stores a value in the internal array using the provided key.
     *
     * @param string $key The key to remember the value by.
     * @param mixed $value The value to remember.
     * @return self The instance of DataCollection for method chaining.
     */
    #[Override]
    public function remember(string $key, mixed $value): self
    {
        $this->remembered[$key] = $value;

        return $this;
    }

    /**
     * Get all remembered values as an associative array.
     *
     * This method retrieves all stored values in the internal array.
     *
     * @return array<string, mixed> An associative array of remembered values.
     */
    #[Override]
    public function get(): array
    {
        return $this->remembered;
    }

    /**
     * Count the number of remembered values.
     *
     * This method returns the count of all stored values in the internal array.
     *
     * @return int The number of remembered values.
     */
    #[Override]
    public function count(): int
    {
        return count($this->get());
    }

    /**
     * Clear all remembered values.
     *
     * This method resets the internal storage of remembered values to an empty state.
     *
     * @return self The instance of DataAggregator for method chaining.
     */
    #[Override]
    public function clear(): self
    {
        $this->remembered = [];

        return $this;
    }
}
