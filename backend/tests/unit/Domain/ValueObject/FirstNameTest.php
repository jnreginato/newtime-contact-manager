<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainValidationException;
use App\UnitTestCase;

/**
 * Feature: FirstNameTest
 *
 * Tests the FirstName value object for validation and string conversion.
 */
final class FirstNameTest extends UnitTestCase
{
    /**
     * Scenario: Create with a valid first name
     *
     * Given a valid first name
     * When creating a new FirstName instance,
     * Then the value should be stored and returned by __toString
     */
    public function testCanCreateWithValidFirstName(): void
    {
        $firstName = new FirstName('John');

        self::assertSame('John', $firstName->value);
        self::assertSame('John', (string) $firstName);
    }

    /**
     * Scenario: Blank first name throws DomainValidationException
     *
     * Given an empty first name
     * When creating a new FirstName instance,
     * Then it should throw a DomainValidationException with the expected message
     */
    public function testThrowsExceptionOnBlankFirstName(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('The first name is required.');

        new FirstName('');
    }

    /**
     * Scenario: A too short first name throws DomainValidationException
     *
     * Given a first name shorter than the minimum length
     * When creating a new FirstName instance,
     * Then it should throw a DomainValidationException with the expected message
     */
    public function testThrowsExceptionOnTooShortFirstName(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('The first name must be between 2 and 100 characters long.');

        new FirstName('A');
    }

    /**
     * Scenario: Too long first name throws DomainValidationException
     *
     * Given a first name longer than maximum length
     * When creating a new FirstName instance,
     * Then it should throw a DomainValidationException with the expected message
     */
    public function testThrowsExceptionOnTooLongFirstName(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('The first name must be between 2 and 100 characters long.');

        new FirstName(str_repeat('a', 101));
    }
}
