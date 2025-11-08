<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Handler;

use App\Application\UseCase\CreateContactUseCase;
use App\Infrastructure\Api\Handler\RequestHandlerDecorator;
use App\Infrastructure\Api\Response\ApiThrowableResponseFactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

use function assert;

/**
 * Factory class for creating instances of CreateContactHandler.
 */
final class CreateContactHandlerFactory
{
    /**
     * Create an instance of RequestHandlerDecorator wrapping CreateContactHandler.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return RequestHandlerInterface The created RequestHandlerDecorator instance.
     * @throws Throwable If an error occurs during the creation of the handler.
     */
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $useCase = $container->get(CreateContactUseCase::class);
        assert($useCase instanceof CreateContactUseCase);

        $handler = new CreateContactHandler($useCase);

        $responseFactory = $container->get(ApiThrowableResponseFactoryInterface::class);
        assert($responseFactory instanceof ApiThrowableResponseFactoryInterface);

        return new RequestHandlerDecorator($handler, $responseFactory);
    }
}
