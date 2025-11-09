<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use App\UnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Feature: ApiError
 *
 * This class tests the ApiError class, which implements the ApiErrorInterface.
 * It verifies that the class correctly handles various error attributes such as status,
 * code, title, detail, and source.
 */
final class ApiErrorTest extends UnitTestCase
{
    /**
     * Scenario: Test ApiError with various attributes
     *
     * When creating an ApiError instance with different combinations of attributes,
     * Then the getters should return the expected values.
     *
     * @param string|null $status The HTTP status code.
     * @param string|null $code The application-specific error code.
     * @param string|null $title A short, human-readable summary of the problem.
     * @param string|null $detail A detailed explanation of the error.
     * @param array<array-key, mixed>|null $source Additional information about the error source.
     */
    #[DataProvider('provideApiErrorData')]
    public function testApiError(?string $status, ?string $code, ?string $title, ?string $detail, ?array $source): void
    {
        // @phpstan-ignore-next-line
        $error = new ApiError(status: $status, code: $code, title: $title, detail: $detail, source: $source);

        self::assertSame($status, $error->getStatus());
        self::assertSame($code, $error->getCode());
        self::assertSame($title, $error->getTitle());
        self::assertSame($detail, $error->getDetail());
        self::assertSame($source, $error->getSource());
    }

    /**
     * Data provider for testApiError.
     *
     * This method provides various combinations of error attributes to test the ApiError class.
     *
     * @return array<string, array<array-key, mixed>>
     */
    public static function provideApiErrorData(): array
    {
        return [
            'all nulls' => [
                null,
                null,
                null,
                null,
                null,
            ],
            'only status' => [
                '400',
                null,
                null,
                null,
                null,
            ],
            'full data' => [
                '422',
                'invalid_field',
                'Invalid Field',
                'The provided field value is invalid.',
                ['pointer' => '/data/attributes/email'],
            ],
            'with parameter source' => [
                '403',
                'permission_denied',
                'Permission Denied',
                'You do not have access to this resource.',
                ['parameter' => 'api_key'],
            ],
            'empty array source' => [
                '500',
                'internal_error',
                'Server Error',
                'Something went wrong.',
                [],
            ],
        ];
    }
}
