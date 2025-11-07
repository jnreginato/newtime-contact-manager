<?php

declare(strict_types=1);

namespace App\Infrastructure\Config;

/**
 * ConfigTypes enum.
 *
 * This enum defines the different types of configuration values that can be
 * used in the application. It includes types such as string, integer, float,
 * boolean, array, object, resource, and null.
 */
enum ConfigTypes: string
{
    case STRING = 'string';
    case INTEGER = 'integer';
    case FLOAT = 'double';
    case BOOLEAN = 'boolean';
    case ARRAY = 'array';
    case OBJECT = 'object';
    case RESOURCE = 'resource';
    case NULL = 'NULL';
}
