<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Mapper;

use App\Infrastructure\Persistence\Doctrine\Repository\QueryRepositoryInterface;

/**
 * Interface QueryParameterApplierInterface
 *
 * This interface defines the contract for applying query parameters to a repository.
 * It is used to set paging limits, offsets, filters, and sorts based on the provided input.
 */
interface QueryParameterApplierInterface
{
    /**
     * Applies the query parameters from the InputInterface to the repository.
     *
     * This method sets paging limits, offsets, filters, and sorts based on the
     * provided input.
     *
     * @param QueryRepositoryInterface $repository The repository instance to which parameters will be applied.
     * @param int $pagingLimit The limit for paging results.
     * @param int $pagingOffset The offset for paging results.
     */
    public function apply(QueryRepositoryInterface $repository, int $pagingLimit, int $pagingOffset): void;
}
