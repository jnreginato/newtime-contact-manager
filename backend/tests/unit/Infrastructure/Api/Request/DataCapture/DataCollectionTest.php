<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\DataCapture;

use App\UnitTestCase;

/**
 * Feature: DataCollection
 *
 * This class tests the functionality of the DataCollection class,
 * ensuring that it correctly stores, retrieves, and manages data.
 */
final class DataCollectionTest extends UnitTestCase
{
    /**
     * Scenario: Remembering a value should store it in the collection
     *
     * Given a DataCollection instance,
     * When a value is remembered,
     * Then it should be stored in the collection.
     */
    public function testRememberStoresValue(): void
    {
        $collection = new DataCollection();

        $result = $collection->remember('foo', 'bar');

        self::assertSame(['foo' => 'bar'], $collection->get());
        self::assertSame($collection, $result); // chaining
    }

    /**
     * Scenario: Remembering multiple values should store them in the collection
     *
     * Given a DataCollection instance,
     * When multiple values are remembered,
     * Then they should all be stored in the collection.
     */
    public function testRememberOverwritesValue(): void
    {
        $collection = new DataCollection();
        $collection->remember('key', 'value1')->remember('key', 'value2');

        self::assertSame(['key' => 'value2'], $collection->get());
    }

    /**
     * Scenario: Getting values should return the stored data
     *
     * Given a DataCollection instance with stored values,
     * When get is called,
     * Then it should return the correct data.
     */
    public function testGetReturnsEmptyArrayInitially(): void
    {
        $collection = new DataCollection();

        self::assertSame([], $collection->get());
    }

    /**
     * Scenario: Count should return the number of stored items
     *
     * Given a DataCollection instance with multiple items,
     * When count is called,
     * Then it should return the correct number of items.
     */
    public function testCountReturnsCorrectValue(): void
    {
        $collection = new DataCollection();
        $collection->remember('a', 1)->remember('b', 2)->remember('c', 3);

        self::assertCount(3, $collection);
        self::assertSame(3, $collection->count());
    }

    /**
     * Scenario: Clearing the collection should remove all items
     *
     * Given a DataCollection instance with stored items,
     * When clear is called,
     * Then it should empty the collection.
     */
    public function testClearEmptiesCollection(): void
    {
        $collection = new DataCollection();
        $collection->remember('x', 100)->remember('y', 200);

        $result = $collection->clear();

        self::assertSame([], $collection->get());
        self::assertSame($collection, $result); // chaining
        self::assertSame(0, $collection->count());
    }
}
