<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Exception\ResourcePersistenceException;
use App\Application\Message\Command\UpdateContactCommand;
use App\Application\Result\Result;
use App\Application\Support\Transaction\TransactionRunner;
use App\Domain\Entity\Contact;
use App\Domain\Exception\DomainDuplicatedResourceException;
use App\Domain\Exception\DomainResourceNotFoundException;
use App\Domain\Repository\ContactRepositoryInterface;
use App\Domain\ValueObject\ContactId;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\FirstName;
use App\Domain\ValueObject\LastName;

/**
 * Use case for updating a contact.
 *
 * This class is responsible for updating a contact based on the provided input.
 *
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
 */
final readonly class UpdateContactUseCase
{
    /**
     * Constructor for the UpdateContactUseCase.
     *
     * @param ContactRepositoryInterface $contactRepository The repository for contact entities.
     * @param TransactionRunner $transactionRunner The service for running transactions.
     */
    public function __construct(
        private ContactRepositoryInterface $contactRepository,
        private TransactionRunner $transactionRunner,
    ) {
    }

    /**
     * Invokes the use case to update a contact.
     *
     * @param UpdateContactCommand $command The command containing the input data for updating a contact.
     * @return Result The result containing the updated contact data.
     * @throws DomainResourceNotFoundException If the contact or role is not found.
     * @throws ResourcePersistenceException If an error occurs during the update process.
     */
    public function __invoke(UpdateContactCommand $command): Result
    {
        $contact = $this->contactRepository->byIdOrFail(new ContactId($command->id));

        $this->rename($command, $contact);
        $this->changeEmail($command, $contact);

        $contact = $this->transactionRunner->run(fn () => $this->contactRepository->save($contact));

        return Result::fromDomain($contact);
    }

    /**
     * Renames the contact if there are changes in the first name or last name.
     *
     * @param UpdateContactCommand $command The command containing the input data for updating a contact.
     * @param Contact $contact The contact entity to be updated.
     */
    private function rename(UpdateContactCommand $command, Contact $contact): void
    {
        $newFirstName = $command->firstName->present
            ? new FirstName((string) $command->firstName->value)
            : null;
        $newLastName = $command->lastName->present
            ? new LastName((string) $command->lastName->value)
            : null;

        if ($newFirstName === null && $newLastName === null) {
            return;
        }

        $contact->rename($newFirstName, $newLastName);
    }

    /**
     * Changes the contact's email if there is a change in the email.
     *
     * @param UpdateContactCommand $command The command containing the input data for updating a contact.
     * @param Contact $contact The contact entity to be updated.
     */
    private function changeEmail(UpdateContactCommand $command, Contact $contact): void
    {
        if (!$command->email->present) {
            return;
        }

        $email = new Email((string) $command->email->value);

        // if email is changing, ensure no other contact has it
        $existing = $this->contactRepository->byEmail($email);

        if ($existing && $existing->id() !== $contact->id()) {
            throw new DomainDuplicatedResourceException((string) $email);
        }

        $contact->changeEmail($email);
    }
}
