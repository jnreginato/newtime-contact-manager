<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Infrastructure\Api\Response\HttpStatusCode;
use App\UnitTestCase;
use RuntimeException;

/**
 * Feature: DomainDuplicatedResourceExceptionTest.
 *
 * Tests the DomainDuplicatedResourceException class behavior.
 */
final class DomainDuplicatedResourceExceptionTest extends UnitTestCase
{
    /**
     * Scenario: Construct with default code.
     *
     * Given a duplicated resource key,
     * When creating the exception without previous and default code,
     * Then the message should include the key and code should be HttpStatusCode::Conflict value.
     */
    public function testConstructsWithDefaultCode(): void
    {
        $exception = new DomainDuplicatedResourceException('my-key');

        self::assertSame('The resource with unique-key <my-key> is duplicated', $exception->getMessage());
        self::assertSame(HttpStatusCode::Conflict->value, $exception->getCode());
        self::assertNull($exception->getPrevious());
    }

    /**
     * Scenario: Construct with custom code and previous.
     *
     * Given a duplicated resource key, a custom HTTP code and a previous throwable,
     * When creating the exception with those parameters,
     * Then the exception should carry the provided code and previous throwable.
     */
    public function testConstructsWithCustomCodeAndPrevious(): void
    {
        $previous = new RuntimeException('previous error');
        $exception = new DomainDuplicatedResourceException('another-key', 422, $previous);

        self::assertSame('The resource with unique-key <another-key> is duplicated', $exception->getMessage());
        self::assertSame(422, $exception->getCode());
        self::assertSame($previous, $exception->getPrevious());
    }
}
