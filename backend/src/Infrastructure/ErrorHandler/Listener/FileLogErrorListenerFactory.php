<?php

declare(strict_types=1);

namespace App\Infrastructure\ErrorHandler\Listener;

use App\Infrastructure\Log\Logger\Factory\LoggerFactoryBehavior;
use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Container\ContainerInterface;
use Throwable;

/**
 * Factory for creating FileLogErrorListener instances.
 *
 * This factory retrieves the logger configuration from the service container,
 * validates it and creates a FileLogErrorListener instance with the appropriate
 * logger.
 * It ensures that the logger type and options are correctly configured before
 * creating the listener.
 */
final class FileLogErrorListenerFactory
{
    use LoggerFactoryBehavior;

    /**
     * Invokes the factory to create a FileLogErrorListener instance.
     *
     * @param ContainerInterface $container The PSR-11 container
     * @return ErrorListenerInterface The created FileLogErrorListener instance
     * @throws Throwable
     */
    public function __invoke(ContainerInterface $container): ErrorListenerInterface
    {
        $logger = $this->createLogger(
            $container,
            'log.file_log_error_listener',
            static function (mixed $stream, Level $level): FingersCrossedHandler {
                $handler = new StreamHandler($stream, $level);
                $handler->pushProcessor(new PsrLogMessageProcessor());

                return new FingersCrossedHandler($handler, new ErrorLevelActivationStrategy(Level::Error));
            },
        );

        return new FileLogErrorListener($logger);
    }
}
