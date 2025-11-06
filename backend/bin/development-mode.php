#!/usr/bin/env php
<?php

declare(strict_types=1);

chdir(dirname((string) realpath(__DIR__)));

require_once 'vendor/autoload.php';

const AUTOLOAD_CONFIG_FOLDER = 'config/autoload';
const DEVEL_CONFIG_DIST = AUTOLOAD_CONFIG_FOLDER . '/development.config.php.dist';
const DEVEL_CONFIG = AUTOLOAD_CONFIG_FOLDER . '/development.config.php';
const LOCAL_CONFIG_DIST = AUTOLOAD_CONFIG_FOLDER . '/local.php.dist';
const LOCAL_CONFIG = AUTOLOAD_CONFIG_FOLDER . '/local.php';
const COMPOSER_DEV_MODE = 'COMPOSER_DEV_MODE';

/**
 * Handle the CLI arguments.
 *
 * @param string[] $arguments
 *
 * @return int
 */
function command(array $arguments): int
{
    // Called without arguments
    if (empty($arguments)) {
        fwrite(STDERR, 'No arguments provided.' . PHP_EOL . PHP_EOL);
        help();
        return 1;
    }

    $argument = array_shift($arguments);

    $callback = match ($argument) {
        '-h', '--help' => static function () {
            help();
            return 0;
        },
        'status' => static function () {
            return status();
        },
        'enable' => static function () {
            return enable();
        },
        'disable' => static function () {
            return disable();
        },
        'auto-composer' => static function () {
            return auto();
        },
        default => static function () {
            fwrite(STDERR, 'Unrecognized argument.' . PHP_EOL . PHP_EOL);
            help();
            return 1;
        }
    };

    return $callback();
}

/**
 * Emit the help message.
 */
