<?php

declare(strict_types=1);

namespace App\Adapter\Api\Middleware;

use App\Infrastructure\Api\Request\InputInterface;
use App\Infrastructure\Api\Request\Parser\InputParserInterface;
use App\Infrastructure\Api\Request\Validation\InputValidatorInterface;
use App\Infrastructure\Api\Response\ApiThrowableResponseFactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function assert;

/**
 * Factory class for creating instances of ValidatedInputMiddleware.
 *
 * This class is responsible for creating a ValidatedInputMiddleware instance
 * with the necessary dependencies.
 */
final readonly class ValidatedInputMiddlewareFactory
{
    /**
     * Constructor for ValidatedInputMiddlewareFactory.
     *
     * @param class-string<InputInterface> $dtoClass The fully qualified class name of the DTO to bind and validate.
     */
    public function __construct(private string $dtoClass)
    {
    }

    /**
     * Create an instance of ValidatedInputMiddleware.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return ValidatedInputMiddleware The created ValidatedInputMiddleware instance.
     * @throws ContainerExceptionInterface If an error occurs while retrieving dependencies.
     * @throws NotFoundExceptionInterface If a dependency is not found in the container.
     */
    public function __invoke(ContainerInterface $container): ValidatedInputMiddleware
    {
        $inputParser = $container->get(InputParserInterface::class);
        assert($inputParser instanceof InputParserInterface);

        $inputValidator = $container->get(InputValidatorInterface::class);
        assert($inputValidator instanceof InputValidatorInterface);

        $responseFactory = $container->get(ApiThrowableResponseFactoryInterface::class);
        assert($responseFactory instanceof ApiThrowableResponseFactoryInterface);

        return new ValidatedInputMiddleware($this->dtoClass, $inputParser, $inputValidator, $responseFactory);
    }
}
