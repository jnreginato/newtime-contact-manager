<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response\Encoder\FieldSet;

use App\Infrastructure\Api\Response\OutputInterface;
use App\UnitTestCase;
use PHPUnit\Framework\MockObject\Exception;

use function assert;

/**
 * Feature: FieldSetFilterTest
 *
 * This class tests the FieldSetFilter functionality, ensuring that it correctly filters fields
 * and relationships based on the provided field set.
 *
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress InvalidArgument
 */
final class FieldSetFilterTest extends UnitTestCase
{
    /**
     * Scenario: GetFields returns filtered fields
     *
     * Given a resource with fields
     * When the FieldSetFilter is applied with a specific field set
     * Then it should return only the fields specified in the field set
     *
     * @throws Exception If there is an error creating the mock objects.
     */
    public function testGetFieldsReturnsFilteredFields(): void
    {
        $resource = $this->createMock(OutputInterface::class);
        assert($resource instanceof OutputInterface);

        $resource->method('getType')->willReturn('user');
        $resource->method('getFields')->willReturn([
            'id' => 1,
            'name' => 'Jonatan',
            'email' => 'jonatan@example.com',
        ]);

        $filter = new FieldSetFilter(
            includePath: [],
            fieldSet: ['user' => ['id', 'name']],
        );

        $fields = iterator_to_array($filter->getFields($resource));

        self::assertSame(['id' => 1, 'name' => 'Jonatan'], $fields);
    }

    /**
     * Scenario: GetFields returns all fields if no filter is applied
     *
     * Given a resource with fields
     * When the FieldSetFilter is applied without any specific field set
     * Then it should return all fields of the resource
     *
     * @throws Exception If there is an error creating the mock objects.
     */
    public function testGetFieldsReturnsAllFieldsIfNoFilter(): void
    {
        $resource = $this->createMock(OutputInterface::class);
        assert($resource instanceof OutputInterface);

        $resource->method('getType')->willReturn('user');
        $resource->method('getFields')->willReturn([
            'id' => 1,
            'name' => 'Jonatan',
        ]);

        $filter = new FieldSetFilter([], []);

        $fields = iterator_to_array($filter->getFields($resource));

        self::assertSame(['id' => 1, 'name' => 'Jonatan'], $fields);
    }

    /**
     * Scenario: GetRelationships yields only included relationships
     *
     * Given a resource with relationships
     * When the FieldSetFilter is applied with a specific relationship set
     * Then it should yield only the relationships specified in the field set
     */
    public function testGetRelationshipsYieldsOnlyIncludedRelationships(): void
    {
        $entity = new UserEntityFake();
        $resource = new UserOutputFake($entity);

        $filter = new FieldSetFilter(
            ['user' => ['profile']],
            ['profile' => ['id', 'bio']],
        );

        // @phpstan-ignore-next-line
        $results = iterator_to_array($filter->getRelationships($resource));

        self::assertArrayHasKey('profile', $results);
        self::assertSame(['id' => 123, 'bio' => 'Developer'], $results['profile']);
    }

    /**
     * Scenario: Nested relationship yields correct structure
     *
     * Given a resource with nested relationships
     * When the FieldSetFilter is applied with specific nested relationships
     * Then it should yield the correct structure for the nested relationship
     *
     * @throws Exception If there is an error creating the mock objects.
     */
    public function testNestedRelationshipYieldsCorrectStructure(): void
    {
        $entity = new UserEntityFake();
        $resource = new UserOutputFake($entity);

        $filter = new FieldSetFilter(
            ['user' => ['profile']],
            ['profile' => ['bio']],
        );

        $results = iterator_to_array($filter->getRelationships($resource));

        self::assertArrayHasKey('profile', $results);
        self::assertSame(['bio' => 'Developer'], $results['profile']);
        self::assertArrayNotHasKey('id', $results['profile']);
    }

    /**
     * Scenario: GetRelationships skips not allowed include on subsequent keys
     *
     * Given a resource with relationships
     * When the FieldSetFilter is applied with an include path that contains not allowed keys
     * Then it should skip the not allowed keys and only return the allowed ones
     */
    public function testGetRelationshipsSkipsNotAllowedIncludeOnSubsequentKeys(): void
    {
        $entity = new UserEntityFake();
        $resource = new UserOutputFake($entity);

        $filter = new FieldSetFilter(
            includePath: ['user' => ['profile', 'notAllowed']],
            fieldSet: [
                // only 'profile' has a defined field set, so 'notAllowed' will be rejected
                'profile' => ['id', 'bio'],
            ],
        );

        $results = iterator_to_array($filter->getRelationships($resource));

        self::assertArrayHasKey('profile', $results);
        self::assertSame(['id' => 123, 'bio' => 'Developer'], $results['profile']);
        self::assertArrayNotHasKey('notAllowed', $results);
    }
}
