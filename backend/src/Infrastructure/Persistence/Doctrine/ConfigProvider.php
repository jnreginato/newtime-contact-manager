<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use App\Application\Support\Transaction\TransactionManagerInterface;
use App\Infrastructure\Log\Logger\Factory\FileLoggerFactory;
use App\Infrastructure\Persistence\Doctrine\Connection\ConnectionAdapterFactory;
use App\Infrastructure\Persistence\Doctrine\EntityManager\EntityManagerAdapterFactory;
use App\Infrastructure\Persistence\Doctrine\Filter\SoftDeleteFilter;
use App\Infrastructure\Persistence\Doctrine\Filter\SoftDeleteFilterInterface;
use App\Infrastructure\Persistence\Doctrine\Transaction\TransactionManagerFactory;
use Doctrine\DBAL\Connections\PrimaryReadReplicaConnection;
use Doctrine\DBAL\Driver\PDO\SQLite\Driver as SqliteDriver;
use PDO;

use function getenv;

/**
 * ConfigProvider for the Persistence Doctrine module.
 *
 * This class provides configuration for the Persistence Doctrine module,
 * including dependencies and factories.
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
 * @psalm-type DefaultOptions = array{
 *     charset: string,
 *     collate: string,
 *     comment: string,
 *     engine: string,
 * }
 * @psalm-type ConnectionParam = array{
 *     charset?: string,
 *     dbname?: string,
 *     host?: string,
 *     password?: string,
 *     port?: int,
 *     user?: string,
 *     path?: string,
 * }
 * @psalm-type Connection = array{
 *     application_name?: string,
 *     defaultTableOptions?: DefaultOptions,
 *     driver: string,
 *     driverClass: class-string,
 *     driverOptions: array<array-key, mixed>,
 *     keepReplica: bool,
 *     persistent: bool,
 *     primary: ConnectionParam,
 *     replica: array<ConnectionParam>,
 *     wrapperClass: class-string,
 * }
 * @psalm-type EntityManager = array{
 *     cache?: array{
 *         adapter: array{
 *             name: string,
 *             options: array{
 *                 default_lifetime: int,
 *                 namespace: string,
 *             }
 *         },
 *         enabled: bool,
 *     },
 *     entities_path: array<string>,
 *     is_dev_mode: bool,
 *     proxy_dir: string,
 * }
 * @psalm-type Logger = array{
 *     channel: string,
 *     level: string,
 *     stream: string,
 * }
 * @psalm-type ConfigItem = array{
 *     connection: Connection,
 *     entity_manager?: EntityManager,
 *     logger?: Logger,
 * }
 * @psalm-type Config = array<string, ConfigItem>
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
 */
final class ConfigProvider
{
    /**
     * Invokes the configuration provider.
     *
     * @return array{
     *     dependencies: ServiceManagerConfiguration,
     *     persistence: array{doctrine: Config},
     * }
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'persistence' => [
                'doctrine' => $this->getConfig(),
            ],
        ];
    }

    /**
     * Returns the dependencies for the Persistence Doctrine module.
     *
     * @return ServiceManagerConfiguration
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                'persistence.doctrine.core_sqlite.connection' => ConnectionAdapterFactory::class,
                'persistence.doctrine.core_sqlite.entity_manager' => EntityManagerAdapterFactory::class,
                'persistence.doctrine.core_sqlite.logger' => FileLoggerFactory::class,
                TransactionManagerInterface::class => TransactionManagerFactory::class,
            ],
            'invokables' => [
                SoftDeleteFilterInterface::class => SoftDeleteFilter::class,
            ],
        ];
    }

    /**
     * Returns the configuration for the Persistence Doctrine module.
     *
     * @return Config The configuration for the Persistence Doctrine module.
     */
    public function getConfig(): array
    {
        return [
            'core_sqlite' => $this->getCoreSqliteConfig(),
        ];
    }

    /**
     * Returns the configuration for the core SQLite database.
     *
     * @return ConfigItem The configuration for the core SQLite database.
     */
    private function getCoreSqliteConfig(): array
    {
        return [
            'connection' => [
                'application_name' => (string) getenv('APPLICATION_NAME'),
                'defaultTableOptions' => [
                    'charset' => 'utf8mb4',
                    'collate' => 'utf8mb4_unicode_ci',
                    'comment' => 'Core database',
                    'engine' => 'InnoDB',
                ],
                'driver' => 'pdo_sqlite',
                'driverClass' => SqliteDriver::class,
                'driverOptions' => [
                    PDO::ATTR_AUTOCOMMIT => true,
                    PDO::ATTR_CASE => PDO::CASE_LOWER,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                    PDO::ATTR_TIMEOUT => 5,
                ],
                'keepReplica' => false,
                'persistent' => false,
                'primary' => [
                    'path' => getenv('SQLITE_PATH') ?: '/var/sqlite/database.sqlite',
                ],
                'replica' => [
                    [
                        'path' => getenv('SQLITE_PATH') ?: '/var/sqlite/database.sqlite',
                    ],
                ],
                'wrapperClass' => PrimaryReadReplicaConnection::class,
            ],
            'entity_manager' => $this->getEntityManagerConfig(),
            'logger' => $this->getLoggerConfig(),
        ];
    }

    /**
     * Returns the configuration for the EntityManager.
     *
     * @return EntityManager The configuration for the EntityManager.
     */
    private function getEntityManagerConfig(): array
    {
        return [
            'cache' => [
                'adapter' => [
                    'name' => 'array',
                    'options' => [
                        'default_lifetime' => 3600,
                        'namespace' => 'doctrine_metadata',
                    ],
                ],
                'enabled' => (bool) getenv('CACHE_ENABLED') === true,
            ],
            'entities_path' => ['src/Domain/Entity'],
            'is_dev_mode' => getenv('APPLICATION_ENV') === 'development',
            'proxy_dir' => 'cache/doctrine/proxies/core_sqlite',
        ];
    }

    /**
     * Returns the configuration for the logger.
     *
     * @return Logger The configuration for the logger.
     */
    private function getLoggerConfig(): array
    {
        return [
            'channel' => 'Doctrine',
            'level' => 'debug',
            'stream' => 'log/core_sqlite/doctrine.log',
        ];
    }
}
