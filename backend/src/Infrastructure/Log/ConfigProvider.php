<?php

declare(strict_types=1);

namespace App\Infrastructure\Log;

use App\Infrastructure\Log\Logger\Factory\ConsoleLoggerFactory;
use App\Infrastructure\Log\Logger\Factory\FileLoggerFactory;

use function defined;

/**
 * ConfigProvider for the Log module.
 *
 * This class provides configuration for the Log module, including:
 * - Logger factories for different types of loggers (e.g., console, file).
 * - Configuration for log destinations, including channel, level, and stream.
 * - Resource usage and start execution time.
 *
 * @psalm-type ServiceManagerConfiguration = array{
 *     abstract_factories?: array<string, callable|class-string>,
 *     aliases?: array<string, string>,
 *     delegators?: array<string, array<array-key, class-string>>,
 *     factories?: array<string, callable|class-string>,
 *     initializers?: array<string, class-string>,
 *     invokables?: array<string, class-string>,
 *     lazy_services?: array<string, class-string>,
 *     services?: array<string, mixed>,
 *     shared?:array<string, bool>,
 *     shared_by_default?: bool,
 *     ...
 * }
 * @psalm-type LoggerData = array{
 *     channel: string,
 *     level: string,
 *     stream: string
 * }
 * @psalm-type Config = array{
 *      resource_usage: mixed,
 *      start_execution_time: float|int,
 *      ...<string, LoggerData>,
 *  }
 */
final class ConfigProvider
{
    /**
     * Invokes the configuration provider.
     *
     * @return array{
     *     dependencies: ServiceManagerConfiguration,
     *     log: Config,
     * }
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'log' => $this->getConfig(),
        ];
    }

    /**
     * Returns the dependencies for the Log module.
     *
     * @return ServiceManagerConfiguration
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                'log.console' => ConsoleLoggerFactory::class,
                'log.file' => FileLoggerFactory::class,
            ],
        ];
    }

    /**
     * Returns the configuration for the Log module.
     *
     *  The configuration array includes:
     *  - One or more log destinations (e.g., 'console', 'file', etc.), each being an associative array with:
     *      - 'channel': string
     *      - 'level': string
     *      - 'stream': string (e.g., file path or PHP stream)
     *  - 'resource_usage': mixed (usually array, based on the RESOURCE_USAGE constant)
     *  - 'start_execution_time': float|int (based on the START_EXECUTION_TIME constant)
     *
     *  Example:
     *  [
     *      'console' => ['channel' => 'App', 'level' => 'debug', 'stream' => 'php://stdout'],
     *      'file' => ['channel' => 'App', 'level' => 'info', 'stream' => 'log/app.log'],
     *      'resource_usage' => [...],
     *      'start_execution_time' => 1715889600.1234,
     *  ]
     *
     * @return Config
     */
    public function getConfig(): array
    {
        return [
            'console' => [
                'channel' => 'NewTime',
                'level' => 'debug',
                'stream' => 'php://stdout',
            ],
            'file' => [
                'channel' => 'NewTime',
                'level' => 'debug',
                'stream' => 'log/application.log',
            ],
            'file_log_error_listener' => [
                'channel' => 'NewTime',
                'level' => 'debug',
                'stream' => 'log/error.log',
            ],
            'resource_usage' => defined('RESOURCE_USAGE') ? RESOURCE_USAGE : [],
            'start_execution_time' => defined('START_EXECUTION_TIME') ? START_EXECUTION_TIME : 0,
        ];
    }
}
