<?php

declare(strict_types=1);

namespace App\Infrastructure\Log\Processor;

use Monolog\LogRecord;
use Override;

/**
 * An optional interface to allow labelling Monolog processors.
 */
interface ProcessorInterface extends \Monolog\Processor\ProcessorInterface
{
    /**
     * Invokes a Processor.
     *
     * @return LogRecord The processed record.
     */
    #[Override]
    public function __invoke(LogRecord $record): LogRecord;
}
