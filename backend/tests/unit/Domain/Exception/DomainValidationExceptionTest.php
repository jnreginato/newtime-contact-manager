<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\UnitTestCase;
use DomainException;
use RuntimeException;

/**
 * Feature: DomainValidationExceptionTest.
 *
 * Tests the DomainValidationException behavior: message, code and previous preservation.
 */
final class DomainValidationExceptionTest extends UnitTestCase
{
    /**
     * Scenario: Construct with message and default values.
     *
     * Given a validation error message,
     * When creating the DomainValidationException without code and previous,
     * Then the message should be preserved, code should be 0 and previous should be null.
     */
    public function testConstructsWithMessageAndDefaultValues(): void
    {
        $exception = new DomainValidationException('Validation failed');

        self::assertSame('Validation failed', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertNull($exception->getPrevious());
        self::assertInstanceOf(DomainException::class, $exception);
        self::assertInstanceOf(DomainValidationException::class, $exception);
    }

    /**
     * Scenario: Construct with custom code and previous.
     *
     * Given a message, custom code and a previous throwable,
     * When creating the DomainValidationException with those parameters,
     * Then the exception should carry the provided code and previous throwable.
     */
    public function testConstructsWithCustomCodeAndPrevious(): void
    {
        $previous = new RuntimeException('previous error');
        $exception = new DomainValidationException('Invalid data', 422, $previous);

        self::assertSame('Invalid data', $exception->getMessage());
        self::assertSame(422, $exception->getCode());
        self::assertSame($previous, $exception->getPrevious());
    }
}
