<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response\MediaType;

use App\Infrastructure\Api\Exception\ApiInvalidArgumentException;
use App\UnitTestCase;

/**
 * Feature: MediaType
 *
 * This class provides unit tests for the MediaType class, which represents a media type
 * in the API response. It tests the functionality of constructing valid media types,
 * handling parameters, and ensuring that exceptions are thrown for invalid inputs.
 */
final class MediaTypeTest extends UnitTestCase
{
    /**
     * Scenario: Can be constructed with valid type and subtype
     *
     * Given a valid type and subtype,
     * When the MediaType is constructed,
     * Then it should return the correct type, subtype, and media type.
     */
    public function testConstructValidMediaType(): void
    {
        $mediaType = new MediaType('application', 'json');

        self::assertSame('application', $mediaType->getType());
        self::assertSame('json', $mediaType->getSubType());
        self::assertSame('application/json', $mediaType->getMediaType());
        self::assertSame([], $mediaType->getParameters());
    }

    /**
     * Scenario: Can be constructed with parameters
     *
     * Given a valid type, subtype, and parameters,
     * When the MediaType is constructed,
     * Then it should return the correct parameters.
     */
    public function testConstructWithParameters(): void
    {
        $parameters = ['charset' => 'utf-8'];
        $mediaType = new MediaType('application', 'json', $parameters);

        self::assertSame($parameters, $mediaType->getParameters());
    }

    /**
     * Scenario: Can be constructed with trimmed type and subtype
     *
     * Given a type and subtype with leading and trailing spaces,
     * When the MediaType is constructed,
     * Then it should trim the type and subtype.
     */
    public function testConstructTrimsTypeAndSubType(): void
    {
        $mediaType = new MediaType(' application ', ' json ', ['foo' => 'bar']);

        self::assertSame('application', $mediaType->getType());
        self::assertSame('json', $mediaType->getSubType());
        self::assertSame('application/json', $mediaType->getMediaType());
        self::assertSame(['foo' => 'bar'], $mediaType->getParameters());
    }

    /**
     * Scenario: Throws exception for empty type
     *
     * Given an empty type,
     * When the MediaType is constructed,
     * Then it should throw an ApiInvalidArgumentException.
     */
    public function testConstructThrowsExceptionForEmptyType(): void
    {
        $this->expectException(ApiInvalidArgumentException::class);
        $this->expectExceptionMessage('type');

        new MediaType('', 'json');
    }

    /**
     * Scenario: Throws exception for whitespace-only type
     *
     * Given a type that contains only whitespace,
     * When the MediaType is constructed,
     * Then it should throw an ApiInvalidArgumentException.
     */
    public function testConstructThrowsExceptionForWhitespaceOnlyType(): void
    {
        $this->expectException(ApiInvalidArgumentException::class);
        $this->expectExceptionMessage('type');

        new MediaType('   ', 'json');
    }

    /**
     * Scenario: Throws exception for empty subType
     *
     * Given an empty subType,
     * When the MediaType is constructed,
     * Then it should throw an ApiInvalidArgumentException.
     */
    public function testConstructThrowsExceptionForEmptySubType(): void
    {
        $this->expectException(ApiInvalidArgumentException::class);
        $this->expectExceptionMessage('subType');

        new MediaType('application', '');
    }

    /**
     * Scenario: Throws exception for whitespace-only subType
     *
     * Given a subType that contains only whitespace,
     * When the MediaType is constructed,
     * Then it should throw an ApiInvalidArgumentException.
     */
    public function testConstructThrowsExceptionForWhitespaceOnlySubType(): void
    {
        $this->expectException(ApiInvalidArgumentException::class);
        $this->expectExceptionMessage('subType');

        new MediaType('application', '   ');
    }
}
