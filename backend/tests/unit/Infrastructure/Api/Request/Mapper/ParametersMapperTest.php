<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Mapper;

use App\Infrastructure\Api\Exception\ApiLogicException;
use App\Infrastructure\Api\Exception\ErrorMessage;
use App\Infrastructure\Persistence\Doctrine\Repository\QueryRepositoryInterface;
use App\Application\Message\PaginatedQueryInterface;
use App\UnitTestCase;

/**
 * Feature: Map request parameters to the repository and apply them.
 *
 * This test class validates ParametersMapper behavior: delegating parameter
 * application to the applier and throwing when the repository is not set.
 */
final class ParametersMapperTest extends UnitTestCase
{
    /**
     * Scenario: apply parameter delegates to applier.
     *
     * Given a ParametersMapper with a QueryParameterApplier,
     * When repository is set and applyQueryParameters is called,
     * Then QueryParameterApplier::apply is invoked with the correct pageSize and offset.
     */
    public function testApplyQueryParametersDelegatesToApplier(): void
    {
        $pageSize = 10;
        $pageNumber = 2;
        $expectedOffset = ($pageNumber - 1) * $pageSize;

        $applier = $this->createMock(QueryParameterApplierInterface::class);
        $repository = $this->createMock(QueryRepositoryInterface::class);
        $query = $this->createMock(PaginatedQueryInterface::class);

        $query->method('getPageSize')->willReturn($pageSize);
        $query->method('getPageNumber')->willReturn($pageNumber);

        $applier
            ->expects(self::once())
            ->method('apply')
            ->with(
                self::identicalTo($repository),
                self::equalTo($pageSize),
                self::equalTo($expectedOffset)
            );

        $mapper = new ParametersMapper($applier);
        $mapper->setRepository($repository);
        $mapper->applyQueryParameters($query);
    }

    /**
     * Scenario: missing repository throws an exception.
     *
     * Given a ParametersMapper with no repository set,
     * When applyQueryParameters is called,
     * Then an ApiLogicException is thrown with the RepositoryIsNotSet message.
     */
    public function testApplyQueryParametersThrowsWhenRepositoryNotSet(): void
    {
        $this->expectException(ApiLogicException::class);
        $this->expectExceptionMessage(ErrorMessage::RepositoryIsNotSet->value);

        $applier = $this->createMock(QueryParameterApplierInterface::class);
        $query = $this->createMock(PaginatedQueryInterface::class);

        // page values are irrelevant because the repository check happens first
        $query->method('getPageSize')->willReturn(1);
        $query->method('getPageNumber')->willReturn(1);

        $mapper = new ParametersMapper($applier);
        $mapper->applyQueryParameters($query);
    }
}
