<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Validation;

use App\Infrastructure\Api\Exception\ApiInvalidQueryException;
use App\Infrastructure\Api\Exception\SimpleError;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use App\Infrastructure\Api\Response\HttpStatusCode;
use Override;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

/**
 * Class InputValidator
 *
 * This class implements the InputValidatorInterface and provides a mechanism
 * for validating input values using Symfony's Validator component.
 * It aggregates validation errors and throws an ApiInvalidQueryException if
 * any errors are found.
 */
final readonly class InputValidator implements InputValidatorInterface
{
    /**
     * Constructor for the InputValidator class.
     *
     * @param SymfonyValidatorInterface $validator The Symfony validator to use for validation.
     * @param ErrorAggregatorInterface $errorAggregator The error aggregator to collect validation errors.
     */
    public function __construct(
        private SymfonyValidatorInterface $validator,
        private ErrorAggregatorInterface $errorAggregator,
    ) {
    }

    /**
     * Validates the given value against the defined validation rules.
     *
     * This method uses the Symfony validator to validate the input value and
     * aggregate any validation errors found. If there are validation errors,
     * it throws an ApiInvalidQueryException with the collected errors.
     *
     * @param mixed $value The value to validate.
     * @param array<string> $groups An optional array of validation groups.
     * @throws ApiInvalidQueryException If validation fails, containing the aggregated errors.
     */
    #[Override]
    public function validate(mixed $value, array $groups = []): void
    {
        $violations = $this->validator->validate($value, null, $groups);

        foreach ($violations as $violation) {
            $this->errorAggregator->addBodyApiError(
                new SimpleError(
                    $violation->getPropertyPath(),
                    $violation->getInvalidValue(),
                    $violation->getCode() ?? 'VALIDATION_ERROR',
                    (string) $violation->getMessage(),
                    [],
                ),
                HttpStatusCode::UnprocessableEntity->value,
            );
        }

        $this->checkValidationQueueErrors();
    }

    /**
     * Checks if there are any validation errors in the error aggregator.
     *
     * If there are errors, it throws an ApiInvalidQueryException with the aggregated errors.
     * This method is called after validation to ensure that any errors are handled appropriately.
     *
     * @throws ApiInvalidQueryException If there are validation errors.
     */
    private function checkValidationQueueErrors(): void
    {
        if (!$this->errorAggregator->count()) {
            return;
        }

        $this->throwApiError();
    }

    /**
     * Throws an ApiInvalidQueryException with the collected validation errors.
     *
     * This method is called when validation fails, and there are errors in the
     * error aggregator. It constructs the exception with the error collection
     * and the response status code.
     *
     * @throws ApiInvalidQueryException The exception containing the validation errors.
     */
    private function throwApiError(): void
    {
        throw new ApiInvalidQueryException(
            $this->errorAggregator->getErrorCollection(),
            $this->errorAggregator->getResponseStatusCode(),
        );
    }
}
