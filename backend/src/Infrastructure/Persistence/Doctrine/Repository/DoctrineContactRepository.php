<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Entity\EntityInterface;
use App\Domain\Entity\Contact;
use App\Domain\Exception\DomainResourceNotFoundException;
use App\Domain\Repository\ContactRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\ContactId;
use Override;
use Stringable;

use function assert;
use function sprintf;

/**
 * DoctrineContactRepository is a Doctrine repository for managing Contact entities.
 */
final class DoctrineContactRepository extends QueryRepository implements ContactRepositoryInterface
{
    /**
     * Returns the alias for the Contact entity in the repository.
     *
     * This method is used to define the alias for the Contact entity when building
     * queries in Doctrine.
     *
     * @return string The alias for the Contact entity.
     */
    #[Override]
    public function getAlias(): string
    {
        return 'contacts';
    }

    /**
     * Retrieves a contact by its identifier.
     *
     * @param ContactId $identifier The identifier of the contact to retrieve.
     * @return Contact|null The contact if found, null otherwise.
     */
    #[Override]
    public function byId(Stringable $identifier): ?Contact
    {
        $contact = $this->find((string) $identifier);

        return $contact instanceof Contact
            ? $contact
            : null;
    }

    /**
     * Loads a contact by ID or throws an exception if not found.
     *
     * @param ContactId $identifier The ID of the contact to be loaded.
     * @return Contact The loaded contact entity.
     * @throws DomainResourceNotFoundException If the contact is not found.
     */
    #[Override]
    public function byIdOrFail(Stringable $identifier): Contact
    {
        $contact = $this->byId($identifier);

        if ($contact === null) {
            throw new DomainResourceNotFoundException(sprintf('Contact with id %s not found', $identifier));
        }

        return $contact;
    }

    /**
     * Retrieves a contact by its email address.
     *
     * This method searches for a contact by their email address and returns the
     * corresponding DomainContact object if found. If no contact is found with the
     * specified email, it returns null.
     *
     * @param Email $email The email address of the contact to retrieve.
     * @return Contact|null The contact if found, null otherwise.
     */
    #[Override]
    public function byEmail(Email $email): ?Contact
    {
        $entity = $this->findOneBy(['email' => $email->getValue()]);

        if ($entity === null) {
            return null;
        }

        assert($entity instanceof Contact);

        return $entity;
    }

    /**
     * Saves a contact entity to the repository.
     *
     * This method persists the provided contact entity and flushes the changes to
     * the database. It returns the saved contact entity.
     *
     * @param EntityInterface $entity The contact entity to save.
     * @return Contact The saved contact entity.
     */
    #[Override]
    public function save(EntityInterface $entity): Contact
    {
        $entity = parent::save($entity);
        assert($entity instanceof Contact);

        return $entity;
    }
}
