<?php

declare(strict_types=1);

namespace App\Infrastructure\Log\Logger\Factory;

use App\Infrastructure\Log\Formatter\ConsoleFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Factory responsible for creating a LoggerAdapter instance configured for
 * console output (stdout) using Monolog.
 */
final class ConsoleLoggerFactory
{
    use LoggerFactoryBehavior;

    /**
     * Creates and returns a LoggerAdapter instance for console logging.
     *
     * @param ContainerInterface $container The PSR-11 container
     * @param string $requestedName Name of the service being requested
     * @return LoggerInterface Configured logger for console output
     * @throws Throwable If any other error occurs during logger creation
     */
    public function __invoke(ContainerInterface $container, string $requestedName): LoggerInterface
    {
        return $this->createLogger(
            $container,
            $requestedName,
            static function (mixed $stream, Level $level): StreamHandler {
                $handler = new StreamHandler($stream, $level);
                $handler->setFormatter(new ConsoleFormatter());

                return $handler;
            },
        );
    }
}
