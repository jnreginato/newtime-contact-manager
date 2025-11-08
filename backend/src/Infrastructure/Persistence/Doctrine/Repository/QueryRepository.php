<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Application\Result\PaginatedResult;
use App\Application\Result\PaginatedResultInterface;
use App\Application\Result\Result;
use App\Domain\Entity\EntityInterface;
use App\Infrastructure\Api\Response\OutputInterface;
use App\Infrastructure\Persistence\Doctrine\QueryBuilder\EntityQueryBuilder;
use App\Infrastructure\Persistence\Doctrine\QueryBuilder\EntityQueryBuilderFactory;
use App\Infrastructure\Persistence\Doctrine\QueryBuilder\PagingApplier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Override;

use function array_filter;
use function array_map;
use function assert;
use function ceil;
use function count;
use function is_array;
use function max;
use function sprintf;

/**
 * Repository is the base class for all repositories.
 *
 * It provides common functionality such as pagination, filtering, sorting,
 * and relationship handling.
 *
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
 */
abstract class QueryRepository extends Repository implements QueryRepositoryInterface
{
    /**
     * The maximum number of results to return in a single query.
     */
    protected int $pagingLimit = 10;

    /**
     * The offset for the results to return.
     *
     * This is used for pagination to skip a certain number of results.
     */
    protected int $pagingOffset = 0;

    /**
     * The query builder used to build the queries for this repository.
     *
     * This is initialized in the `makePaginatedResourcesQueryBuilder` method
     * and is used to fetch resources, apply filters, sorting, and pagination.
     *
     * @var EntityQueryBuilder|null The query builder instance used for building queries.
     */
    protected ?EntityQueryBuilder $queryBuilder = null;

    /**
     * The factory for creating query builders.
     *
     * This factory is used to create instances of EntityQueryBuilder with the
     * specified parameters such as paging, filtering, sorting, and relationship handling.
     *
     * @var EntityQueryBuilderFactory The factory for creating query builders.
     */
    protected EntityQueryBuilderFactory $queryBuilderFactory;

    /**
     * Constructor for Repository.
     *
     * This constructor initializes the query builder factory with the provided
     * entity manager and class metadata, allowing the repository to create
     * query builders for fetching resources.
     *
     * @param EntityManagerInterface $entityManager The entity manager to be used for database operations.
     * @param ClassMetadata<EntityInterface> $class The class metadata for the entity this repository manages.
     */
    public function __construct(EntityManagerInterface $entityManager, ClassMetadata $class)
    {
        $this->queryBuilderFactory = new EntityQueryBuilderFactory($entityManager, new PagingApplier());

        parent::__construct($entityManager, $class);
    }

    /**
     * Sets the paging parameters for the repository.
     *
     * This method allows you to specify the maximum number of results to return
     * and the number of results to skip before starting to return results.
     *
     * @param int $pagingLimit The maximum number of results to return.
     * @param int $pagingOffset The number of results to skip before returning results.
     */
    #[Override]
    public function withPaging(int $pagingLimit, int $pagingOffset): void
    {
        $this->pagingLimit = $pagingLimit;
        $this->pagingOffset = $pagingOffset;
    }

    /**
     * Lists resources with pagination and query parameters applied.
     *
     * This method retrieves a paginated list of resources, applying any necessary
     * filters, sorts, and paging limits as defined in the repository.
     *
     * @param class-string<OutputInterface> $outputClass The class name of the output DTO to map the resources to.
     * @return PaginatedResultInterface The paginated data containing the list of resources.
     */
    #[Override]
    public function list(string $outputClass): PaginatedResultInterface
    {
        return $this->fetchPaginatedResources($this->fetchResources(), $outputClass);
    }

    /**
     * Fetches resources based on the query builder.
     *
     * This method retrieves the resources from the database using the query builder,
     * applying any filters, sorts, and paging limits that have been set.
     *
     * @return array<EntityInterface> The list of resources fetched from the database.
     */
    private function fetchResources(): array
    {
        if ($this->queryBuilder === null) {
            $this->makePaginatedResourcesQueryBuilder();
        }

        assert($this->queryBuilder instanceof EntityQueryBuilder);

        $result = $this->queryBuilder->getQuery()->getResult();
        assert(is_array($result));

        return array_filter($result, static fn ($item) => $item instanceof EntityInterface);
    }

    /**
     * Fetches paginated resources without relationships.
     *
     * This method constructs a PaginatedResult object based on the provided list of resources,
     * calculating pagination details such as page size, page number, total items, and total pages.
     *
     * @param array<EntityInterface> $list The list of resources to paginate.
     * @param class-string<OutputInterface> $outputClass The class name of the output DTO to map the resources to.
     * @return PaginatedResultInterface The paginated data containing the list of resources and pagination details.
     */
    private function fetchPaginatedResources(array $list, string $outputClass): PaginatedResultInterface
    {
        if ($this->queryBuilder === null) {
            $this->makePaginatedResourcesQueryBuilder();
        }

        assert($this->queryBuilder instanceof EntityQueryBuilder);

        $count = count($list);
        $perPage = $this->queryBuilder->getMaxResults() ?? 10;
        $currentPage = (int) ($this->queryBuilder->getFirstResult() / max(1, $perPage)) + 1;
        $totalItems = $this->countLastQuery();
        $totalPages = (int) ceil($totalItems / $perPage);

        $data = array_map(
            static fn (EntityInterface $item): OutputInterface => $outputClass::fromResult(Result::fromDomain($item)),
            $list,
        );

        return new PaginatedResult($data, $count, $currentPage, $perPage, $totalPages, $totalItems);
    }

    /**
     * Counts the total number of items in the last query.
     *
     * This method executes a count query based on the last query builder state,
     * resetting any grouping or ordering to ensure an accurate count.
     *
     * @return int The total number of items matching the last query.
     */
    private function countLastQuery(): int
    {
        if ($this->queryBuilder === null) {
            $this->makePaginatedResourcesQueryBuilder();
        }

        assert($this->queryBuilder instanceof EntityQueryBuilder);

        return (int) $this->queryBuilder
            ->select(sprintf('COUNT(%s) total', $this->getAlias()))
            ->resetDQLPart('groupBy')
            ->resetDQLPart('orderBy')
            ->setFirstResult(null)
            ->setMaxResults(null)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Creates a query builder for paginated resources.
     *
     * This method initializes the query builder with the entity name, alias,
     * paging limit, offset, filter parameters, filter join type, sorting parameters,
     * and relationship filters and sorts.
     */
    private function makePaginatedResourcesQueryBuilder(): void
    {
        $this->queryBuilder = $this->queryBuilderFactory->create(
            $this->getEntityName(),
            $this->getAlias(),
            $this->pagingLimit,
            $this->pagingOffset,
        );
    }
}
