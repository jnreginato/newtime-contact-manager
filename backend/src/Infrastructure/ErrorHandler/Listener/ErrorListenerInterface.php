<?php

declare(strict_types=1);

namespace App\Infrastructure\ErrorHandler\Listener;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * This interface defines the contract for the listeners in the error handling.
 *
 * Error listeners are responsible for handling errors that occur during the
 * request lifecycle.
 * They can log errors, send notifications, or perform other actions based on
 * the error type and context.
 */
interface ErrorListenerInterface
{
    /**
     * Invokes the error listener.
     *
     * This method is called when an error occurs.
     * The listener can perform actions such as logging the error, sending
     * notifications, or modifying the response.
     *
     * @param Throwable $error The error that occurred.
     * @param ServerRequestInterface $request The server request associated with the error.
     * @param ResponseInterface $response The response object.
     */
    public function __invoke(Throwable $error, ServerRequestInterface $request, ResponseInterface $response): void;
}
