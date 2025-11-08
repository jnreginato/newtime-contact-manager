<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Adapter\Api\V1\Presenter\ContactOutput;
use App\Application\Message\Query\ListContactsQuery;
use App\Application\Result\PaginatedResultInterface;
use App\Domain\Repository\ContactRepositoryInterface;
use App\Infrastructure\Api\Request\Mapper\ParametersMapperInterface;

/**
 * Use case for listing contacts.
 *
 * This use case handles the logic for retrieving a paginated list of contacts.
 * It applies query parameters to the request and retrieves the contact data from the repository.
 */
final readonly class ListContactsUseCase
{
    /**
     * Constructor for the ListContactsUseCase.
     *
     * @param ParametersMapperInterface $parametersMapper The mapper for query parameters.
     * @param ContactRepositoryInterface $contactRepository The repository for contact entities.
     */
    public function __construct(
        private ParametersMapperInterface $parametersMapper,
        private ContactRepositoryInterface $contactRepository,
    ) {
    }

    /**
     * Invokes the use case to list contacts.
     *
     * @param ListContactsQuery $query The input data for listing contacts.
     * @return PaginatedResultInterface The paginated data containing the list of contacts.
     */
    public function __invoke(ListContactsQuery $query): PaginatedResultInterface
    {
        $this->parametersMapper->applyQueryParameters($query);

        return $this->contactRepository->list(ContactOutput::class);
    }
}
