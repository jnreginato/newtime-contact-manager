<?php

declare(strict_types=1);

namespace App\Infrastructure\Api;

use App\Infrastructure\Api\Exception\ThrowableConverterFactory;
use App\Infrastructure\Api\Exception\ThrowableConverterInterface;
use App\Infrastructure\Api\Request\DataCapture\DataCollection;
use App\Infrastructure\Api\Request\DataCapture\DataCollectionInterface;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregator;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use App\Infrastructure\Api\Request\Mapper\ParametersMapperFactory;
use App\Infrastructure\Api\Request\Mapper\ParametersMapperInterface;
use App\Infrastructure\Api\Request\Parser\BodyParserFactory;
use App\Infrastructure\Api\Request\Parser\BodyParserInterface;
use App\Infrastructure\Api\Request\Parser\InputParserFactory;
use App\Infrastructure\Api\Request\Parser\InputParserInterface;
use App\Infrastructure\Api\Request\Parser\QueryParserFactory;
use App\Infrastructure\Api\Request\Parser\QueryParserInterface;
use App\Infrastructure\Api\Request\Validation\InputValidatorFactory;
use App\Infrastructure\Api\Request\Validation\InputValidatorInterface;
use App\Infrastructure\Api\Response\ApiThrowableResponseFactoryFactory;
use App\Infrastructure\Api\Response\ApiThrowableResponseFactoryInterface;

/**
 * ConfigProvider for the API module.
 *
 * @phpstan-type ServiceManagerConfiguration array{
 *     factories?: array<class-string, class-string>,
 *     invokables?: array<class-string, class-string>,
 * }
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
 */
final class ConfigProvider
{
    /**
     * Invokes the configuration provider.
     *
     * @return array{dependencies: ServiceManagerConfiguration}
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the dependencies for the API module.
     *
     * @return ServiceManagerConfiguration
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                ApiThrowableResponseFactoryInterface::class => ApiThrowableResponseFactoryFactory::class,
                BodyParserInterface::class => BodyParserFactory::class,
                InputParserInterface::class => InputParserFactory::class,
                InputValidatorInterface::class => InputValidatorFactory::class,
                ParametersMapperInterface::class => ParametersMapperFactory::class,
                QueryParserInterface::class => QueryParserFactory::class,
                ThrowableConverterInterface::class => ThrowableConverterFactory::class,
            ],
            'invokables' => [
                DataCollectionInterface::class => DataCollection::class,
                ErrorAggregatorInterface::class => ErrorAggregator::class,
            ],
        ];
    }
}
