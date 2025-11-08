<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response\Encoder\FieldSet;

use InvalidArgumentException;
use App\UnitTestCase;

/**
 * Feature: RelationshipTraversalState
 *
 * Test suite for the RelationshipTraversalState class.
 */
final class RelationshipTraversalStateTest extends UnitTestCase
{
    /**
     * Scenario: Get Entity
     *
     * Given an entity or a persistent collection,
     * When the state is created,
     * Then it should return the entity or collection when getEntity is called.
     */
    public function testGetEntityReturnsEntity(): void
    {
        $entity = new UserEntityFake();
        $output = new UserOutputFake($entity);
        $state = new RelationshipTraversalState($output);

        self::assertSame($entity, $state->getEntity());
    }

    /**
     * Scenario: Get Type
     *
     * Given an output with a specific type,
     * When the state is created,
     * Then it should return the type when getType is called.
     */
    public function testGetTypeReturnsTypeFromOutput(): void
    {
        $entity = new UserEntityFake();
        $output = new UserOutputFake($entity);
        $state = new RelationshipTraversalState($output);

        self::assertSame('user', $state->getType());
    }

    /**
     * Scenario: Get Parent
     *
     * Given a parent identifier,
     * When the state is created with the parent,
     * Then it should return the parent when getParent is called.
     */
    public function testGetParentReturnsProvidedParent(): void
    {
        $entity = new UserEntityFake();
        $output = new UserOutputFake($entity);
        $state = new RelationshipTraversalState($output, 'user.profile');

        self::assertSame('user.profile', $state->getParent());
    }

    /**
     * Scenario: Get Parent Default
     *
     * Given no parent identifier,
     * When the state is created without a parent,
     * Then it should return an empty string when getParent is called.
     */
    public function testGetParentReturnsEmptyStringIfNotProvided(): void
    {
        $entity = new UserEntityFake();
        $output = new UserOutputFake($entity);
        $state = new RelationshipTraversalState($output);

        self::assertSame('', $state->getParent());
    }

    /**
     * Scenario: Get Related Output Class
     *
     * Given an output with defined relationships,
     * When getRelatedOutputClass is called with a valid field name,
     * Then it should return the corresponding related output class name.
     */
    public function testGetRelatedOutputClassDelegatesToOutput(): void
    {
        $entity = new UserEntityFake();
        $output = new UserOutputFake($entity);
        $state = new RelationshipTraversalState($output);

        $related = $state->getRelatedOutputClass('profile');

        self::assertSame(ProfileOutputFake::class, $related);
    }

    /**
     * Scenario: Get Related Output Class Invalid Argument
     *
     * Given an output without the specified relationship,
     * When getRelatedOutputClass is called with an invalid field name,
     * Then it should throw an InvalidArgumentException.
     */
    public function testGetRelatedOutputClassPropagatesInvalidArgument(): void
    {
        $entity = new UserEntityFake();
        $output = new UserOutputFake($entity);
        $state = new RelationshipTraversalState($output);

        $this->expectException(InvalidArgumentException::class);
        $state->getRelatedOutputClass('unknown');
    }
}
