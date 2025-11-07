<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Types;

/**
 * CustomTypes class.
 *
 * This class defines custom types used in the Doctrine ORM.
 */
final class CustomTypes
{
    /**
     * The name of the custom type for DateTimeImmutable in UTC.
     *
     * This type is used to ensure that DateTimeImmutable objects are always stored
     * and retrieved in the UTC timezone.
     */
    public const string DATETIME_IMMUTABLE_UTC = 'datetime_immutable_utc';
}
