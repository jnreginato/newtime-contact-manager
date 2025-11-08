<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use Throwable;

/**
 * Interface ThrowableConverterInterface
 *
 * Provides a method to convert a Throwable into an ApiException.
 */
interface ThrowableConverterInterface
{
    /**
     * Converts a Throwable into an ApiExceptionInterface instance.
     *
     * This method is designed to handle various types of exceptions
     * and convert them into a standardized ApiExceptionInterface format.
     *
     * @param Throwable $throwable The throwable to convert.
     * @return ApiExceptionInterface The converted ApiExceptionInterface instance.
     */
    public function convert(Throwable $throwable): ApiExceptionInterface;
}
