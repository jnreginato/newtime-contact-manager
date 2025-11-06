<?php

declare(strict_types=1);

namespace App;

use Dotenv\Dotenv;
use Override;
use PHPUnit\Framework\TestCase;

use function chdir;
use function copy;
use function define;
use function defined;
use function dirname;
use function getrusage;
use function microtime;
use function realpath;

/**
 * Base class for integration tests.
 *
 * This class sets up the test environment and provides common functionality
 * for integration tests.
 */
abstract class IntegrationTestCase extends TestCase
{
    /**
     * Set up the test environment.
     *
     * This method is called before each test method is executed.
     * It sets up the execution time and resource usage constants
     * and changes the current working directory to the application root.
     */
    #[Override]
    protected function setUp(): void
    {
        if (!defined('START_EXECUTION_TIME')) {
            define('START_EXECUTION_TIME', microtime(true));
        }

        if (!defined('RESOURCE_USAGE')) {
            define('RESOURCE_USAGE', getrusage());
        }

        if (!defined('APP_ROOT')) {
            define('APP_ROOT', dirname((string) realpath(__DIR__), 2));
        }

        /**
         * Change the current working directory to the application root.
         * This makes our life easier when dealing with paths.
         * Everything is relative to the application root now.
         */
        chdir(dirname((string) realpath(__DIR__), 2));


        $envFile = APP_ROOT . '/.env';
        $envExampleFile = APP_ROOT . '/.env.example';

        if (!file_exists($envFile)) {
            copy($envExampleFile, $envFile);
        }

        $dotenv = Dotenv::createUnsafeImmutable(APP_ROOT);
        $dotenv->load();

        parent::setUp();
    }
}
