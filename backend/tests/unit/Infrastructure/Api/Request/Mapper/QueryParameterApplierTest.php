<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Mapper;

use App\Infrastructure\Persistence\Doctrine\Repository\QueryRepositoryInterface;
use App\UnitTestCase;

/**
 * Feature: Apply query parameters to the repository.
 *
 * QueryParameterApplier should set paging on the repository.
 */
final class QueryParameterApplierTest extends UnitTestCase
{
    /**
     * Scenario: apply forwards paging to the repository.
     *
     * Given a QueryParameterApplier and a repository,
     * When apply is called with paging limit and offset,
     * Then repository::withPaging is invoked with the same values.
     */
    public function testApplySetsPagingOnRepository(): void
    {
        $limit = 25;
        $offset = 50;

        $repository = $this->createMock(QueryRepositoryInterface::class);
        $repository
            ->expects(self::once())
            ->method('withPaging')
            ->with(self::equalTo($limit), self::equalTo($offset));

        $applier = new QueryParameterApplier();
        $applier->apply($repository, $limit, $offset);
    }
}
