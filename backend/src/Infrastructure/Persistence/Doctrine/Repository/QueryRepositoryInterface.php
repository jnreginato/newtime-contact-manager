<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Application\Result\PaginatedResultInterface;

/**
 * Interface QueryRepositoryInterface
 *
 * This interface defines the methods required for a repository that handles
 * data persistence and retrieval in an API context. It includes methods for
 * setting paging limits, offsets, filter parameters, sorting parameters,
 * and relationship filters and sorts.
 *
 * @psalm-type FilterMap = array<string, array<int, list<string>>>
 * @psalm-type SortMap = array<string, string>
 */
interface QueryRepositoryInterface
{
    /**
     * Sets the paging limit and offset for the repository.
     *
     * This method allows you to specify the maximum number of results
     * to return in a single query and the number of results to skip
     * before starting to return results in a query.
     *
     * @param int $pagingLimit The maximum number of results to return.
     * @param int $pagingOffset The number of results to skip before returning results.
     */
    public function withPaging(int $pagingLimit, int $pagingOffset): void;

    /**
     * Lists resources with pagination and query parameters applied.
     *
     * This method retrieves a paginated list of resources, applying any necessary
     * filters, sorts, and paging limits as defined in the repository.
     *
     * @param class-string $outputClass The class name of the output DTO to map the results to.
     * @return PaginatedResultInterface The paginated data containing the list of resources.
     */
    public function list(string $outputClass): PaginatedResultInterface;

    /**
     * Returns the alias for the entity in the query builder.
     *
     * This method should be implemented in concrete repository classes
     * to provide the alias used in DQL queries.
     *
     * @return string The alias for the entity.
     */
    public function getAlias(): string;
}
