<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Infrastructure\Api\Response\HttpStatusCode;
use App\UnitTestCase;
use RuntimeException;

/**
 * Feature: DomainResourceNotFoundExceptionTest.
 *
 * Tests the DomainResourceNotFoundException behavior when constructed with
 * different values and previous throwable.
 */
final class DomainResourceNotFoundExceptionTest extends UnitTestCase
{
    /**
     * Scenario: Construct with integer value.
     *
     * Given an integer identifier,
     * When creating the DomainResourceNotFoundException,
     * Then the message must include the integer and the code must be HttpStatusCode::NotFound.
     */
    public function testConstructsWithIntValue(): void
    {
        $exception = new DomainResourceNotFoundException(123);

        self::assertSame('The resource identified by <123> was not found', $exception->getMessage());
        self::assertSame(HttpStatusCode::NotFound->value, $exception->getCode());
        self::assertNull($exception->getPrevious());
    }

    /**
     * Scenario: Construct with string value.
     *
     * Given a string identifier,
     * When creating the DomainResourceNotFoundException,
     * Then the message must include the string and the code must be HttpStatusCode::NotFound.
     */
    public function testConstructsWithStringValue(): void
    {
        $exception = new DomainResourceNotFoundException('my-resource');

        self::assertSame('The resource identified by <my-resource> was not found', $exception->getMessage());
        self::assertSame(HttpStatusCode::NotFound->value, $exception->getCode());
        self::assertNull($exception->getPrevious());
    }

    /**
     * Scenario: Construct with null value and previous throwable.
     *
     * Given a null identifier and a previous throwable,
     * When creating the DomainResourceNotFoundException,
     * Then the message must include empty angle brackets and previous must be preserved.
     */
    public function testConstructsWithNullValueAndPrevious(): void
    {
        $previous = new RuntimeException('previous error');
        $exception = new DomainResourceNotFoundException(null, $previous);

        self::assertSame('The resource identified by <> was not found', $exception->getMessage());
        self::assertSame(HttpStatusCode::NotFound->value, $exception->getCode());
        self::assertSame($previous, $exception->getPrevious());
    }
}
