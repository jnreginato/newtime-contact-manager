#!/usr/bin/env php
<?php

declare(strict_types=1);

use Mezzio\Router\FastRouteRouter;

chdir(dirname((string) realpath(__DIR__)));

require_once 'vendor/autoload.php';

$config = include 'config/autoload/routes.global.php'; // NOSONAR

if (!isset($config['router']['fastroute'][FastRouteRouter::CONFIG_CACHE_ENABLED])) {
    echo 'No route cache file found' . PHP_EOL;
    exit(0);
}

if (!file_exists($config['router']['fastroute'][FastRouteRouter::CONFIG_CACHE_FILE])) {
    printf(
        "Configured route cache file '%s' not found%s",
        $config['router']['fastroute'][FastRouteRouter::CONFIG_CACHE_FILE],
        PHP_EOL
    );
    exit(0);
}

if (unlink($config['router']['fastroute'][FastRouteRouter::CONFIG_CACHE_FILE]) === false) {
    printf(
        "Error removing route cache file '%s'%s",
        $config['router']['fastroute'][FastRouteRouter::CONFIG_CACHE_FILE],
        PHP_EOL
    );
    exit(1);
}

printf(
    "Removed configured route cache file '%s'%s",
    $config['router']['fastroute'][FastRouteRouter::CONFIG_CACHE_FILE],
    PHP_EOL
);

exit(0);
