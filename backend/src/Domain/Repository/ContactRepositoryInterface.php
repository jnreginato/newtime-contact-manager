<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Contact;
use App\Domain\Entity\EntityInterface;
use App\Domain\ValueObject\ContactId;
use App\Domain\ValueObject\Email;
use Stringable;

/**
 * ContactRepositoryInterface
 *
 * This interface defines the methods for interacting with contact entities in
 * the repository.
 */
interface ContactRepositoryInterface
{
    /**
     * Retrieves a contact by its identifier.
     *
     * @param ContactId $identifier The identifier of the contact to retrieve.
     * @return Contact|null The contact if found, null otherwise.
     */
    public function byId(Stringable $identifier): ?Contact;

    /**
     * Retrieves a contact by its identifier or throws an exception if not found.
     *
     * @param ContactId $identifier The identifier of the contact to retrieve.
     * @return Contact The contact if found.
     */
    public function byIdOrFail(Stringable $identifier): Contact;

    /**
     * Saves a contact entity to the repository.
     *
     * @param EntityInterface $entity The contact entity to save.
     * @return Contact The saved contact entity.
     */
    public function save(EntityInterface $entity): Contact;

    /**
     * Retrieves a contact by its email address.
     *
     * @param Email $email The email address of the contact to retrieve.
     * @return Contact|null The contact if found, null otherwise.
     */
    public function byEmail(Email $email): ?Contact;
}
