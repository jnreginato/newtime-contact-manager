<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\ContactStatus;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\FirstName;
use App\Domain\ValueObject\LastName;
use App\UnitTestCase;
use DateTimeImmutable;
use Psr\Clock\ClockInterface;

/**
 * Feature: ContactTest
 *
 * Tests the Contact entity behavior: registration, rename, change email, delete
 * and restore.
 */
final class ContactTest extends UnitTestCase
{
    /**
     * Scenario: Register a new contact.
     *
     * Given valid FirstName, LastName and Email value objects,
     * When registering a contact,
     * Then the contact should contain the provided values and the default status should be Active.
     */
    public function testRegisterCreatesContact(): void
    {
        $contact = Contact::register(
            new FirstName('John'),
            new LastName('Doe'),
            new Email('john.doe@example.com')
        );

        self::assertSame('John', $contact->firstName());
        self::assertSame('Doe', $contact->lastName());
        self::assertSame('john.doe@example.com', $contact->email());
        self::assertSame(ContactStatus::Active, $contact->status());
        self::assertNull($contact->deletedAt());
        self::assertSame('', $contact->id()); // id is null by default and cast to empty string
    }

    /**
     * Scenario: Rename contact with new names
     *
     * Given an existing contact and new FirstName and LastName,
     * When calling rename with non-null values,
     * Then the contact's firstName and lastName should be updated.
     */
    public function testRenameUpdatesNames(): void
    {
        $contact = Contact::register(
            new FirstName('Alice'),
            new LastName('Smith'),
            new Email('alice.smith@example.com')
        );

        $contact->rename(new FirstName('Alicia'), new LastName('Johnson'));

        self::assertSame('Alicia', $contact->firstName());
        self::assertSame('Johnson', $contact->lastName());
    }

    /**
     * Scenario: Rename contact with null values keeps current names
     *
     * Given an existing contact,
     * When calling rename with null for one or both parameters,
     * Then the contact should keep the existing values for null parameters.
     */
    public function testRenameWithNullKeepsExisting(): void
    {
        $contact = Contact::register(
            new FirstName('Tom'),
            new LastName('Brown'),
            new Email('tom.brown@example.com')
        );

        $contact->rename();
        self::assertSame('Tom', $contact->firstName());
        self::assertSame('Brown', $contact->lastName());

        $newFirst = new FirstName('Thomas');

        $contact->rename($newFirst);
        self::assertSame('Thomas', $contact->firstName());
        self::assertSame('Brown', $contact->lastName());
    }

    /**
     * Scenario: Change email
     *
     * Given an existing contact and a new Email value object,
     * When calling changeEmail,
     * Then the contact's email should be updated.
     */
    public function testChangeEmailUpdatesEmail(): void
    {
        $contact = Contact::register(
            new FirstName('Sam'),
            new LastName('Green'),
            new Email('sam.green@example.com')
        );

        $newEmail = new Email('sam.green+updated@example.com');

        $contact->changeEmail($newEmail);

        self::assertSame('sam.green+updated@example.com', $contact->email());
    }

    /**
     * Scenario: Delete contact sets status to Deleted and records deletedAt
     *
     * Given an active contact and a clock returning a specific DateTimeImmutable,
     * When calling delete,
     * Then the contact status should be Deleted and deletedAt should be set to the clock time.
     */
    public function testDeleteSetsDeletedAtAndStatus(): void
    {
        $contact = Contact::register(
            new FirstName('Mary'),
            new LastName('Lee'),
            new Email('mary.lee@example.com')
        );

        $clock = $this->createMock(ClockInterface::class);
        $now = new DateTimeImmutable('2020-01-01T12:00:00+00:00');
        $clock->method('now')->willReturn($now);

        $contact->delete($clock);

        self::assertSame(ContactStatus::Deleted, $contact->status());
        self::assertSame($now, $contact->deletedAt());
    }

    /**
     * Scenario: Delete called twice does not change deletedAt
     *
     * Given a contact already deleted at time A,
     * When calling delete again with a clock returning time B,
     * Then deletedAt should remain time A.
     */
    public function testDeleteWhenAlreadyDeletedDoesNothing(): void
    {
        $contact = Contact::register(
            new FirstName('Lara'),
            new LastName('Croft'),
            new Email('lara.croft@example.com')
        );

        $clockA = $this->createMock(ClockInterface::class);
        $timeA = new DateTimeImmutable('2021-05-01T10:00:00+00:00');
        $clockA->method('now')->willReturn($timeA);

        $contact->delete($clockA);

        $clockB = $this->createMock(ClockInterface::class);
        $timeB = new DateTimeImmutable('2022-06-01T10:00:00+00:00');
        $clockB->method('now')->willReturn($timeB);

        $contact->delete($clockB);

        self::assertSame($timeA, $contact->deletedAt());
        self::assertSame(ContactStatus::Deleted, $contact->status());
    }

    /**
     * Scenario: Restore contact clears deletedAt and sets status to Active
     *
     * Given a deleted contact,
     * When calling restore,
     * Then the contact should be Active and deletedAt should be null.
     */
    public function testRestoreSetsActiveAndClearsDeletedAt(): void
    {
        $contact = Contact::register(
            new FirstName('Nina'),
            new LastName('Hart'),
            new Email('nina.hart@example.com')
        );

        $clock = $this->createMock(ClockInterface::class);
        $now = new DateTimeImmutable('2023-03-03T09:00:00+00:00');
        $clock->method('now')->willReturn($now);

        $contact->delete($clock);
        self::assertSame(ContactStatus::Deleted, $contact->status());
        self::assertSame($now, $contact->deletedAt());

        $contact->restore();

        self::assertSame(ContactStatus::Active, $contact->status());
        self::assertNull($contact->deletedAt());
    }

    /**
     * Scenario: Restore when already active, does nothing.
     *
     * Given an active contact,
     * When calling restore,
     * Then status remains Active and deletedAt stays null.
     */
    public function testRestoreWhenActiveDoesNothing(): void
    {
        $contact = Contact::register(
            new FirstName('Eve'),
            new LastName('Adams'),
            new Email('eve.adams@example.com')
        );

        $contact->restore();

        self::assertSame(ContactStatus::Active, $contact->status());
        self::assertNull($contact->deletedAt());
    }
}
