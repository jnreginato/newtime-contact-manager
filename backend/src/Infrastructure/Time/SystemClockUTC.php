<?php

declare(strict_types=1);

namespace App\Infrastructure\Time;

use DateTimeImmutable;
use DateTimeZone;
use Override;
use Psr\Clock\ClockInterface;

/**
 * Class SystemClockUTC
 *
 * This class implements the Clock interface and provides the current time in UTC.
 * It is used to abstract the time retrieval, allowing for easier testing and mocking.
 */
final class SystemClockUTC implements ClockInterface
{
    /**
     * Returns the current time as a DateTimeImmutable object in UTC.
     *
     * This method provides the current time in UTC, ensuring that the time is immutable
     * and can be used safely across different parts of the application.
     *
     * @return DateTimeImmutable The current time in UTC.
     */
    #[Override]
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }
}
