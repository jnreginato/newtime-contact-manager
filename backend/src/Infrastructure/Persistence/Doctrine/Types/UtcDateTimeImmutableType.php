<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Types;

use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Exception;
use Override;

/**
 * UtcDateTimeImmutableType class.
 *
 * This class extends the DateTimeImmutableType to handle DateTimeImmutable objects
 * specifically in UTC timezone. It overrides methods to ensure that the DateTimeImmutable
 * values are always stored and retrieved in UTC format.
 *
 * @psalm-suppress MissingConstructor
 */
final class UtcDateTimeImmutableType extends DateTimeImmutableType
{
    /**
     * Converts the database value to a PHP DateTimeImmutable object.
     *
     * This method overrides the parent method to ensure that the DateTimeImmutable
     * object is always in UTC timezone when retrieved from the database.
     *
     * @param mixed $value The value from the database.
     * @param AbstractPlatform $platform The platform being used.
     * @return DateTimeImmutable|null The converted DateTimeImmutable object in UTC timezone, or null.
     * @throws InvalidFormat If the value cannot be converted to a DateTimeImmutable object.
     */
    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?DateTimeImmutable
    {
        return parent::convertToPHPValue($value, $platform)?->setTimezone(new DateTimeZone('UTC'));
    }

    /**
     * Converts a PHP DateTimeImmutable object to a database value.
     *
     * This method overrides the parent method to ensure that the DateTimeImmutable
     * object is converted to UTC format before being stored in the database.
     *
     * @param mixed $value The DateTimeImmutable object to convert.
     * @param AbstractPlatform $platform The platform being used.
     * @return string|null The formatted date string in UTC, or null if the value is null.
     * @throws DateMalformedStringException If the date string is malformed.
     * @throws Exception If an error occurs during the conversion.
     */
    #[Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof DateTimeInterface) {
            $utc = (new DateTimeImmutable($value->format('Y-m-d H:i:s.u'), $value->getTimezone()))
                ->setTimezone(new DateTimeZone('UTC'));

            return $utc->format($platform->getDateTimeFormatString());
        }

        return parent::convertToDatabaseValue($value, $platform);
    }
}
