<?php

declare(strict_types=1);

namespace App\Infrastructure\Log\Logger;

use Override;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Stringable;

/**
 * Adapter for providing a consistent logging tool using a PSR-3: Logger
 * implementation.
 *
 * This class implements the LoggerInterface and provides methods for logging
 * messages at various levels (debug, info, notice, warning, error, critical,
 * alert, emergency).
 * It wraps a PSR-3 logger and allows easy integration with other components.
 *
 * @psalm-suppress UndefinedInterfaceMethod
 */
final readonly class LoggerAdapter implements LoggerInterface
{
    /**
     * Constructor for LoggerAdapter.
     *
     * @param LoggerInterface $logger The PSR-3 logger to be used for logging.
     */
    public function __construct(public LoggerInterface $logger)
    {
    }

    /**
     * The System is unusable.
     *
     * @param string|Stringable $message The log message
     * @param array<array-key, mixed> $context Contextual data to be included in the log
     */
    #[Override]
    public function emergency(string | Stringable $message, array $context = []): void
    {
        $this->logger->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string|Stringable $message The log message
     * @param array<array-key, mixed> $context Contextual data to be included in the log
     */
    #[Override]
    public function alert(string | Stringable $message, array $context = []): void
    {
        $this->logger->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string|Stringable $message The log message
     * @param array<array-key, mixed> $context Contextual data to be included in the log
     */
    #[Override]
    public function critical(string | Stringable $message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string|Stringable $message The log message
     * @param array<array-key, mixed> $context Contextual data to be included in the log
     */
    #[Override]
    public function error(string | Stringable $message, array $context = []): void
    {
        $this->logger->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string|Stringable $message The log message
     * @param array<array-key, mixed> $context Contextual data to be included in the log
     */
    #[Override]
    public function warning(string | Stringable $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string|Stringable $message The log message
     * @param array<array-key, mixed> $context Contextual data to be included in the log
     */
    #[Override]
    public function notice(string | Stringable $message, array $context = []): void
    {
        $this->logger->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string|Stringable $message The log message
     * @param array<array-key, mixed> $context Contextual data to be included in the log
     */
    #[Override]
    public function info(string | Stringable $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string|Stringable $message The log message
     * @param array<array-key, mixed> $context Contextual data to be included in the log
     */
    #[Override]
    public function debug(string | Stringable $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level The log level (e.g., 'info', 'error', etc.)
     * @param string|Stringable $message The log message
     * @param array<array-key, mixed> $context Contextual data to be included in the log
     * @throws InvalidArgumentException If the log level is not valid
     */
    #[Override]
    public function log(mixed $level, string | Stringable $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }

    /**
     * Closes the logger.
     *
     * This method is used to close the logger and release any resources it may
     * be holding - flushing the logger ensures that all log messages are
     * written to the underlying storage (e.g., file, database, etc.).
     */
    public function close(): void
    {
        $this->logger->close();
    }
}
