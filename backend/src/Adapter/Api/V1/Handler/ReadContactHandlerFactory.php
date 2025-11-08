<?php

declare(strict_types=1);

namespace App\Adapter\Api\V1\Handler;

use App\Application\UseCase\ReadContactUseCase;
use App\Infrastructure\Api\Handler\RequestHandlerDecorator;
use App\Infrastructure\Api\Response\ApiThrowableResponseFactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

use function assert;

/**
 * Factory class for creating instances of ReadContactHandler.
 *
 * This class is responsible for creating a RequestHandlerDecorator instance
 * wrapping ReadContactHandler with the necessary dependencies.
 */
final readonly class ReadContactHandlerFactory
{
    /**
     * Create an instance of RequestHandlerDecorator wrapping ReadContactHandler.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return RequestHandlerInterface The created RequestHandlerDecorator instance.
     * @throws Throwable If an error occurs during the creation of the handler.
     */
    public function __invoke(ContainerInterface $container): RequestHandlerInterface
    {
        $useCase = $container->get(ReadContactUseCase::class);
        assert($useCase instanceof ReadContactUseCase);

        $handler = new ReadContactHandler($useCase);

        $responseFactory = $container->get(ApiThrowableResponseFactoryInterface::class);
        assert($responseFactory instanceof ApiThrowableResponseFactoryInterface);

        return new RequestHandlerDecorator($handler, $responseFactory);
    }
}
