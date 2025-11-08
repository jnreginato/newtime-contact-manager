<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use InvalidArgumentException;
use App\UnitTestCase;
use Override;
use stdClass;
use Stringable;

/**
 * Feature: SimpleError unit tests.
 *
 * This test suite verifies the functionality of the SimpleError class,
 * ensuring it correctly handles various types of parameters, including scalars,
 * Stringable objects, and null values.
 */
final class SimpleErrorTest extends UnitTestCase
{
    /**
     * Feature: Constructing SimpleError with valid scalars and Stringable objects.
     *
     * Given: A SimpleError instance with valid parameters,
     * When: The constructor is called,
     * Then: It should initialize the properties correctly.
     */
    public function testConstructWithValidScalars(): void
    {
        $error = new SimpleError(
            parameterName: 'email',
            parameterValue: 'invalid@example.com',
            internalErrorCode: '123',
            messageTemplate: 'Invalid value for {0}',
            messageParameters: ['email'],
        );

        self::assertSame('email', $error->parameterName);
        self::assertSame('invalid@example.com', $error->parameterValue);
        self::assertSame('123', $error->internalErrorCode);
        self::assertSame('Invalid value for {0}', $error->messageTemplate);
        self::assertSame(['email'], $error->messageParameters);
    }

    /**
     * Feature: Constructing SimpleError with Stringable objects.
     *
     * Given: A SimpleError instance with a Stringable object,
     * When: The constructor is called,
     * Then: It should accept the Stringable object in message parameters.
     */
    public function testConstructWithStringableObject(): void
    {
        $stringable = new class () implements Stringable {
            /**
             * Returns the string representation of the object.
             *
             * @return string The string representation of the object.
             */
            #[Override]
            public function __toString(): string
            {
                return 'stringable';
            }
        };

        $error = new SimpleError(
            parameterName: 'id',
            parameterValue: 42,
            internalErrorCode: '999',
            messageTemplate: 'Error with {0}',
            messageParameters: [$stringable],
        );

        self::assertSame([$stringable], $error->messageParameters);
    }

    /**
     * Feature: Constructing SimpleError with null in message parameters.
     *
     * Given: A SimpleError instance with null as a message parameter,
     * When: The constructor is called,
     * Then: It should accept null in the message parameters.
     */
    public function testConstructWithNullInMessageParameters(): void
    {
        $error = new SimpleError(
            parameterName: 'nullable',
            parameterValue: null,
            internalErrorCode: '500',
            messageTemplate: 'Value for {0} is null',
            messageParameters: [null],
        );

        self::assertSame([null], $error->messageParameters);
    }

    /**
     * Feature: Throws exception when a parameter is not convertible to string.
     *
     * Given: A SimpleError instance with a non-scalar and non-Stringable parameter,
     * When: The constructor is called,
     * Then: It should throw an InvalidArgumentException.
     */
    public function testThrowsExceptionWhenParameterNotConvertibleToString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must be a scalar or Stringable type.');

        new SimpleError(
            parameterName: 'test',
            parameterValue: [],
            internalErrorCode: '400',
            messageTemplate: 'Invalid type',
            messageParameters: [new stdClass()], // not stringable
        );
    }
}
