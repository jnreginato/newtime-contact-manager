<?php

declare(strict_types=1);

namespace App\Adapter\Api;

use App\Adapter\Api\Middleware\ValidatedInputMiddlewareFactory;
use App\Adapter\Api\V1\Handler\CreateContactHandler;
use App\Adapter\Api\V1\Handler\CreateContactHandlerFactory;
use App\Adapter\Api\V1\Handler\ListContactsHandler;
use App\Adapter\Api\V1\Handler\ListContactsHandlerFactory;
use App\Adapter\Api\V1\Handler\ReadContactHandler;
use App\Adapter\Api\V1\Handler\ReadContactHandlerFactory;
use App\Adapter\Api\V1\Input\CreateContactInput;
use App\Adapter\Api\V1\Input\ListContactsInput;
use App\Adapter\Api\V1\Input\ReadContactInput;

/**
 * The configuration provider for the Api Adapter module.
 *
 * @phpstan-type ServiceManagerConfiguration array{
 *      factories?: array<class-string|string, class-string|callable|object>,
 *  }
 */
final readonly class ConfigProvider
{
    /**
     * Returns the configuration array.
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
     * Returns the dependencies for the Api Adapter module.
     *
     * @return ServiceManagerConfiguration
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                // Middleware to bind and validate input
                'ListContactsMiddleware' => new ValidatedInputMiddlewareFactory(ListContactsInput::class),
                'ReadContactMiddleware' => new ValidatedInputMiddlewareFactory(ReadContactInput::class),
                'CreateContactMiddleware' => new ValidatedInputMiddlewareFactory(CreateContactInput::class),

                // Handlers
                ListContactsHandler::class => ListContactsHandlerFactory::class,
                ReadContactHandler::class => ReadContactHandlerFactory::class,
                CreateContactHandler::class => CreateContactHandlerFactory::class,
            ],
        ];
    }
}
