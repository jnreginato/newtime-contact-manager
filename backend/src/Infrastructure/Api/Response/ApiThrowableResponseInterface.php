<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Interface for responses that encapsulate a Throwable.
 */
interface ApiThrowableResponseInterface extends ResponseInterface
{
    /**
     * Retrieves the Throwable instance associated with this response.
     *
     * @return Throwable The Throwable instance.
     */
    public function getThrowable(): Throwable;
}
