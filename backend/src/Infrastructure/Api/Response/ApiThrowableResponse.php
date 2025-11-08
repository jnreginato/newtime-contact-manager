<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\InjectContentTypeTrait;
use Laminas\Diactoros\Stream;
use Override;
use Throwable;

/**
 * Class JsonApiThrowableResponse.
 *
 * This class represents a JSON API response that encapsulates a Throwable.
 * It extends the JsonApiResponse class and implements the JsonApiThrowableResponseInterface.
 * The response includes the Throwable details, content, and HTTP status code.
 */
final class ApiThrowableResponse extends Response implements ApiThrowableResponseInterface
{
    use InjectContentTypeTrait;

    /**
     * The Throwable instance associated with this response.
     */
    private Throwable $throwable;

    /**
     * Constructor.
     *
     * Initializes the response with the given Throwable, content, and status code.
     *
     * @param Throwable $throwable The Throwable to include in the response.
     * @param string $content The content of the response.
     * @param int $status The HTTP status code for the response.
     */
    public function __construct(Throwable $throwable, string $content, int $status)
    {
        $body = new Stream('php://temp', 'wb+');

        $body->write($content);
        $body->rewind();

        // Inject content-type even when there is no content otherwise
        // it would be set to 'text/html' by PHP/Web server/Browser
        $headers = $this->injectContentType('application/json', []);

        parent::__construct($body, $status, $headers);

        // Store the Throwable instance for later retrieval
        $this->throwable = $throwable;
    }

    /**
     * Returns the Throwable associated with this response.
     *
     * This method provides access to the original Throwable that caused the
     * response, allowing for further handling or logging if necessary.
     *
     * @return Throwable The Throwable associated with this response.
     */
    #[Override]
    public function getThrowable(): Throwable
    {
        return $this->throwable;
    }
}
