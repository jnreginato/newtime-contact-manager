<?php

declare(strict_types=1);

namespace App\Infrastructure\Log\Processor;

use Monolog\LogRecord;
use Override;

use function count;
use function file_get_contents;
use function function_exists;
use function getrusage;
use function is_file;
use function microtime;
use function preg_match_all;
use function round;
use function sys_getloadavg;

/**
 * Monolog processor that enriches log-records with runtime performance metrics.
 *
 * Metrics include:
 * - Execution time since application bootstrap
 * - CPU count
 * - 1-minute load average (normalized)
 * - Resource usage time (user/system CPU time)
 *
 * @psalm-suppress RedundantCast
 */
final class MetricProcessor implements ProcessorInterface
{
    private const int LOAD_1_MINUTE = 0;

    /**
     * MetricProcessor constructor.
     *
     * @param float $startExecutionTime Timestamp from application start (in microseconds)
     * @param array<string, int> $initialResourceUsage Result of getrusage() captured at bootstrap
     */
    public function __construct(
        private readonly float $startExecutionTime,
        private readonly array $initialResourceUsage,
    ) {
    }

    /**
     * Enriches the log record with execution metrics under the 'extra' key.
     *
     * @param LogRecord $record The log record to enrich
     * @return LogRecord Modified log record
     */
    #[Override]
    public function __invoke(LogRecord $record): LogRecord
    {
        $elapsedTime = microtime(true) - $this->startExecutionTime;

        $record->extra['execution_time'] = (int) round($elapsedTime * 1000.0);
        $record->extra['cpu_count'] = $this->getCpuCount();
        $record->extra['load_average'] = $this->getLoadAverage();
        $record->extra['ru_utime'] = $this->resourceUsageTime('utime');
        $record->extra['ru_stime'] = $this->resourceUsageTime('stime');

        return $record;
    }

    /**
     * Returns the number of logical processors on the system.
     *
     * @return int CPU core count
     */
    private function getCpuCount(): int
    {
        $cpuCount = 1;
        $cpuInfo = false;

        if (is_file('/proc/cpuinfo')) {
            $cpuInfo = file_get_contents('/proc/cpuinfo');
        }

        if ($cpuInfo !== false) {
            preg_match_all('/^processor/m', $cpuInfo, $matches);
            $cpuCount = count($matches[0]);
        }

        return $cpuCount;
    }

    /**
     * Returns the normalized 1-minute load average or null if unavailable.
     *
     * @return float|null Load average (per CPU) or null if unsupported
     */
    private function getLoadAverage(): ?float
    {
        if (!function_exists('sys_getloadavg')) {
            return null;
        }

        $usage = sys_getloadavg();

        return $usage === false
            ? null
            : $usage[self::LOAD_1_MINUTE] / $this->getCpuCount();
    }

    /**
     * Calculates the difference in CPU time since the initial resource usage.
     *
     * @param string $index 'utime' (user) or 'stime' (system)
     * @return float Time delta in milliseconds
     */
    private function resourceUsageTime(string $index): float
    {
        $initialSec = $this->initialResourceUsage["ru_$index.tv_sec"] ?? 0;
        $initialUsec = $this->initialResourceUsage["ru_$index.tv_usec"] ?? 0;
        $initialMs = (float) (($initialSec * 1000) + (int) ($initialUsec / 1000));

        $resourceUsage = (array) (getrusage() ?: []);
        $currentSec = $resourceUsage["ru_$index.tv_sec"] ?? 0;
        $currentUsec = $resourceUsage["ru_$index.tv_usec"] ?? 0;
        $currentMs = (float) (($currentSec * 1000) + (int) ($currentUsec / 1000));

        return $currentMs - $initialMs;
    }
}
