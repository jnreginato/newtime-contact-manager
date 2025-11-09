<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Handler;

use App\Adapter\Api\V1\Input\UpdateContactInput;
use App\Adapter\Api\V1\Presenter\ContactOutput;
use App\Application\UseCase\UpdateContactUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

use function assert;

/**
 * UpdateContactHandler class handles the updating of a contact via an API request.
 *
 * This class implements the RequestHandlerInterface and is responsible for
 * processing the incoming request to update a contact.
 */
final readonly class UpdateContactHandler implements RequestHandlerInterface
{
    /**
     * Constructor for UpdateAppHandler.
     *
     * @param UpdateContactUseCase $useCase The use case for updating a contact.
     */
    public function __construct(private UpdateContactUseCase $useCase)
    {
    }

    /**
     * Handles the incoming request to update a contact.
     *
     * @param ServerRequestInterface $request The incoming server request.
     * @return ResponseInterface The API response containing the updated contact data.
     * @throws Throwable If an error occurs during the processing of the request.
     */
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $input = $request->getAttribute(UpdateContactInput::class);
        assert($input instanceof UpdateContactInput);

        $output = ContactOutput::fromResult(($this->useCase)($input->toCommand()));

        return new JsonResponse($output->getFields(), 200);
    }
}
