<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\DataCapture;

use Countable;

/**
 * Interface DataCollectionInterface
 *
 * This interface defines methods for aggregating data in a structured way.
 * It allows remembering values by keys, retrieving all remembered values,
 * and clearing the remembered values.
 */
interface DataCollectionInterface extends Countable
{
    /**
     * Remember a value with a specific key.
     *
     * @param string $key The key to remember the value by.
     * @param mixed $value The value to remember.
     * @return self The instance of the DataCollectionInterface for method chaining.
     */
    public function remember(string $key, mixed $value): self;

    /**
     * Get all remembered values as an associative array.
     *
     * @return array<string, mixed> An associative array of remembered values.
     */
    public function get(): array;

    /**
     * Clear all remembered values.
     *
     * @return self The instance of the DataCollectionInterface for method chaining.
     */
    public function clear(): self;
}
