<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\ContactStatus;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\FirstName;
use App\Domain\ValueObject\LastName;
use App\Infrastructure\Persistence\Doctrine\Entity\Timestampable;
use App\Infrastructure\Persistence\Doctrine\Repository\DoctrineContactRepository;
use App\Infrastructure\Persistence\Doctrine\Types\CustomTypes;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Override;
use Psr\Clock\ClockInterface;

/**
 * This class represents a Contact entity in the system.
 *
 * @SuppressWarnings("PHPMD")
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[ORM\Entity(repositoryClass: DoctrineContactRepository::class)]
#[ORM\Table(name: 'contacts')]
#[ORM\HasLifecycleCallbacks]
class Contact implements EntityInterface
{
    use Timestampable;

    /**
     * The unique identifier for the contact.
     *
     * This is an auto-generated integer that serves as the primary key in the database.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(name: 'id', type: Types::INTEGER)]
    private ?int $id = null; // @phpstan-ignore property.unusedType

    /**
     * The first name of the contact.
     *
     * This is a string that stores the contact's first name.
     */
    #[ORM\Column(name: 'first_name', type: Types::STRING, length: 100)]
    private string $firstName;

    /**
     * The last name of the contact.
     *
     * This is a string that stores the contact's last name.
     */
    #[ORM\Column(name: 'last_name', type: Types::STRING, length: 100)]
    private string $lastName;

    /**
     * The email address of the contact.
     *
     * This is a string that stores the contact's email address.
     */
    #[ORM\Column(name: 'email', type: Types::STRING, length: 255, unique: true)]
    private string $email;

    /**
     * The date and time when the contact was deleted.
     *
     * This is nullable and can be set to null if the contact has not been deleted.
     */
    #[ORM\Column(name: 'deleted_at', type: CustomTypes::DATETIME_IMMUTABLE_UTC, nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    private ContactStatus $status = ContactStatus::Active;

    /**
     * Constructor for the Contact entity.
     *
     * @param string $firstName The first name of the contact.
     * @param string $lastName The last name of the contact.
     * @param string $email The email address of the contact.
     */
    private function __construct(string $firstName, string $lastName, string $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    /**
     * Registers a new contact with the provided details.
     *
     * @param FirstName $firstName The first name of the contact.
     * @param LastName $lastName The last name of the contact.
     * @param Email $email The email address of the contact.
     * @return self A new instance of the Contact class.
     */
    public static function register(FirstName $firstName, LastName $lastName, Email $email): self
    {
        return new self((string) $firstName, (string) $lastName, (string) $email);
    }

    /**
     * Renames the contact by updating the first name and last name.
     *
     * @param FirstName|null $newFirstName The new first name of the contact.
     * @param LastName|null $newLastName The new last name of the contact.
     */
    public function rename(?FirstName $newFirstName = null, ?LastName $newLastName = null): void
    {
        $this->firstName = $newFirstName !== null
            ? (string) $newFirstName
            : $this->firstName;

        $this->lastName = $newLastName !== null
            ? (string) $newLastName
            : $this->lastName;
    }

    /**
     * Changes the contact's email by updating the email address.
     *
     * @param Email $newEmail The new email address for the contact.
     */
    public function changeEmail(Email $newEmail): void
    {
        $this->email = (string) $newEmail;
    }

    /**
     * Deletes the contact by setting the status to delete and recording the deletion time.
     */
    public function delete(ClockInterface $clock): void
    {
        if ($this->status === ContactStatus::Deleted) {
            return;
        }

        $this->status = ContactStatus::Deleted;
        $this->deletedAt = $clock->now();
        // raise domain event if needed
    }

    /**
     * Restores the contact by setting its status to active and clearing the deletedAt timestamp.
     */
    public function restore(): void
    {
        if ($this->status === ContactStatus::Active) {
            return;
        }

        $this->status = ContactStatus::Active;
        $this->deletedAt = null;
        // raise domain event if needed
    }

    /**
     * Returns the unique identifier of the contact.
     *
     * @return string The ID of the contact
     */
    #[Override]
    public function id(): string
    {
        return (string) $this->id;
    }

    /**
     * Returns the first name of the contact.
     *
     * @return string The first name of the contact.
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * Returns the last name of the contact.
     *
     * @return string The last name of the contact.
     */
    public function lastName(): string
    {
        return $this->lastName;
    }

    /**
     * Returns the email address of the contact.
     *
     * @return string The email address of the contact.
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * Returns the date and time when the contact was deleted.
     *
     * @return DateTimeImmutable|null The date and time when the contact was deleted, or null if not deleted.
     */
    public function deletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    /**
     * Returns the status of the contact.
     *
     * @return ContactStatus The status of the contact.
     */
    public function status(): ContactStatus
    {
        return $this->status;
    }
}
