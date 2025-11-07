<?php

declare(strict_types=1);

namespace App\Infrastructure\ErrorHandler\Listener;

use Psr\Log\LoggerInterface;

/**
 * FileLogErrorListener is an error listener that logs errors to a file.
 *
 * This class implements the ErrorListenerInterface and uses a PSR-3 logger to
 * log errors.
 * It is typically used in a production environment to log errors for later
 * analysis.
 */
final class FileLogErrorListener implements ErrorListenerInterface
{
    use LogsErrorToPsrLogger;

    /**
     * Injects a PSR-3 logger used to log errors.
     *
     * @param LoggerInterface $logger The logger to use.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->setLogger($logger);
    }
}
