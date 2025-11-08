<?php

declare(strict_types=1);

namespace App\Infrastructure\Config;

/**
 * ConfigServiceInterface interface.
 *
 * This interface defines the contract for a configuration service that allows
 * retrieving configuration values by their path.
 */
interface ConfigServiceInterface
{
    /**
     * Retrieves a configuration value by its path.
     *
     * @param string $path The path to the configuration value (e.g., 'db.host')
     * @param mixed $default The default value to return if the configuration key does not exist
     * @param ValidatorOptions|null $validators An optional object containing validation options
     * @return mixed The configuration value or the default value
     */
    public function get(string $path, mixed $default = null, ?ValidatorOptions $validators = null): mixed;
}
