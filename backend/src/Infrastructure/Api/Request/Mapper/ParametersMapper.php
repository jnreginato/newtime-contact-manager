<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Mapper;

use App\Application\Message\PaginatedQueryInterface;
use App\Infrastructure\Api\Exception\ApiLogicException;
use App\Infrastructure\Api\Exception\ErrorMessage;
use App\Infrastructure\Persistence\Doctrine\Repository\QueryRepositoryInterface;
use Override;

/**
 * Class ParametersMapper
 *
 * This class implements the ParametersMapperInterface and provides a way to map
 * query parameters from an InputInterface to a RepositoryInterface.
 */
final class ParametersMapper implements ParametersMapperInterface
{
    /**
     * The repository instance used for querying the entity.
     * It should implement RepositoryInterface.
     */
    private ?QueryRepositoryInterface $repository = null;

    /**
     * Constructor.
     *
     * Initializes the ParametersMapper with the provided dependencies.
     *
     * @param QueryParameterApplierInterface $parameterApplier The service for applying parameters to the repository.
     */
    public function __construct(private readonly QueryParameterApplierInterface $parameterApplier)
    {
    }

    /**
     * Sets the repository instance that will be used for querying the entity.
     *
     * @param QueryRepositoryInterface $repository The repository instance.
     */
    #[Override]
    public function setRepository(QueryRepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    /**
     * Applies the query parameters from the InputInterface to the repository.
     *
     * This method sets paging limits, offsets, filters, and sorts based on the
     * provided input. It also handles relationship filters and sorts.
     *
     * @param PaginatedQueryInterface $query The input containing query parameters.
     */
    #[Override]
    public function applyQueryParameters(PaginatedQueryInterface $query): void
    {
        $this->parameterApplier->apply(
            $this->getRepository(),
            $query->getPageSize(),
            ($query->getPageNumber() - 1) * $query->getPageSize(),
        );
    }

    /**
     * Returns the repository instance used for querying the entity.
     *
     * @return QueryRepositoryInterface The repository instance.
     * @throws ApiLogicException If the repository is not set.
     */
    private function getRepository(): QueryRepositoryInterface
    {
        if (!$this->repository instanceof QueryRepositoryInterface) {
            throw new ApiLogicException(ErrorMessage::RepositoryIsNotSet->value);
        }

        return $this->repository;
    }
}
