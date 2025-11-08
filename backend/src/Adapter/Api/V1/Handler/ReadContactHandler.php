<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Handler;

use App\Adapter\Api\V1\Input\ReadContactInput;
use App\Adapter\Api\V1\Presenter\ContactOutput;
use App\Application\UseCase\ReadContactUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

use function assert;

/**
 * ReadContactHandler class handles the reading of a contact via an API request.
 *
 * This class implements the RequestHandlerInterface and is responsible for
 * processing the incoming request to read a contact.
 */
final readonly class ReadContactHandler implements RequestHandlerInterface
{
    /**
     * Constructor for ReadContactHandler.
     *
     * @param ReadContactUseCase $useCase The use case for reading a contact.
     */
    public function __construct(private ReadContactUseCase $useCase)
    {
    }

    /**
     * Handles the incoming request to read a contact.
     *
     * @param ServerRequestInterface $request The incoming server request.
     * @return ResponseInterface The API response containing the contact data.
     * @throws Throwable If an error occurs during the processing of the request.
     */
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $input = $request->getAttribute(ReadContactInput::class);
        assert($input instanceof ReadContactInput);

        $output = ContactOutput::fromResult(($this->useCase)($input->toQuery()));

        return new JsonResponse($output->getFields(), 200);
    }
}