function help(): void
{
    $message = <<<EOH
Enable/Disable development mode.

Usage:

development-mode [-h|--help] disable|enable|status

--help|-h                    Print this usage message.
disable                      Disable development mode.
enable                       Enable development mode
                             (do not use in production).
status                       Determine if development mode is currently
                             enabled.
auto-composer                Enable or disable development mode based on
                             the environment variable COMPOSER_DEV_MODE.
                             If the variable is not found, the mode is
                             untouched. If set to something other than "0",
                             it's enabled.

To enable development mode, the following file MUST exist:

- config/autoload/development.config.php.dist; this file will be copied to
  config/autoload/development.config.php

And:

- config/autoload/*.local.php.dist; this files will be copied to
  config/autoload/*.local.php

When disabling development mode:

- config/autoload/development.config.php will be removed if it exists
- config/autoload/*.local.php will be removed if it exists

Additionally, both when disabling and enabling development mode, the
script will remove the file cache/config-cache.php.

EOH;

    fwrite(STDOUT, $message);
}

/**
 * Indicate whether, or not development mode is enabled.
 *
 * @return int
 */
function status(): int
{
    if (file_exists(DEVEL_CONFIG)) {
        // nothing to do
        echo 'Development mode is ENABLED', PHP_EOL;
        return 0;
    }

    echo 'Development mode is DISABLED', PHP_EOL;
    return 0;
}

/**
 * Enable development mode.
 *
 * @return int
 */
function enable(): int // NOSONAR
{
    if (file_exists(DEVEL_CONFIG)) {
        // nothing to do
        echo 'Already in development mode!', PHP_EOL;
        return 0;
    }

    if (!file_exists(DEVEL_CONFIG_DIST)) {
        fwrite(
            STDERR,
            'MISSING "config/autoload/development.config.php.dist". Could not switch to development mode!' . PHP_EOL
        );
        return 1;
    }

    try {
        removeConfigCacheFile();
        removeRouteCacheFile();
    } catch (RuntimeException $error) {
        fwrite(STDERR, $error->getMessage());
        return 1;
    }

    copyOrLink(DEVEL_CONFIG_DIST, DEVEL_CONFIG);

    if (file_exists(LOCAL_CONFIG_DIST)) {
        copyOrLink(LOCAL_CONFIG_DIST, LOCAL_CONFIG);
        echo 'Created: ' . LOCAL_CONFIG . "\n";
    }

    $files = scandir(AUTOLOAD_CONFIG_FOLDER);
    if ($files === false) {
        fwrite(STDERR, 'Failed to scan directory: ' . AUTOLOAD_CONFIG_FOLDER . PHP_EOL);
        return 1;
    }

    foreach ($files as $file) {
        if (preg_match('/\.local\.php\.dist$/', $file)) {
            $source = AUTOLOAD_CONFIG_FOLDER . DIRECTORY_SEPARATOR . $file;
            $destination = AUTOLOAD_CONFIG_FOLDER . DIRECTORY_SEPARATOR . preg_replace('/\.dist$/', '', $file);

            copyOrLink($source, $destination);
            echo "Created: $destination\n";
        }
    }

    echo 'You are now in development mode.', PHP_EOL;
    return 0;
}

/**
 * Disable development mode.
 *
 * @return int
 */
function disable(): int // NOSONAR
{
    if (!file_exists(DEVEL_CONFIG)) {
        // nothing to do
        echo 'Development mode was already disabled.', PHP_EOL;
        return 0;
    }

    try {
        removeConfigCacheFile();
        removeRouteCacheFile();
    } catch (RuntimeException $error) {
        fwrite(STDERR, $error->getMessage());
        return 1;
    }

    if (file_exists(LOCAL_CONFIG)) {
        unlink(LOCAL_CONFIG);
        echo 'Removed: ' . LOCAL_CONFIG . "\n";
    }

    $files = scandir(AUTOLOAD_CONFIG_FOLDER);
    if ($files === false) {
        fwrite(STDERR, 'Failed to scan directory: ' . AUTOLOAD_CONFIG_FOLDER . PHP_EOL);
        return 1;
    }

    foreach ($files as $file) {
        if (preg_match('/\.local\.php$/', $file)) {
            $path = AUTOLOAD_CONFIG_FOLDER . DIRECTORY_SEPARATOR . $file;
            if (is_file($path)) {
                unlink($path);
                echo "Removed: $path\n";
            }
        }
    }

    unlink(DEVEL_CONFIG);

    echo 'Development mode is now disabled.', PHP_EOL;
    return 0;
}

/**
 * Automatically switch to and from development mode based on type of composer
 * install/update used.
 *
 * If a development install is being performed (`--dev` flag or absence of
 * `--no-dev` flag), then it will enable development mode. Otherwise, it
 * disables it. This is determined by the value of the `COMPOSER_DEV_MODE`
 * environment variable that Composer sets.
 *
 * If the `COMPOSER_DEV_MODE` environment variable is missing, then the command
 * does nothing.
 *
 * @return int
 */
function auto(): int // NOSONAR
{
    $composerDevMode = getenv(COMPOSER_DEV_MODE);
    if ($composerDevMode === '' || $composerDevMode === false) {
        // Not running under composer; do nothing.
        echo 'COMPOSER_DEV_MODE not set. Nothing to do.' . PHP_EOL;
        return 0;
    }

    if ($composerDevMode === '0') {
        return disable();
    }

    if ($composerDevMode === '1') {
        return enable();
    }

    printf(
        'COMPOSER_DEV_MODE set to unexpected value (%s). Nothing to do.%s',
        var_export($composerDevMode, true),
        PHP_EOL
    );
    return 1;
}

/**
 * Removes the application configuration cache file, if present.
 */
function removeConfigCacheFile(): void
{
    $configCacheFile = getConfigCacheFile();

    if ($configCacheFile === false || !file_exists($configCacheFile)) {
        return;
    }

    unlink($configCacheFile);
    echo "Removed: $configCacheFile\n";
}

/**
 * Retrieve the config cache file, if any.
 */
function getConfigCacheFile(): false|string
{
    $config = getApplicationConfig();

    if (!isset($config['config_cache_path']) || !is_string($config['config_cache_path'])) {
        return false;
    }

    return $config['config_cache_path'];
}

/**
 * Return the application configuration.
 *
 * Raises an exception if retrieved configuration is not an array.
 *
 * @return array
 * @throws RuntimeException If config/config.php does not return an array.
 */
function getApplicationConfig(): array
{
    $configFile = 'config/config.php';

    if (!file_exists($configFile)) {
        return [];
    }

    $applicationConfig = include $configFile; // NOSONAR

    if (!is_array($applicationConfig)) {
        throw new RuntimeException( // NOSONAR
            'Invalid configuration returned from config/config.php.' . PHP_EOL
        );
    }

    return $applicationConfig;
}

/**
 * Removes the route cache file, if present.
 */
function removeRouteCacheFile(): void
{
    $routCacheFile = getRouteCacheFile();

    if ($routCacheFile === false || !file_exists($routCacheFile)) {
        return;
    }

    unlink($routCacheFile);
    echo "Removed: $routCacheFile\n";
}

/**
 * Retrieve the route cache file, if any.
 */
function getRouteCacheFile(): false|string
{
    $config = getRouteConfig();

    if (
        !isset($config['router']['fastroute']['cache_file'])
        || !is_string($config['router']['fastroute']['cache_file'])
    ) {
        return false;
    }

    return $config['router']['fastroute']['cache_file'];
}

/**
 * Return the route configuration.
 *
 * Raises an exception if retrieved configuration is not an array.
 *
 * @return array
 * @throws RuntimeException If config/autoload/routes.global.php does not return an array.
 */
function getRouteConfig(): array
{
    $configFile = 'config/autoload/routes.global.php';

    if (!file_exists($configFile)) {
        return [];
    }

    $applicationConfig = include $configFile; // NOSONAR

    if (!is_array($applicationConfig)) {
        throw new RuntimeException( // NOSONAR
            'Invalid configuration returned from config/autoload/routes.global.php.' . PHP_EOL
        );
    }

    return $applicationConfig;
}

/**
 * Copy, or symlink, the source to the destination.
 *
 * @param string $source
 * @param string $destination
 *
 * @return void
 */
function copyOrLink(string $source, string $destination): void
{
    if (supportsSymlinks()) {
        symlink(basename($source), $destination);
        return;
    }

    copy($source, $destination);
}

/**
 * Returns whether the OS support symlinks reliably.
 *
 * This approach uses a pre-configured allowlist of PHP_OS values that
 * typically support symlinks reliably. This may omit some systems that
 * also support symlinks properly; if you find this to be the case, please
 * send a pull request with the PHP_OS value for us to match.
 *
 * This method is marked protected so that we can mock it.
 *
 * @return bool
 */
function supportsSymlinks(): bool
{
    return in_array(PHP_OS, ['Linux', 'Unix', 'Darwin']);
}

exit(command(array_slice($argv, 1)));
