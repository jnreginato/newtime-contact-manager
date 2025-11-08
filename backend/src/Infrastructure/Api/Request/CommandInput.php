<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use function array_key_exists;
use function array_keys;
use function in_array;
use function is_string;
use function trim;

/**
 * Abstract base class for validating command input data.
 */
#[Assert\Callback('validateAllowedBodyFields')]
#[Assert\Callback('validateRequiredBodyFields')]
abstract class CommandInput extends Input
{
    /**
     * Keys that are not considered part of the body fields.
     */
    private const array NON_BODY_KEYS = ['resourceId', 'pageSize', 'pageNumber'];

    /**
     * Converts the validated input to a command object.
     *
     * This method should be implemented by subclasses to convert the validated
     * input data into a command object that can be processed by the application.
     *
     * @return object The command object representing the validated input.
     */
    abstract public function toCommand(): object;

    /**
     * Returns the allowed fields by API operation.
     *
     * This method should be implemented by subclasses to return an associative
     * array where keys are API operation names and values are arrays of field
     * names that are allowed for that operation.
     *
     * @return list<string> The allowed fields by validation group.
     */
    abstract protected function allowedBodyFields(): array;

    /**
     * Returns the required fields by API operation.
     *
     * This method should be implemented by subclasses to return an associative
     * array where keys are API operation names and values are arrays of field
     * names that are required for that operation.
     *
     * @return list<string> The required fields by validation group.
     */
    abstract protected function requiredBodyFields(): array;

    /**
     * Validates allowed body fields based on the validation group.
     *
     * This method checks if the fields present in the input data are allowed
     * based on the current validation group.
     *
     * @param ExecutionContextInterface $context The validation context.
     */
    public function validateAllowedBodyFields(ExecutionContextInterface $context): void
    {
        $allowed = $this->allowedBodyFields();

        foreach (array_keys($this->data) as $field) {
            if (in_array($field, self::NON_BODY_KEYS, true)) {
                continue;
            }

            if (in_array($field, $allowed, true)) {
                continue;
            }

            $context->buildViolation("Field '$field' is not allowed.")
                ->atPath('{' . $field . '}')
                ->addViolation();
        }
    }

    /**
     * Validates required body fields based on the validation group.
     *
     * This method checks if the required fields for the current validation
     * group are present and non-empty in the input data.
     *
     * @param ExecutionContextInterface $context The validation context.
     */
    public function validateRequiredBodyFields(ExecutionContextInterface $context): void
    {
        $required = $this->requiredBodyFields();

        foreach ($required as $field) {
            if ($this->exists($field) && $this->isValid($field)) {
                continue;
            }

            $context->buildViolation("Field '$field' is required.")
                ->atPath('{' . $field . '}')
                ->addViolation();
        }
    }

    /**
     * Checks if a key exists in the input data.
     *
     * @param string $key The key to check for existence.
     * @return bool True if the key exists, false otherwise.
     */
    private function exists(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Checks if a key in the input data is valid (non-null and non-empty).
     *
     * @param string $key The key to check for validity.
     * @return bool True if the key is valid, false otherwise.
     */
    private function isValid(string $key): bool
    {
        $value = $this->data[$key] ?? null;

        return $value !== null && (!is_string($value) || trim($value) !== '');
    }
}
