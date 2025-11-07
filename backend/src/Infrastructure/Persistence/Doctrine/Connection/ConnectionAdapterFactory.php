<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Connection;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\Middleware;
use App\Infrastructure\Config\ConfigServiceInterface;
use App\Infrastructure\Config\ConfigTypes;
use App\Infrastructure\Config\ValidatorOptions;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

use function array_key_last;
use function assert;
use function explode;
use function implode;

/**
 * ConnectionAdapterFactory class.
 *
 * This class is responsible for creating instances of the ConnectionAdapter.
 * It retrieves the connection parameters from the configuration service and
 * creates a new ConnectionAdapter instance with the provided parameters.
 *
 * @psalm-type OverrideParams = array{
 *     application_name?: string,
 *     charset?: string,
 *     dbname?: string,
 *     defaultTableOptions?: array<string, mixed>,
 *     driver?: 'pdo_mysql' | 'pdo_sqlite',
 *     driverClass?: class-string<Driver>,
 *     driverOptions?: array<array-key, mixed>,
 *     host?: string,
 *     memory?: bool,
 *     password?: string,
 *     path?: string,
 *     persistent?: bool,
 *     port?: int,
 *     serverVersion?: string,
 *     sessionMode?: int,
 *     user?: string,
 *     unix_socket?: string,
 *     wrapperClass?: class-string<Connection>,
 * }
 * @psalm-type Params = array{
 *     application_name?: string,
 *     charset?: string,
 *     dbname?: string,
 *     defaultTableOptions?: array<string, mixed>,
 *     driver?: 'pdo_mysql' | 'pdo_sqlite',
 *     driverClass?: class-string<Driver>,
 *     driverOptions?: array<array-key, mixed>,
 *     host?: string,
 *     keepReplica?: bool,
 *     memory?: bool,
 *     password?: string,
 *     path?: string,
 *     persistent?: bool,
 *     port?: int,
 *     primary?: OverrideParams,
 *     replica?: array<OverrideParams>,
 *     serverVersion?: string,
 *     sessionMode?: int,
 *     user?: string,
 *     wrapperClass?: class-string<Connection>,
 *     unix_socket?: string,
 * }
 * @psalm-suppress MissingConstructor
 */
final class ConnectionAdapterFactory
{
    /**
     * Creates and returns a ConnectionAdapter instance.
     *
     * @param ContainerInterface $container The container instance
     * @param string $requestedName The requested name for the connection
     * @return ConnectionAdapter The created ConnectionAdapter instance
     * @throws Throwable If an error occurs while creating the connection
     */
    public function __invoke(ContainerInterface $container, string $requestedName): ConnectionAdapter
    {
        $configService = $container->get(ConfigServiceInterface::class);
        assert($configService instanceof ConfigServiceInterface);

        $appConfig = $this->getApplicationConfig($configService, $requestedName);

        $doctrineConfig = new Configuration();

        $loggerConfigPath = $this->getLoggerConfigPath($requestedName);

        if ($loggerConfigPath && $container->has($loggerConfigPath)) {
            $logger = $container->get($loggerConfigPath);
            assert($logger instanceof LoggerInterface);

            $doctrineConfig->setMiddlewares([new Middleware($logger)]);
        }

        return new ConnectionAdapter(DriverManager::getConnection($appConfig, $doctrineConfig));
    }

    /**
     * Retrieves the application parameters from the configuration service.
     *
     * @param ConfigServiceInterface $configService The configuration service instance
     * @param string $configPath The configuration path to retrieve the parameters from
     * @return Params The application parameters
     * @throws Throwable If an error occurs while retrieving the parameters
     */
    private function getApplicationConfig(ConfigServiceInterface $configService, string $configPath): array
    {
        // @phpstan-ignore-next-line
        return $configService->get(
            $configPath,
            null,
            new ValidatorOptions(
                required: true,
                notEmpty: true,
                type: ConfigTypes::ARRAY,
            ),
        );
    }

    /**
     * Retrieves the logger configuration path.
     *
     * This method constructs the logger configuration path by replacing the
     * last segment of the current configuration path with 'logger'.
     *
     * @param string $path The current configuration path
     * @return string The logger configuration path
     */
    private function getLoggerConfigPath(string $path): string
    {
        $segments = explode('.', $path);
        $segments[array_key_last($segments)] = 'logger';

        return implode('.', $segments);
    }
}
