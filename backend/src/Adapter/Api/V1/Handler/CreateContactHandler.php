<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Handler;

use App\Adapter\Api\V1\Input\CreateContactInput;
use App\Adapter\Api\V1\Presenter\ContactOutput;
use App\Application\UseCase\CreateContactUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

use function assert;

/**
 * CreateContactHandler class handles the creation of a contact via an API request.
 *
 * This class implements the RequestHandlerInterface and is responsible for
 * processing the incoming request to create a contact.
 */
final readonly class CreateContactHandler implements RequestHandlerInterface
{
    /**
     * Constructor for CreateContactHandler.
     *
     * @param CreateContactUseCase $useCase The use case for creating a contact.
     */
    public function __construct(private CreateContactUseCase $useCase)
    {
    }

    /**
     * Handles the incoming request to create a contact.
     *
     * @param ServerRequestInterface $request The incoming server request.
     * @return ResponseInterface The API response containing the created contact data.
     * @throws Throwable If an error occurs during the processing of the request.
     */
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $input = $request->getAttribute(CreateContactInput::class);
        assert($input instanceof CreateContactInput);

        $output = ContactOutput::fromResult(($this->useCase)($input->toCommand()));

        return new JsonResponse($output->getFields(), 201);
    }
}
