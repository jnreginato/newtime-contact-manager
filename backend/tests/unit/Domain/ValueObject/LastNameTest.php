<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainValidationException;
use App\UnitTestCase;

/**
 * Feature: LastNameTest
 *
 * Tests the LastName value object for validation and string conversion.
 */
final class LastNameTest extends UnitTestCase
{
    /**
     * Scenario: Create with a valid last name
     *
     * Given a valid last name
     * When creating a new LastName instance,
     * Then the value should be stored and returned by __toString
     */
    public function testCanCreateWithValidLastName(): void
    {
        $lastName = new LastName('Doe');

        self::assertSame('Doe', $lastName->value);
        self::assertSame('Doe', (string) $lastName);
    }

    /**
     * Scenario: Blank last name throws DomainValidationException
     *
     * Given an empty last name
     * When creating a new LastName instance,
     * Then it should throw a DomainValidationException with the expected message
     */
    public function testThrowsExceptionOnBlankLastName(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('The last name is required.');

        new LastName('');
    }

    /**
     * Scenario: A too short last name throws DomainValidationException
     *
     * Given a last name shorter than the minimum length,
     * When creating a new LastName instance,
     * Then it should throw a DomainValidationException with the expected message
     */
    public function testThrowsExceptionOnTooShortLastName(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('The last name must be between 2 and 100 characters long.');

        new LastName('A');
    }

    /**
     * Scenario: Too long last name throws DomainValidationException
     *
     * Given a last name longer than the maximum length,
     * When creating a new LastName instance,
     * Then it should throw a DomainValidationException with the expected message
     */
    public function testThrowsExceptionOnTooLongLastName(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('The last name must be between 2 and 100 characters long.');

        new LastName(str_repeat('a', 101));
    }

    /**
     * Scenario: __toString returns the value.
     *
     * Given a LastName instance,
     * When casting it to string,
     * Then it should return the original value.
     */
    public function testToStringReturnsValue(): void
    {
        $lastName = new LastName('Smith');

        self::assertSame('Smith', (string) $lastName);
    }
}
