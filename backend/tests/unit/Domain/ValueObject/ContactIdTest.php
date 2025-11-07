<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainValidationException;
use App\UnitTestCase;

/**
 * Feature: ContactIdTest
 *
 * This class tests the ContactId class, ensuring that it correctly
 * handles valid and invalid values.
 */
final class ContactIdTest extends UnitTestCase
{
    /**
     * Scenario: Create ContactId with a valid value.
     *
     * Given a valid ContactId value
     * When creating a new ContactId instance,
     * Then the value should be set correctly
     */
    public function testCanCreateWithValidValue(): void
    {
        $contactId = new ContactId(123);
        self::assertSame(123, $contactId->getValue());
        self::assertSame('123', (string)$contactId);
    }

    /**
     * Scenario: Throws exception on zero value.
     *
     * Given a zero value
     * When creating a new ContactId instance,
     * Then it should throw a DomainValidationException.
     */
    public function testThrowsExceptionOnZeroValue(): void
    {
        $this->expectException(DomainValidationException::class);
        new ContactId(0);
    }

    /**
     * Scenario: Throws exception on negative value.
     *
     * Given a negative value
     * When creating a new ContactId instance,
     * Then it should throw a DomainValidationException.
     */
    public function testThrowsExceptionOnNegativeValue(): void
    {
        $this->expectException(DomainValidationException::class);
        new ContactId(-5);
    }

    /**
     * Scenario: Test toString method
     *
     * Given a ContactId instance
     * When calling the toString method,
     * Then it should return the value as a string
     */
    public function testToStringReturnsValue(): void
    {
        $contactId = new ContactId(42);
        self::assertSame('42', (string)$contactId);
    }
}
