<?php

declare(strict_types=1);

namespace App\Infrastructure\Log\Logger\Factory;

use App\Infrastructure\Config\ConfigServiceInterface;
use App\Infrastructure\Config\ConfigTypes;
use App\Infrastructure\Config\ValidatorOptions;
use App\Infrastructure\Log\Exception\InvalidLoggerConfigurationException;
use App\Infrastructure\Log\Logger\LoggerAdapter;
use App\Infrastructure\Log\Processor\MetricProcessor;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\ProcessorInterface;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

use function assert;
use function gettype;
use function in_array;
use function is_resource;
use function is_string;
use function sprintf;
use function strtolower;
use function trim;

/**
 * Trait to create and configure LoggerAdapter instances.
 *
 * This trait encapsulates the common logic required to create a LoggerAdapter.
 * It provides methods to set up the configuration service, validate
 * configuration options, and create the logger with the appropriate
 * handlers and processors.
 */
trait LoggerFactoryBehavior
{
    /**
     * Creates and returns a configured LoggerAdapter instance for logging.
     *
     * @param callable(string|resource, Level): HandlerInterface $handlerFactory
     * @return LoggerAdapter Configured logger for the specified type
     * @throws InvalidLoggerConfigurationException If the required config values are missing or invalid
     * @throws Throwable If any other error occurs during logger creation
     */
    private function createLogger(
        ContainerInterface $container,
        string $configPath,
        callable $handlerFactory,
    ): LoggerInterface {
        $configService = $this->getConfigService($container);
        $channel = $this->getChannel($configService, $configPath);
        $handler = $handlerFactory(
            $this->getValidatedStream($configService, $configPath),
            $this->getValidatedLevel($configService, $configPath),
        );
        $processors = $this->createProcessors($configService);

        $logger = new Logger($channel, [$handler], $processors);
        $logger->useMicrosecondTimestamps(true);

        return new LoggerAdapter($logger);
    }

    /**
     * Sets up the ConfigServiceInterface instance for the logger.
     *
     * @param ContainerInterface $container The PSR-11 container
     * @throws Throwable If any error occurs during ConfigServiceInterface creation
     */
    private function getConfigService(ContainerInterface $container): ConfigServiceInterface
    {
        $configService = $container->get(ConfigServiceInterface::class);
        assert($configService instanceof ConfigServiceInterface);

        return $configService;
    }

    /**
     * Retrieves the channel name from config.
     *
     * @param ConfigServiceInterface $configService The configuration service
     * @param string $configPath The configuration path for the logger
     * @return string Channel name
     */
    private function getChannel(ConfigServiceInterface $configService, string $configPath): string
    {
        // @phpstan-ignore return.type
        return $configService->get(
            $configPath . '.channel',
            null,
            new ValidatorOptions(
                required: true,
                notEmpty: true,
                type: ConfigTypes::STRING,
            ),
        );
    }

    /**
     * Validates the provided stream option.
     *
     * @param ConfigServiceInterface $configService The configuration service
     * @param string $configPath The configuration path for the logger
     * @return string|resource Validated stream value
     * @throws InvalidLoggerConfigurationException If the stream is missing or not a valid string
     */
    private function getValidatedStream(ConfigServiceInterface $configService, string $configPath): mixed
    {
        $stream = $configService->get(
            $configPath . '.stream',
            null,
            new ValidatorOptions(
                required: true,
                notEmpty: true,
            ),
        );

        if ((!is_resource($stream) && !is_string($stream)) || (is_string($stream) && trim($stream) === '')) {
            $type = gettype($stream);

            throw new InvalidLoggerConfigurationException(
                sprintf('Invalid "stream" option: expected resource or a non-empty string, got %s.', $type),
            );
        }

        return $stream;
    }

    /**
     * Validates the provided log level option.
     *
     * @param ConfigServiceInterface $configService The configuration service
     * @param string $configPath The configuration path for the logger
     * @return Level Validated log level value (as accepted by Monolog)
     * @throws InvalidLoggerConfigurationException If the level is missing or not a valid string
     */
    private function getValidatedLevel(ConfigServiceInterface $configService, string $configPath): Level
    {
        $level = $configService->get(
            $configPath . '.level',
            null,
            new ValidatorOptions(
                required: true,
                notEmpty: true,
                type: ConfigTypes::STRING,
            ),
        );

        $validLevels = ['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'];
        // @phpstan-ignore argument.type
        $normalizedLevel = strtolower($level);

        if (!in_array($normalizedLevel, $validLevels, true)) {
            throw new InvalidLoggerConfigurationException(
                sprintf(
                    'Invalid log level "%s". Accepted values: %s.',
                    // @phpstan-ignore argument.type
                    $level,
                    implode(', ', $validLevels),
                ),
            );
        }

        return Level::fromName($normalizedLevel);
    }

    /**
     * Creates and returns a set of Monolog processors for enriched logging
     * context.
     *
     * @param ConfigServiceInterface $configService The configuration service
     * @return array<ProcessorInterface> Array of Monolog processor instances
     */
    private function createProcessors(ConfigServiceInterface $configService): array
    {
        $startExecutionTime = $this->getStartExecutionTime($configService);
        $resourceUsage = $this->getResourceUsage($configService);

        return [
            new PsrLogMessageProcessor(),
            new ProcessIdProcessor(),
            new MetricProcessor($startExecutionTime, $resourceUsage),
            new MemoryUsageProcessor(),
            new MemoryPeakUsageProcessor(),
            new WebProcessor(),
        ];
    }

    /**
     * Retrieves the execution start time from config.
     *
     * @param ConfigServiceInterface $configService The configuration service
     * @return float Start execution timestamp (in microseconds)
     */
    private function getStartExecutionTime(ConfigServiceInterface $configService): float
    {
        // @phpstan-ignore return.type
        return $configService->get(
            'log.start_execution_time',
            null,
            new ValidatorOptions(
                required: true,
                notEmpty: true,
                type: ConfigTypes::FLOAT,
            ),
        );
    }

    /**
     * Retrieves the resource usage array from config.
     *
     * @param ConfigServiceInterface $configService The configuration service
     * @return array<string, int> Array of resource usage metrics
     */
    private function getResourceUsage(ConfigServiceInterface $configService): array
    {
        // @phpstan-ignore return.type
        return $configService->get(
            'log.resource_usage',
            null,
            new ValidatorOptions(
                required: true,
                notEmpty: true,
                type: ConfigTypes::ARRAY,
            ),
        );
    }
}
