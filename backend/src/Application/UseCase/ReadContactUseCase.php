<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Message\Query\ReadContactQuery;
use App\Application\Result\Result;
use App\Domain\Exception\DomainResourceNotFoundException;
use App\Domain\Repository\ContactRepositoryInterface;
use App\Domain\ValueObject\ContactId;

/**
 * Use-case for reading a contact.
 *
 * This class is responsible for retrieving a contact based on the provided input.
 */
final readonly class ReadContactUseCase
{
    /**
     * Constructor for the ReadContactUseCase.
     *
     * @param ContactRepositoryInterface $contactRepository The repository for contact entities.
     */
    public function __construct(private ContactRepositoryInterface $contactRepository)
    {
    }

    /**
     * Invokes the use case to read a contact.
     *
     * @param ReadContactQuery $query The query containing the input data for reading a contact.
     * @return Result The result containing the contact data.
     * @throws DomainResourceNotFoundException If the contact is not found.
     */
    public function __invoke(ReadContactQuery $query): Result
    {
        $contact = $this->contactRepository->byIdOrFail(new ContactId((int) $query->id));

        return Result::fromDomain($contact);
    }
}
