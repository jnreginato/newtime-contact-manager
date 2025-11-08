<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Exception;

use InvalidArgumentException;
use Stringable;

use function is_scalar;

/**
 * Class SimpleError
 *
 * Represents a simple error with a parameter name, value, error code, message
 * template, and parameters.
 */
final readonly class SimpleError
{
    /**
     * SimpleError constructor.
     *
     * @param string|null $parameterName The name of the parameter that caused the error, or null if not applicable.
     * @param mixed $parameterValue The value of the parameter that caused the error.
     * @param string $internalErrorCode The internal error code associated with this error.
     * @param string $messageTemplate A template for the error message.
     * @param array<array-key, mixed> $messageParameters Parameters to be used in the message template.
     */
    public function __construct(
        public ?string $parameterName,
        public mixed $parameterValue,
        public string $internalErrorCode,
        public string $messageTemplate,
        public array $messageParameters,
    ) {
        if ($this->checkEachValueConvertibleToString($messageParameters) === false) {
            throw new InvalidArgumentException('The value must be a scalar or Stringable type.');
        }
    }

    /**
     * Checks if each value in the message parameters is convertible to a string.
     *
     * @param array<array-key, mixed> $messageParameters The message parameters to check.
     * @return bool True if all values are convertible to string, false otherwise.
     */
    private function checkEachValueConvertibleToString(iterable $messageParameters): bool
    {
        foreach ($messageParameters as $value) {
            if (!is_scalar($value) && $value !== null && !$value instanceof Stringable) {
                return false;
            }
        }

        return true;
    }
}
