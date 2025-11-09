<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Exception\ResourcePersistenceException;
use App\Application\Message\Command\DeleteContactCommand;
use App\Application\Support\Transaction\TransactionRunner;
use App\Domain\Exception\DomainResourceNotFoundException;
use App\Domain\Repository\ContactRepositoryInterface;
use App\Domain\ValueObject\ContactId;
use Psr\Clock\ClockInterface;

/**
 * Use case for deleting a contact.
 *
 * This class is responsible for handling the deletion of a contact entity.
 * It retrieves the contact by its identifier, marks it as deleted, and persists
 * the changes to the repository.
 */
final readonly class DeleteContactUseCase
{
    /**
     * Constructor for the DeleterContactUseCase.
     *
     * @param ContactRepositoryInterface $contactRepository The repository for contact entities.
     * @param TransactionRunner $transactionRunner The service for running transactions.
     * @param ClockInterface $clock The clock interface for getting the current time.
     */
    public function __construct(
        private ContactRepositoryInterface $contactRepository,
        private TransactionRunner $transactionRunner,
        private ClockInterface $clock,
    ) {
    }

    /**
     * Invokes the use case to delete a contact.
     *
     * @param DeleteContactCommand $command The command containing the input data for deleting a contact.
     * @throws DomainResourceNotFoundException If the contact is not found.
     * @throws ResourcePersistenceException If there is an error during persistence.
     */
    public function __invoke(DeleteContactCommand $command): void
    {
        $contact = $this->contactRepository->byIdOrFail(new ContactId($command->id));

        $contact->delete($this->clock);

        $this->transactionRunner->run(fn () => $this->contactRepository->save($contact));
    }
}
