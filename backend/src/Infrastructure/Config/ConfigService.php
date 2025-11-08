<?php

declare(strict_types=1);

namespace App\Infrastructure\Config;

use App\Infrastructure\Config\Exception\EmptyConfigException;
use App\Infrastructure\Config\Exception\InvalidTypeConfigException;
use App\Infrastructure\Config\Exception\MissingConfigException;
use Override;

use function array_key_exists;
use function explode;
use function gettype;
use function sprintf;

/**
 * ConfigService class.
 *
 * This class is responsible for managing configuration values.
 * It allows retrieving configuration values by their path, validating them
 * based on specified options, and handling missing or invalid configurations.
 */
final readonly class ConfigService implements ConfigServiceInterface
{
    /**
     * ConfigService constructor.
     *
     * @param array<array-key, mixed> $config Configuration array
     */
    public function __construct(private array $config)
    {
    }

    /**
     * Retrieves a configuration value by its path.
     *
     * @param string $path The path to the configuration value (e.g., 'db.host')
     * @param mixed $default The default value to return if the configuration key does not exist
     * @param ValidatorOptions|null $validators An optional object containing validation options
     * @return mixed The configuration value or the default value
     */
    #[Override]
    public function get(string $path, mixed $default = null, ?ValidatorOptions $validators = null): mixed
    {
        $value = $this->findValueByPath($path, $default, $validators);

        $this->validateValue($path, $value, $validators);

        return $value;
    }

    /**
     * Find a configuration value by its path.
     *
     * @param string $path The path to the configuration value (e.g., 'db.host')
     * @param mixed $default The default value to return if the configuration key does not exist
     * @param ValidatorOptions|null $validators An optional object containing validation options
     * @return mixed The configuration value or the default value
     */
    private function findValueByPath(string $path, mixed $default, ?ValidatorOptions $validators = null): mixed
    {
        $keys = explode('.', $path);
        $value = $this->config;

        foreach ($keys as $key) {
            /** @var array<string, mixed> $value */
            if (!array_key_exists($key, $value)) {
                return $this->handleMissingKey($path, $default, $validators);
            }

            $value = $value[$key];
        }

        return $value;
    }

    /**
     * Handle a missing configuration key.
     *
     * @param string $path The path to the configuration value (e.g., 'db.host')
     * @param mixed $default The default value to return if the configuration key does not exist
     * @param ValidatorOptions|null $validators An optional object containing validation options
     * @return mixed The default value or throws an exception if the key is required
     */
    private function handleMissingKey(string $path, mixed $default, ?ValidatorOptions $validators = null): mixed
    {
        if ($validators?->required) {
            throw new MissingConfigException(sprintf('Missing required configuration: "%s"', $path));
        }

        return $default;
    }

    /**
     * Validates the configuration value based on the provided options.
     *
     * @param string $path The path to the configuration value (e.g., 'db.host')
     * @param mixed $value The configuration value to validate
     * @param ValidatorOptions|null $validators An optional object containing validation options
     * @throws EmptyConfigException If the value is empty and not allowed
     * @throws InvalidTypeConfigException If the value is of an invalid type
     */
    private function validateValue(string $path, mixed $value, ?ValidatorOptions $validators = null): void
    {
        if ($validators?->notEmpty && ($value === '' || $value === null || $value === [])) {
            throw new EmptyConfigException(sprintf('Configuration "%s" cannot be empty.', $path));
        }

        if ($validators?->type !== null && gettype($value) !== $validators?->type->value) {
            throw new InvalidTypeConfigException(
                sprintf(
                    'Configuration "%s" must be of the type "%s", "%s" given.',
                    $path,
                    $validators?->type->value,
                    gettype($value),
                ),
            );
        }
    }
}
