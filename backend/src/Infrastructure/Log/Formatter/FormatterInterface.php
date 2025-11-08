<?php

declare(strict_types=1);

namespace App\Infrastructure\Log\Formatter;

use Monolog\LogRecord;
use Override;

/**
 * Interface for formatters.
 *
 * This interface extends Monolog's FormatterInterface and provides methods
 * for formatting log records and batches of log records.
 */
interface FormatterInterface extends \Monolog\Formatter\FormatterInterface
{
    /**
     * Formats a log record.
     *
     * @param LogRecord $record A record to format
     * @return mixed The formatted record
     */
    #[Override]
    public function format(LogRecord $record): mixed;

    /**
     * Formats a set of log records.
     *
     * @param array<LogRecord> $records A set of records to format
     * @return mixed The formatted set of records
     */
    #[Override]
    public function formatBatch(array $records): mixed;
}
