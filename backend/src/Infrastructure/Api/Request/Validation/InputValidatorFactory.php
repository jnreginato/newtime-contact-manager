<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Validation;

use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Validation;
use Throwable;

use function assert;

/**
 * Factory class for creating instances of InputValidator.
 *
 * This class is responsible for creating an InputValidator instance with the
 * necessary dependencies, such as a validator and an error aggregator.
 */
final class InputValidatorFactory
{
    /**
     * Create an InputValidator instance.
     *
     * @param ContainerInterface $container The container to retrieve dependencies from.
     * @return InputValidatorInterface The created InputValidator instance.
     * @throws Throwable If an error occurs during the creation of the validator.
     */
    public function __invoke(ContainerInterface $container): InputValidatorInterface
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();

        $errorAggregator = $container->get(ErrorAggregatorInterface::class);
        assert($errorAggregator instanceof ErrorAggregatorInterface);

        return new InputValidator($validator, $errorAggregator);
    }
}
