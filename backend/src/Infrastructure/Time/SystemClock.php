<?php

declare(strict_types=1);

namespace App\Infrastructure\Time;

use DateTimeImmutable;
use Override;
use Psr\Clock\ClockInterface;

/**
 * Class SystemClock
 *
 * This class implements the Clock interface and provides the current time.
 * It is used to abstract the time retrieval, allowing for easier testing and mocking.
 */
final class SystemClock implements ClockInterface
{
    /**
     * Returns the current time as a DateTimeImmutable object.
     *
     * This method provides the current time, ensuring that the time is immutable
     * and can be used safely across different parts of the application.
     *
     * @return DateTimeImmutable The current time.
     */
    #[Override]
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now');
    }
}
