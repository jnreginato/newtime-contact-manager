<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Mapper;

use App\Infrastructure\Persistence\Doctrine\Repository\QueryRepositoryInterface;
use Override;

/**
 * Class QueryParameterApplier
 */
final class QueryParameterApplier implements QueryParameterApplierInterface
{
    /**
     * Applies the query parameters from the InputInterface to the repository.
     *
     * This method sets paging limits, offsets, filters, and sorts based on the
     * provided input. It also handles relationship filters and sorts.
     *
     * @param QueryRepositoryInterface $repository The repository instance to which parameters will be applied.
     * @param int $pagingLimit The limit for paging results.
     * @param int $pagingOffset The offset for paging results.
     */
    #[Override]
    public function apply(QueryRepositoryInterface $repository, int $pagingLimit, int $pagingOffset): void
    {
        $this->applyPaging($repository, $pagingLimit, $pagingOffset);
    }

    /**
     * Applies paging parameters to the repository.
     *
     * This method sets the `limit` and `offset` for the repository,
     * allowing for pagination of results.
     *
     * @param QueryRepositoryInterface $repository Repository to which page parameters will be applied.
     * @param int $limit The maximum number of results to return.
     * @param int $offset The number of results to skip before starting to return results.
     */
    private function applyPaging(QueryRepositoryInterface $repository, int $limit, int $offset): void
    {
        $repository->withPaging($limit, $offset);
    }
}
