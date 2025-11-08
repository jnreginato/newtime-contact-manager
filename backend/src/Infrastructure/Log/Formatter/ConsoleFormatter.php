<?php

declare(strict_types=1);

namespace App\Infrastructure\Log\Formatter;

use Monolog\LogRecord;
use Override;

use function sprintf;

/**
 * Formats log records into a simple and readable string suitable for console
 * output.
 */
final class ConsoleFormatter implements FormatterInterface
{
    /**
     * Formats a single log record into a readable string.
     *
     * @param LogRecord $record The log record to format
     * @return string Formatted log message
     */
    #[Override]
    public function format(LogRecord $record): string
    {
        return sprintf("[%s] %s\n", $record->level->getName(), $record->message);
    }

    /**
     * Formats a batch of log records into a single readable string.
     *
     * @param array<LogRecord> $records Array of log records
     * @return string Concatenated formatted log messages
     */
    #[Override]
    public function formatBatch(array $records): string
    {
        $message = '';

        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }
}
