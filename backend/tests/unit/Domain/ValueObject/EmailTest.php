<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\DomainValidationException;
use App\UnitTestCase;

/**
 * Feature: EmailTest
 *
 * Tests the Email value object for validation, lowercasing, and string conversion.
 */
final class EmailTest extends UnitTestCase
{
    /**
     * Scenario: Create with a valid email
     *
     * Given a valid email address
     * When creating a new Email instance,
     * Then the email should be normalized and stored correctly
     */
    public function testCanCreateWithValidEmail(): void
    {
        $email = new Email('TEST@EXAMPLE.COM');

        self::assertSame('test@example.com', $email->getValue());
        self::assertSame('test@example.com', (string)$email);
    }

    /**
     * Scenario: Blank email throws DomainValidationException
     *
     * Given an empty email address
     * When creating a new Email instance,
     * Then it should throw a DomainValidationException
     */
    public function testThrowsExceptionOnBlankEmail(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('Email is required.');

        new Email('');
    }

    /**
     * Scenario: Invalid email format throws DomainValidationException
     *
     * Given an invalid email address
     * When creating a new Email instance,
     * Then it should throw a DomainValidationException
     */
    public function testThrowsExceptionOnInvalidEmailFormat(): void
    {
        $this->expectException(DomainValidationException::class);
        $this->expectExceptionMessage('Email must be a valid email address.');

        new Email('not-an-email');
    }
}
