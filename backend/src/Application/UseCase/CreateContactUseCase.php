<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Exception\ResourcePersistenceException;
use App\Application\Message\Command\CreateContactCommand;
use App\Application\Result\Result;
use App\Application\Support\Transaction\TransactionRunner;
use App\Domain\Entity\Contact;
use App\Domain\Exception\DomainDuplicatedResourceException;
use App\Domain\Exception\DomainResourceNotFoundException;
use App\Domain\Repository\ContactRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\FirstName;
use App\Domain\ValueObject\LastName;

/**
 * Use-case for creating a new contact.
 *
 * This class is responsible for handling the creation of a contact entity.
 * It validates the input, checks for existing contacts with the same email,
 * retrieves the role entity by its identifier, and persists the new contact entity
 * to the repository.
 *
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
 */
final readonly class CreateContactUseCase
{
    /**
     * Constructor for the CreateContactUseCase.
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
     * Invokes the use case to create a new contact.
     *
     * @param CreateContactCommand $command The command containing the input data for creating a contact.
     * @return Result The result containing the created contact data.
     * @throws DomainResourceNotFoundException If the role with the given identifier does not exist.
     * @throws DomainDuplicatedResourceException If a contact with the same email already exists.
     * @throws ResourcePersistenceException If there is an error during persistence.
     */
    public function __invoke(CreateContactCommand $command): Result
    {
        $email = new Email($command->email);

        // Check if a contact with the same email already exists
        if ($this->contactRepository->byEmail($email)) {
            throw new DomainDuplicatedResourceException((string) $email);
        }

        $contact = Contact::register(
            new FirstName($command->firstName),
            new LastName($command->lastName),
            $email,
        );

        $this->transactionRunner->run(fn () => $this->contactRepository->save($contact));

        return Result::fromDomain($contact);
    }
}
