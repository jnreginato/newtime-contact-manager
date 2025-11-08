<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\ErrorCapture;

use App\Infrastructure\Api\Exception\ApiErrorInterface;
use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Interface for a collection of API errors.
 *
 * This interface defines methods for managing a collection of API errors,
 * allowing for adding, retrieving, and clearing errors.
 *
 * @template TKey of int
 * @template TValue of ApiErrorInterface
 * @template-extends ArrayAccess<TKey, TValue>
 * @template-extends IteratorAggregate<TKey, TValue>
 */
interface ErrorCollectionInterface extends IteratorAggregate, Countable, ArrayAccess
{
    /**
     * Adds an API error to the collection.
     *
     * @param ApiErrorInterface $error The API error to add.
     * @return ErrorCollectionInterface<int, ApiErrorInterface> The instance of ErrorCollectionInterface.
     */
    public function add(ApiErrorInterface $error): self;

    /**
     * Retrieves all API errors in the collection.
     *
     * @return array<int, ApiErrorInterface> An array of API errors.
     */
    public function get(): array;

    /**
     * Clears all API errors from the collection.
     *
     * @return ErrorCollectionInterface<int, ApiErrorInterface> The instance of ErrorCollectionInterface.
     */
    public function clear(): self;
}
