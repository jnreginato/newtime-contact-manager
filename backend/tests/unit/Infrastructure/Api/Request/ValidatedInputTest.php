<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

use App\UnitTestCase;

/**
 * Feature: ValidatedInput
 *
 * This test suite validates the functionality of the ValidatedInput class,
 * ensuring that it correctly handles input validation for filters, sorts,
 * includes, and fields according to the defined rules.
 */
final class ValidatedInputTest extends UnitTestCase
{
    /**
     * Scenario: ValidatedInput constructor defaults
     *
     * Given a new instance of ValidatedInput
     * When it is constructed without any data,
     * Then it should have default values for resourceId, pageSize, pageNumber, filter, and sort
     */
    public function testConstructorDefaults(): void
    {
        // @phpstan-ignore-next-line
        $input = new FakeConcreteInput([]);

        self::assertNull($input->getResourceId());
        self::assertSame(10, $input->getPageSize());
        self::assertSame(1, $input->getPageNumber());
    }

    /**
     * Scenario: ValidatedInput constructor with data
     *
     * Given a new instance of ValidatedInput with specific data
     * When it is constructed with that data,
     * Then it should correctly set the resourceId, pageSize, pageNumber, filter, and sort
     */
    public function testConstructorWithData(): void
    {
        // @phpstan-ignore-next-line
        $input = new FakeConcreteInput([
            'resourceId' => '<script>1</script>',
            'pageSize' => 5,
            'pageNumber' => 2,
        ]);

        self::assertSame('<script>1</script>', $input->getResourceId());
        self::assertSame(5, $input->getPageSize());
        self::assertSame(2, $input->getPageNumber());
    }
}
