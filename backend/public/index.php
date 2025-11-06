<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Mezzio\Application;

// This makes our life easier when dealing with paths.
// Everything is relative to the application root now.
chdir(dirname((string) realpath(__DIR__)));

// Load the global constants
require_once 'public/constants.php'; // NOSONAR

// Setup composer autoload
require_once 'vendor/autoload.php'; // NOSONAR

// Loads environment variables from .env to getenv(), $_ENV and $_SERVER automagically.
// @phpstan-ignore-next-line
$dotenv = Dotenv::createUnsafeImmutable(APP_ROOT);
$dotenv->load();

// Self-called anonymous function that creates its own scope and keep the global namespace clean.
(static function (): void {
    $container = require 'config/container.php'; // NOSONAR
    assert($container instanceof Psr\Container\ContainerInterface);
    $application = $container->get(Application::class);
    assert($application instanceof Application);
    (require 'config/pipeline.php')($application); // NOSONAR
    (require 'config/routes.php')($application); // NOSONAR

    $application->run();
})();
