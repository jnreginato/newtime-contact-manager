<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Handler;

use App\Adapter\Api\V1\Input\DeleteContactInput;
use App\Application\UseCase\DeleteContactUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

use function assert;

/**
 * DeleteContactHandler class handles the deletion of a contact via an API request.
 *
 * This class implements the RequestHandlerInterface and is responsible for
 * processing the incoming request to delete a contact.
 */
final readonly class DeleteContactHandler implements RequestHandlerInterface
{
    /**
     * Constructor for DeleteContactHandler.
     *
     * @param DeleteContactUseCase $useCase The use case for deleting a contact.
     */
    public function __construct(private DeleteContactUseCase $useCase)
    {
    }

    /**
     * Handles the incoming request to delete a contact.
     *
     * @param ServerRequestInterface $request The incoming server request.
     * @return ResponseInterface The API response indicating the deletion of the contact.
     * @throws Throwable If an error occurs during the processing of the request.
     */
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $input = $request->getAttribute(DeleteContactInput::class);
        assert($input instanceof DeleteContactInput);

        ($this->useCase)($input->toCommand());

        return new JsonResponse(null, 204);
    }
}
