<?php

declare(strict_types=1);

namespace App\Infrastructure\ErrorHandler;

use App\Infrastructure\ErrorHandler\Listener\FileLogErrorListener;
use App\Infrastructure\ErrorHandler\Listener\FileLogErrorListenerFactory;
use Laminas\Stratigility\Middleware\ErrorHandler;

/**
 * ConfigProvider for the ErrorHandler module.
 *
 * This class provides configuration for the ErrorHandler module, including:
 * - Error handler factory for creating and configuring the error handler.
 * - File listener factory for creating file listeners.
 *
 * @psalm-type ServiceManagerConfiguration = array{
 *     delegators?: array<class-string, array<class-string>>,
 *     factories?: array<class-string, class-string>,
 * }
 * @psalm-type Config = array{listeners: array<class-string>}
 */
final class ConfigProvider
{
    /**
     * Invokes the configuration provider.
     *
     * @return array{
     *     dependencies: ServiceManagerConfiguration,
     *     error_handler: Config,
     * }
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'error_handler' => $this->getConfig(),
        ];
    }

    /**
     * Returns the dependencies for the ErrorHandler module.
     *
     * @return ServiceManagerConfiguration
     */
    public function getDependencies(): array
    {
        return [
            'delegators' => [
                ErrorHandler::class => [ErrorHandlerFactory::class],
            ],
            'factories' => [
                FileLogErrorListener::class => FileLogErrorListenerFactory::class,
            ],
        ];
    }

    /**
     * Returns the configuration for the ErrorHandler module.
     *
     * @return Config
     */
    public function getConfig(): array
    {
        return [
            'listeners' => [
                FileLogErrorListener::class,
            ],
        ];
    }
}
