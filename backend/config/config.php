<?php

declare(strict_types=1);

use App\Adapter;
use App\Application;
use App\Infrastructure;
use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

$cacheConfig = [
    'config_cache_path' => 'cache/config-cache.php',
];

$aggregator = new ConfigAggregator(
    [
        Mezzio\ConfigProvider::class,
        Mezzio\Helper\ConfigProvider::class,
        Mezzio\Router\ConfigProvider::class,
        Mezzio\Router\FastRouteRouter\ConfigProvider::class,
        Laminas\Diactoros\ConfigProvider::class,

        Adapter\Api\ConfigProvider::class,
        Application\ConfigProvider::class,
        Infrastructure\Api\ConfigProvider::class,
        Infrastructure\Config\ConfigProvider::class,
        Infrastructure\Log\ConfigProvider::class,

        new ArrayProvider($cacheConfig),
        new PhpFileProvider('config/autoload/global.php'),
        new PhpFileProvider('config/autoload/*.global.php'),
        new PhpFileProvider('config/autoload/local.php'),
        new PhpFileProvider('config/autoload/*.local.php'),
        new PhpFileProvider('config/autoload/development.config.php'),
        new PhpFileProvider('config/application/*.php'),
    ],
    $cacheConfig['config_cache_path'],
);

return $aggregator->getMergedConfig();
