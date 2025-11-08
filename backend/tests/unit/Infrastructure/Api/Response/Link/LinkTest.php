<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response\Link;

use App\Infrastructure\Api\Response\JsonApiKeyword;
use App\UnitTestCase;
use ReflectionClass;
use ReflectionException;

/**
 * Feature: Link
 *
 * This class provides unit tests for the Link class, which represents a link
 * in the API response.
 * It tests the functionality of showing links as strings, building URLs,
 * and getting array representations.
 */
final class LinkTest extends UnitTestCase
{
    /**
     * Scenario: Can be shown as string
     *
     * Given a Link instance with isSubUrl set to true and hasMeta set to false,
     * When canBeShownAsString is called;
     * Then it should return true.
     */
    public function testCanBeShownAsStringReturnsTrueWhenHasMetaIsFalse(): void
    {
        $link = new Link(true, '/resource', false);
        self::assertTrue($link->canBeShownAsString());
    }

    /**
     * Scenario: Cannot be shown as string
     *
     * Given a Link instance with isSubUrl set to true and hasMeta set to true,
     * When canBeShownAsString is called,
     * Then it should return false.
     */
    public function testCanBeShownAsStringReturnsFalseWhenHasMetaIsTrue(): void
    {
        $link = new Link(true, '/resource', true, ['foo' => 'bar']);
        self::assertFalse($link->canBeShownAsString());
    }

    /**
     * Scenario: Get string representation
     *
     * Given a Link instance with isSubUrl set to true,
     * When getStringRepresentation is called with a prefix,
     * Then it should return the full URL with the prefix.
     */
    public function testGetStringRepresentationReturnsValueWithPrefixForSubUrl(): void
    {
        $link = new Link(true, '/resource', false);
        $prefix = 'https://api.example.com';

        $result = $link->getStringRepresentation($prefix);

        self::assertSame('https://api.example.com/resource', $result);
    }

    /**
     * Scenario: Get string representation without prefix
     *
     * Given a Link instance with isSubUrl set to false,
     * When getStringRepresentation is called with a prefix,
     * Then it should return the value without the prefix.
     */
    public function testGetStringRepresentationReturnsValueWithoutPrefixForNonSubUrl(): void
    {
        $link = new Link(false, 'https://external.com/page', false);
        $result = $link->getStringRepresentation('https://ignored.com');

        self::assertSame('https://external.com/page', $result);
    }

    /**
     * Scenario: Get array representation
     *
     * Given a Link instance with isSubUrl set to true and hasMeta set to true,
     * When getArrayRepresentation is called with a prefix,
     * Then it should return an array with href and meta.
     */
    public function testGetArrayRepresentationReturnsHrefAndMeta(): void
    {
        $meta = ['page' => 1, 'limit' => 10];
        $link = new Link(true, '/paginated', true, $meta);

        $result = $link->getArrayRepresentation('https://api.example.com');

        self::assertSame([
            JsonApiKeyword::Href->value => 'https://api.example.com/paginated',
            JsonApiKeyword::Meta->value => $meta,
        ], $result);
    }

    /**
     * Scenario: Get array representation without meta
     *
     * Given a Link instance with isSubUrl set to true and hasMeta set to false,
     * When getArrayRepresentation is called,
     * Then it should assert that the link can be shown as a string.
     *
     * @throws ReflectionException If the method cannot be accessed.
     */
    public function testBuildUrlReturnsPrefixedValueWhenIsSubUrl(): void
    {
        $ref = new ReflectionClass(Link::class);
        $method = $ref->getMethod('buildUrl');

        $link = new Link(true, '/path', false);
        $result = $method->invoke($link, 'https://example.com');

        self::assertSame('https://example.com/path', $result);
    }

    /**
     * Scenario: Build URL returns value when not a sub-URL
     *
     * Given a Link instance with isSubUrl set to false,
     * When buildUrl is called with a prefix,
     * Then it should return the value without the prefix.
     *
     * @throws ReflectionException
     */
    public function testBuildUrlReturnsValueWhenNotSubUrl(): void
    {
        $ref = new ReflectionClass(Link::class);
        $method = $ref->getMethod('buildUrl');

        $link = new Link(false, 'https://external.com/page', false);
        $result = $method->invoke($link, 'https://ignored.com');

        self::assertSame('https://external.com/page', $result);
    }
}
