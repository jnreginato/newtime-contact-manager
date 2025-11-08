<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\EntityManager;

use App\Infrastructure\Config\ConfigServiceInterface;
use App\Infrastructure\Config\ConfigTypes;
use App\Infrastructure\Config\ValidatorOptions;
use App\Infrastructure\Persistence\Doctrine\Connection\ConnectionAdapter;
use App\Infrastructure\Persistence\Doctrine\Filter\SoftDeleteFilter;
use App\Infrastructure\Persistence\Doctrine\Types\CustomTypes;
use App\Infrastructure\Persistence\Doctrine\Types\UtcDateTimeImmutableType;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Doctrine\ORM\ORMSetup;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Throwable;

use function assert;

/**
 * EntityManagerAdapterFactory class.
 *
 * This class is responsible for creating instances of the EntityManager.
 * It retrieves the connection parameters from the configuration service and
 * creates a new EntityManager instance with the provided parameters.
 *
 * @psalm-type CacheParams = array{
 *     enabled: bool,
 *     adapter: array{name: string, options: array<string, mixed>}
 * }
 * @psalm-type Params = array{
 *     entities_path: array<string>,
 *     is_dev_mode: bool,
 *     proxy_dir: string,
 *     cache: CacheParams|null,
 * }
 * @psalm-suppress MissingConstructor
 * @psalm-suppress UndefinedInterfaceMethod
 * @SuppressWarnings(PHPMD)
 */
final class EntityManagerAdapterFactory
{
    /**
     * Invokes the factory to create an EntityManager instance.
     *
     * @param ContainerInterface $container The PSR-11 container
     * @param string $requestedName The requested name for the EntityManager
     * @return EntityManagerAdapterInterface The created EntityManager instance
     * @throws Throwable If any error occurs during EntityManager creation
     */
    public function __invoke(ContainerInterface $container, string $requestedName): EntityManagerAdapterInterface
    {
        $configService = $container->get(ConfigServiceInterface::class);
        assert($configService instanceof ConfigServiceInterface);

        $appConfig = $this->getApplicationConfig($configService, $requestedName);

        // Get the Doctrine connection
        $connectionAdapter = $container->get($this->getConnectionConfigPath($requestedName));
        assert($connectionAdapter instanceof ConnectionAdapter);
        $doctrineConnection = $connectionAdapter->connection;

        // Get the DoctrineConfiguration
        $doctrineConfig = ORMSetup::createAttributeMetadataConfiguration(
            $appConfig['entities_path'],
            $appConfig['is_dev_mode'],
            $appConfig['proxy_dir'],
            $this->createCacheInstance($container, $appConfig['cache']),
        );
        $doctrineConfig->setNamingStrategy(new UnderscoreNamingStrategy());
        $doctrineConfig->addFilter('soft_delete', SoftDeleteFilter::class);

        if (!Type::hasType(CustomTypes::DATETIME_IMMUTABLE_UTC)) {
            Type::addType(CustomTypes::DATETIME_IMMUTABLE_UTC, UtcDateTimeImmutableType::class);
        }

        $entityManager = new EntityManager($doctrineConnection, $doctrineConfig, new EventManager());

        return new EntityManagerAdapter($entityManager);
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
     * Retrieves the connection configuration path.
     *
     * @param string $configPath The original configuration path
     * @return string The connection configuration path
     */
    private function getConnectionConfigPath(string $configPath): string
    {
        $segments = explode('.', $configPath);
        $segments[array_key_last($segments)] = 'connection';

        return implode('.', $segments);
    }

    /**
     * Creates a cache instance based on the provided configuration.
     *
     * @param CacheParams|null $config The cache configuration
     * @return CacheItemPoolInterface|null The cache instance or null if not enabled
     */
    private function createCacheInstance(ContainerInterface $container, ?array $config): ?CacheItemPoolInterface
    {
        if ($config === null || !$config['enabled']) {
            return null;
        }

        // @phpstan-ignore-next-line
        $adapter = $container->build($config['adapter']['name'], $config['adapter']['options']);
        assert($adapter instanceof CacheItemPoolInterface);

        return $adapter;
    }
}
