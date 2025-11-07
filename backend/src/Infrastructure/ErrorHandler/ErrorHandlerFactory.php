<?php

declare(strict_types=1);

namespace App\Infrastructure\ErrorHandler;

use App\Infrastructure\Config\ConfigServiceInterface;
use App\Infrastructure\ErrorHandler\Exception\ErrorHandlerConfigurationException;
use App\Infrastructure\ErrorHandler\Exception\ErrorHandlerInitializationException;
use App\Infrastructure\ErrorHandler\Exception\ListenerResolutionException;
use App\Infrastructure\ErrorHandler\Listener\ErrorListenerInterface;
use Laminas\Stratigility\Middleware\ErrorHandler;
use Psr\Container\ContainerInterface;
use Throwable;

use function array_filter;
use function assert;
use function class_exists;
use function is_array;
use function is_string;
use function method_exists;
use function sprintf;

/**
 * Factory class for creating and configuring an error handler.
 *
 * This class is responsible for initializing the error handler and attaching
 * listeners to it. It retrieves the configuration from the container and
 * handles any exceptions that may occur during the process.
 */
final class ErrorHandlerFactory
{
    /**
     * Invokes the error handler factory.
     *
     * @param ContainerInterface $container The PSR-11 container
     * @param string $serviceName The name of the error handler service
     * @param callable $callback A callable to create the error handler
     * @return ErrorHandler The configured error handler
     * @throws ErrorHandlerInitializationException If the error handler cannot be initialized
     * @throws Throwable If any other error occurs during initialization
     */
    public function __invoke(ContainerInterface $container, string $serviceName, callable $callback): ErrorHandler
    {
        if (!$serviceName) {
            throw new ErrorHandlerInitializationException('Error handler not found');
        }

        $errorHandler = $callback();

        if (!$errorHandler instanceof ErrorHandler) {
            throw new ErrorHandlerInitializationException('Callback did not return a valid ErrorHandler instance.');
        }

        $configService = $container->get(ConfigServiceInterface::class);
        assert($configService instanceof ConfigServiceInterface);

        $listeners = $this->getListeners($container, $configService);

        foreach ($listeners as $listener) {
            $errorHandler->attachListener($listener);
        }

        return $errorHandler;
    }

    /**
     * Retrieves listeners from the error handler configuration.
     *
     * @param ContainerInterface $container The PSR-11 container
     * @param ConfigServiceInterface $configService The configuration service
     * @return array<ErrorListenerInterface> The list of listeners
     */
    private function getListeners(ContainerInterface $container, ConfigServiceInterface $configService): array
    {
        $listeners = [];
        $listenerClasses = $this->extractListenersFromConfig($configService);

        foreach ($listenerClasses as $listenerClass) {
            $listeners[] = $this->getListenerInstance($container, $listenerClass);
        }

        return $listeners;
    }

    /**
     * Extracts listeners from the error handler configuration.
     *
     * @return list<class-string> The list of listener class names
     * @throws ErrorHandlerConfigurationException If the configuration is invalid
     */
    private function extractListenersFromConfig(ConfigServiceInterface $configService): array
    {
        $listeners = $configService->get('error_handler.listeners');

        if (!is_array($listeners) || array_filter($listeners, static fn ($listener) => !is_string($listener))) {
            throw new ErrorHandlerConfigurationException('Invalid error handler configuration.');
        }

        /** @var list<class-string> $listeners */
        foreach ($listeners as $listener) {
            $this->validateListener($listener);
        }

        return $listeners;
    }

    /**
     * Validates if the listener is callable.
     *
     * @param string $listenerClass The listener class name
     * @throws ListenerResolutionException If the listener is not callable
     */
    private function validateListener(string $listenerClass): void
    {
        if (!class_exists($listenerClass)) {
            throw new ListenerResolutionException(sprintf('Listener class "%s" does not exist.', $listenerClass));
        }

        if (!method_exists($listenerClass, '__invoke')) {
            throw new ListenerResolutionException(sprintf('Listener class "%s" is not invokable.', $listenerClass));
        }
    }

    /**
     * Retrieves the listener instance from the container.
     *
     * @param ContainerInterface $container The PSR-11 container
     * @param string $listenerClass The listener class name
     * @return ErrorListenerInterface The listener instance
     * @throws ListenerResolutionException If the listener cannot be resolved
     */
    private function getListenerInstance(ContainerInterface $container, string $listenerClass): ErrorListenerInterface
    {
        try {
            $instance = $container->get($listenerClass);

            if (!$instance instanceof ErrorListenerInterface) {
                throw new ListenerResolutionException(
                    sprintf('%s is not an instance of ErrorListenerInterface.', $listenerClass),
                );
            }
        } catch (Throwable $e) {
            throw new ListenerResolutionException($e->getMessage(), (int) $e->getCode(), $e->getPrevious());
        }

        return $instance;
    }
}
