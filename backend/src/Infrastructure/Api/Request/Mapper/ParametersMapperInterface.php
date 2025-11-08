<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Mapper;

use App\Application\Message\PaginatedQueryInterface;
use App\Domain\Entity\EntityInterface;
use App\Infrastructure\Persistence\Doctrine\Repository\QueryRepositoryInterface;

/**
 * Interface ParametersMapperInterface
 *Ò
 * This interface defines the contract for a parameters mapper that applies query parameters
 * to an entity class and its repository.
 */
interface ParametersMapperInterface
{
    /**
     * Sets the repository for the entity class.
     *
     * @param QueryRepositoryInterface $repository The repository instance for the entity class.
     */
    public function setRepository(QueryRepositoryInterface $repository): void;

    /**
     * Applies the query parameters from the input to the entity class and its repository.
     *
     * This method processes the input parameters and applies them to the entity class,
     * allowing for filtering, sorting, and pagination based on the provided input.
     *
     * @param PaginatedQueryInterface $query The input containing query parameters to be applied.
     */
    public function applyQueryParameters(PaginatedQueryInterface $query): void;
}
