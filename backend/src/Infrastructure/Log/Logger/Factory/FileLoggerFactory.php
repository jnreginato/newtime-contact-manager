<?php

declare(strict_types=1);

namespace App\Infrastructure\Log\Logger\Factory;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BufferHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Factory responsible for creating a LoggerAdapter instance configured for file
 * output using Monolog.
 *
 * This logger buffers log records and only writes them to a file if a message
 * of ERROR level or higher is encountered (via FingersCrossedHandler).
 */
final class FileLoggerFactory
{
    use LoggerFactoryBehavior;

    /**
     * Creates and returns a LoggerAdapter configured for file logging.
     *
     * @param ContainerInterface $container PSR-11 container instance
     * @param string $requestedName Name of the service being requested
     * @return LoggerInterface Configured logger for file output
     * @throws Throwable If any other error occurs during logger creation
     */
    public function __invoke(ContainerInterface $container, string $requestedName): LoggerInterface
    {
        return $this->createLogger(
            $container,
            $requestedName,
            static function (mixed $stream, Level $level): BufferHandler {
                $streamHandler = new StreamHandler($stream, $level);
                $formater = new LineFormatter(
                    "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
                    'Y-m-d H:i:s.u',
                    true,
                    true,
                );
                $streamHandler->setFormatter($formater);

                return new BufferHandler($streamHandler, 100);
            },
        );
    }
}
