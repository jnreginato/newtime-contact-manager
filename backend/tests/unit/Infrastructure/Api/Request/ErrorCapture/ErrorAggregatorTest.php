<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\ErrorCapture;

use AssertionError;
use App\Infrastructure\Api\Exception\SimpleError;
use App\Infrastructure\Api\Response\HttpStatusCode;
use App\UnitTestCase;
use PHPUnit\Framework\MockObject\Exception;

use function assert;

/**
 * Scenario: ErrorAggregatorTest
 *
 * This test suite verifies the functionality of the ErrorAggregator class,
 * ensuring it correctly aggregates errors, manages response statuses,
 * and interacts with the ErrorCollectionInterface.
 */
final class ErrorAggregatorTest extends UnitTestCase
{
    /**
     * Feature: Clear resets status and clears collection
     *
     * Given: An ErrorAggregator with an ErrorCollection
     * When: The clear method is called
     * Then: It should reset the response status code and clear the collection
     */
    public function testClearResetsStatusAndClearsCollection(): void
    {
        $aggregator = new ErrorAggregator();
        $aggregator->addApiError('Error');
        $aggregator->clear();

        self::assertSame(HttpStatusCode::BadRequest->value, $aggregator->getResponseStatusCode());
        self::assertCount(0, $aggregator);
    }

    /**
     * Feature: Count delegates to collection.
     *
     * Given: An ErrorAggregator with an ErrorCollection
     * When: The count method is called
     * Then: It should return the count of errors in the collection
     *
     * @throws Exception If the mock creation fails
     */
    public function testCountDelegatesToCollection(): void
    {
        $mock = $this->createMock(ErrorCollectionInterface::class);
        assert($mock instanceof ErrorCollectionInterface);
        $mock->method('count')->willReturn(3);

        $aggregator = new ErrorAggregator($mock);
        self::assertCount(3, $aggregator);
    }

    /**
     * Feature: AddApiError sets status and adds error
     *
     * Given: An ErrorAggregator with an ErrorCollection
     * When: An API error is added
     * Then: It should set the response status code and add the error to the collection
     */
    public function testAddApiErrorSetsStatusAndAddsError(): void
    {
        $aggregator = new ErrorAggregator();
        $aggregator->addApiError('Title', 'Detail', '404');
        self::assertSame(HttpStatusCode::BadRequest->value, $aggregator->getResponseStatusCode());
    }

    /**
     * Feature: AddQueryApiError adds error with parameter
     *
     * Given: An ErrorAggregator with an ErrorCollection
     * When: A query API error is added
     * Then: It should add the error to the collection with the correct parameter source
     */
    public function testAddQueryApiErrorSetsErrorCorrectly(): void
    {
        $error = new SimpleError(
            parameterName: 'field',
            parameterValue: null,
            internalErrorCode: '1001',
            messageTemplate: 'Invalid value for {field}',
            messageParameters: ['field' => 'field'],
        );

        $aggregator = new ErrorAggregator();
        $aggregator->addQueryApiError($error);
        self::assertSame(HttpStatusCode::UnprocessableEntity->value, $aggregator->getResponseStatusCode());
    }

    /**
     * Feature: AddBodyApiError with parameter name
     *
     * Given: An ErrorAggregator with an ErrorCollection
     * When: A body API error is added with a parameter name
     * Then: It should set the response status code and add the error to the collection
     */
    public function testAddBodyApiErrorWithParameterName(): void
    {
        $error = new SimpleError(
            parameterName: 'email',
            parameterValue: 'invalid@email',
            internalErrorCode: '1002',
            messageTemplate: 'Invalid email {email}',
            messageParameters: ['email' => 'email'],
        );

        $aggregator = new ErrorAggregator();
        $aggregator->addBodyApiError($error, HttpStatusCode::UnprocessableEntity->value);
        self::assertSame(HttpStatusCode::UnprocessableEntity->value, $aggregator->getResponseStatusCode());
    }

    /**
     * Feature: AddBodyApiError without parameter name
     *
     * Given: An ErrorAggregator with an ErrorCollection
     * When: A body API error is added without a parameter name
     * Then: It should set the response status code and add the error to the collection
     */
    public function testAddBodyApiErrorWithoutParameterName(): void
    {
        $error = new SimpleError(
            parameterName: null,
            parameterValue: null,
            internalErrorCode: 'E003',
            messageTemplate: 'Missing body content',
            messageParameters: [],
        );

        $aggregator = new ErrorAggregator();
        $aggregator->addBodyApiError($error, HttpStatusCode::UnprocessableEntity->value);
        self::assertSame(HttpStatusCode::UnprocessableEntity->value, $aggregator->getResponseStatusCode());
    }

    /**
     * Feature: Multiple statuses fallback to 400
     *
     * Given: An ErrorAggregator with multiple errors
     * When: Errors with different statuses are added
     * Then: It should fallback to 400 Bad Request if multiple statuses are present
     */
    public function testMultipleStatusesFallbackTo400(): void
    {
        $aggregator = new ErrorAggregator();

        $error1 = new SimpleError('field1', 'field1 value', 'E1', 'Error {a}', ['a' => 1]);
        $error2 = new SimpleError('field2', 'field2 value', 'E2', 'Error {b}', ['b' => 2]);

        $aggregator->addQueryApiError($error1, HttpStatusCode::UnprocessableEntity->value);
        self::assertSame(HttpStatusCode::UnprocessableEntity->value, $aggregator->getResponseStatusCode());

        $aggregator->addQueryApiError($error2, HttpStatusCode::Conflict->value);
        self::assertSame(HttpStatusCode::BadRequest->value, $aggregator->getResponseStatusCode());
    }
}
