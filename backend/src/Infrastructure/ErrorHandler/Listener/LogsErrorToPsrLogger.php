<?php

declare(strict_types=1);

namespace App\Infrastructure\ErrorHandler\Listener;

use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Provides reusable logic for error logging in error listeners.
 *
 * This trait provides a common interface and shared functionality for error
 * listeners in the application.
 * It handles logging of errors and provides a method to be invoked when an
 * error occurs.
 */
trait LogsErrorToPsrLogger
{
    protected LoggerInterface $logger;

    /**
     * Sets the logger instance.
     *
     * This method is used to inject a PSR-3 logger into the error listener.
     * It allows for flexibility in choosing the logger implementation and
     * configuration.
     *
     * @param LoggerInterface $logger The logger instance to set.
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Invokes the error listener.
     *
     * This method is called when an error occurs.
     * It logs the error details, including the request URI, status code, and
     * response body.
     *
     * @param Throwable $error The error that occurred.
     * @param ServerRequestInterface $request The server request associated with the error.
     * @param ResponseInterface $response The response object.
     */
    #[Override]
    public function __invoke(Throwable $error, ServerRequestInterface $request, ResponseInterface $response): void
    {
        $uri = $request->getUri();
        $path = $uri->getPath();
        $version = $request->getProtocolVersion();
        $statusCode = $response->getStatusCode();
        $contents = $this->extractResponseBody($response);

        $this->logger->error(
            "HTTP/$version $statusCode $path",
            array_merge(
                [
                    'error_message' => $error->getMessage(),
                    'response_body' => $contents,
                    'status_code' => $statusCode,
                    'url' => (string) $uri,
                ],
                $this->getContext(),
            ),
        );
    }

    /**
     * Extracts the response body from the response object.
     *
     * This method checks if the response body is readable and its size is less
     * than 5000 bytes.
     * If so, it returns the contents of the response body; otherwise, it
     * returns a message indicating that the response is too long.
     *
     * @param ResponseInterface $response The response object.
     * @return string The contents of the response body or a message indicating that it's too long.
     */
    private function extractResponseBody(ResponseInterface $response): string
    {
        $body = $response->getBody();

        return $body->isReadable() && $body->getSize() < 5000
            ? $body->getContents()
            : 'Too long response';
    }

    /**
     * Retrieves the context for the error log.
     *
     * This method can be overridden in subclasses to provide additional
     * context information for the error log.
     *
     * @return array<string, string> An array of context information for the error log.
     */
    protected function getContext(): array
    {
        return [];
    }
}
