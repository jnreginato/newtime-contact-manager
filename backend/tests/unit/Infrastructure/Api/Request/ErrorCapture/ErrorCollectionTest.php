<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\ErrorCapture;

use InvalidArgumentException;
use App\Infrastructure\Api\Exception\ApiErrorInterface;
use App\UnitTestCase;
use PHPUnit\Framework\MockObject\Exception;
use stdClass;

use function assert;

/**
 * Feature: ErrorCollection unit tests.
 *
 * This class tests the ErrorCollection class, which implements the
 * ErrorCollectionInterface.
 * It verifies the functionality of adding, retrieving, clearing, and counting
 * errors, as well as the implementation of the Countable, IteratorAggregate,
 * and ArrayAccess interfaces.
 */
final class ErrorCollectionTest extends UnitTestCase
{
    /**
     * Creates a fake ApiErrorInterface for testing purposes.
     *
     * This method returns a mock object that simulates an API error.
     *
     * @param string $title The title of the error, default is 'Error'.
     * @return ApiErrorInterface A mock object representing an API error.
     * @throws Exception If the mock creation fails.
     */
    private function createFakeError(string $title = 'Error'): ApiErrorInterface
    {
        $mock = $this->createMock(ApiErrorInterface::class);
        assert($mock instanceof ApiErrorInterface);

        $mock->method('getStatus')->willReturn('400');
        $mock->method('getCode')->willReturn('bad_request');
        $mock->method('getTitle')->willReturn($title);
        $mock->method('getDetail')->willReturn('This is a fake error for testing purposes.');
        $mock->method('getSource')->willReturn(['pointer' => '/data/attributes/title']);

        return $mock;
    }

    /**
     * Feature: Adding and retrieving errors from the collection
     *
     * Given an ErrorCollection instance,
     * When an error is added,
     * Then it can be retrieved from the collection.
     *
     * @throws Exception If the mock creation fails
     */
    public function testAddAndGet(): void
    {
        $collection = new ErrorCollection();
        $error = $this->createFakeError();

        $collection->add($error);

        self::assertSame([$error], $collection->get());
    }

    /**
     * Feature: Clearing the collection
     *
     * Given an ErrorCollection instance with errors,
     * When the collection is cleared,
     * Then it should be empty.
     *
     * @throws Exception If the mock creation fails
     */
    public function testClearResetsCollection(): void
    {
        $collection = new ErrorCollection();
        $error = $this->createFakeError();

        $collection->add($error);
        $collection->clear();

        self::assertSame([], $collection->get());
    }

    /**
     * Feature: Countable interface implementation
     *
     * Given an ErrorCollection instance,
     * When errors are added,
     * Then the count method should return the correct number of errors.
     *
     * @throws Exception If the mock creation fails
     */
    public function testCount(): void
    {
        $collection = new ErrorCollection();
        $collection->add($this->createFakeError('E1'));
        $collection->add($this->createFakeError('E2'));

        self::assertSame(2, $collection->count());
        self::assertCount(2, $collection);
    }

    /**
     * Feature: IteratorAggregate interface implementation
     *
     * Given an ErrorCollection instance,
     * When errors are added,
     * Then the getIterator method should return an iterator over the errors.
     *
     * @throws Exception If the mock creation fails
     */
    public function testGetIterator(): void
    {
        $collection = new ErrorCollection();

        $error1 = $this->createFakeError('E1');
        $error2 = $this->createFakeError('E2');

        $collection->add($error1)->add($error2);

        $errors = iterator_to_array($collection->getIterator());

        self::assertSame([$error1, $error2], $errors);
    }

    /**
     * Feature: ArrayAccess interface implementation
     *
     * Given an ErrorCollection instance,
     * When errors are added,
     * Then the collection should support array access methods.
     *
     * @throws Exception If the mock creation fails
     */
    public function testOffsetExists(): void
    {
        $collection = new ErrorCollection();
        $error = $this->createFakeError();

        $collection->add($error);

        self::assertTrue($collection->offsetExists(0));
        self::assertFalse($collection->offsetExists(1));
        self::assertTrue(isset($collection[0]));
        self::assertFalse(isset($collection[1]));
    }

    /**
     * Feature: ArrayAccess interface implementation for getting an error by offset
     *
     * Given an ErrorCollection instance,
     * When an error is added,
     * Then it can be accessed using array syntax.
     *
     * @throws Exception If the mock creation fails
     */
    public function testOffsetGet(): void
    {
        $collection = new ErrorCollection();
        $error = $this->createFakeError();
        $collection->offsetSet(null, $error);

        self::assertSame($error, $collection[0]);
        self::assertNull($collection[1]);
    }

    /**
     * Feature: ArrayAccess interface implementation for setting an error by offset
     *
     * Given an ErrorCollection instance,
     * When an error is set at a specific offset,
     * Then it can be retrieved using that offset.
     *
     * @throws Exception If the mock creation fails
     */
    public function testOffsetSetWithExplicitOffset(): void
    {
        $collection = new ErrorCollection();
        $error = $this->createFakeError();

        $collection[5] = $error;

        self::assertSame($error, $collection[5]);
    }

    /**
     * Feature: ArrayAccess interface implementation for setting an error without an offset
     *
     * Given an ErrorCollection instance,
     * When an error is added without specifying an offset,
     * Then it should be added at the next available index.
     *
     * @throws Exception If the mock creation fails
     */
    public function testOffsetSetWithoutOffset(): void
    {
        $collection = new ErrorCollection();
        $error = $this->createFakeError();

        $collection[] = $error;

        self::assertSame($error, $collection[0]);
    }

    /**
     * Feature: ArrayAccess interface implementation for unsetting an error by offset
     *
     * Given an ErrorCollection instance,
     * When an error is unset at a specific offset,
     * Then it should no longer exist at that offset.
     *
     * @throws Exception If the mock creation fails
     */
    public function testOffsetUnset(): void
    {
        $collection = new ErrorCollection();
        $error = $this->createFakeError();

        $collection->add($error);
        $collection->add($error);
        unset($collection[0]);
        $collection->offsetUnset(1);

        self::assertCount(0, $collection);
    }

    /**
     * Feature: offsetGet must throw exception if offset is not an integer
     *
     * Given an ErrorCollection instance,
     * When trying to get an error using a non-integer offset,
     * Then it should throw an InvalidArgumentException.
     */
    public function testOffsetGetThrowsIfOffsetIsNotInt(): void
    {
        $collection = new ErrorCollection();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Offset must be an integer.');

        $collection->offsetGet('not-an-int');
    }

    /**
     * Feature: offsetSet must throw exception if value is not ApiErrorInterface
     *
     * Given an ErrorCollection instance,
     * When trying to set a value that is not an instance of ApiErrorInterface,
     * Then it should throw an InvalidArgumentException.
     */
    public function testOffsetSetThrowsIfValueIsInvalid(): void
    {
        $collection = new ErrorCollection();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be an instance of ApiErrorInterface.');

        // @phpstan-ignore-next-line
        $collection->offsetSet(0, new stdClass());
    }

    /**
     * Feature: offsetSet must throw exception if offset is not an integer
     *
     * Given an ErrorCollection instance,
     * When trying to set an error using a non-integer offset,
     * Then it should throw an InvalidArgumentException.
     *
     * @throws Exception If the mock creation fails
     */
    public function testOffsetSetThrowsIfOffsetIsNotInt(): void
    {
        $collection = new ErrorCollection();
        $error = $this->createFakeError();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Offset must be an integer.');

        // @phpstan-ignore-next-line
        $collection->offsetSet('invalid-offset', $error);
    }
}
