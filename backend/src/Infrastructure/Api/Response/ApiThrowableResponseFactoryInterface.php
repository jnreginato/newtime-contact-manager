<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

use Throwable;

/**
 * Interface for factories that create responses based on Throwable instances.
 */
interface ApiThrowableResponseFactoryInterface
{
    /**
     * Creates a response based on the provided Throwable.
     *
     * @param Throwable $throwable The exception to handle.
     * @return ApiThrowableResponseInterface The response containing the exception details.
     */
    public function createResponse(Throwable $throwable): ApiThrowableResponseInterface;
}
