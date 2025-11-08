<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Handler;

use App\Adapter\Api\V1\Input\ListContactsInput;
use App\Adapter\Api\V1\Presenter\ContactOutput;
use App\Application\UseCase\ListContactsUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

use function array_map;
use function assert;

/**
 * ListContactsHandler class handles the listing of contacts via an API request.
 *
 * This class implements the RequestHandlerInterface and is responsible for
 * processing the incoming request to list contacts.
 */
final readonly class ListContactsHandler implements RequestHandlerInterface
{
    /**
     * Constructor for ListContactsHandler.
     *
     * @param ListContactsUseCase $useCase The use case for listing contacts.
     */
    public function __construct(private ListContactsUseCase $useCase)
    {
    }

    /**
     * Handles the incoming request to list contacts.
     *
     * @param ServerRequestInterface $request The incoming server request.
     * @return ResponseInterface The API response containing the list of contacts.
     * @throws Throwable If an error occurs during the processing of the request.
     */
    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $input = $request->getAttribute(ListContactsInput::class);
        assert($input instanceof ListContactsInput);

        $output = ($this->useCase)($input->toQuery());

        $data = array_map(
            static fn (ContactOutput $contactOutput): array => $contactOutput->getFields(),
            $output->getData(),
        );

        return new JsonResponse(
            [
                'data' => $data,
                'meta' => [
                    'count' => $output->getCount(),
                    'currentPage' => $output->getCurrentPage(),
                    'perPage' => $output->getPerPage(),
                    'totalPages' => $output->getTotalPages(),
                    'totalItems' => $output->getTotalItems(),
                ],
            ],
            200,
        );
    }
}
