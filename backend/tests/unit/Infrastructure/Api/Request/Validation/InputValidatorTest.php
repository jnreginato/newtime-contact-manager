<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request\Validation;

use App\Infrastructure\Api\Exception\ApiInvalidQueryException;
use App\Infrastructure\Api\Exception\SimpleError;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorAggregatorInterface;
use App\Infrastructure\Api\Request\ErrorCapture\ErrorCollectionInterface;
use App\UnitTestCase;
use Override;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

use function assert;

/**
 * Feature: InputValidator
 *
 * This class tests the InputValidator functionality, ensuring that it correctly validates input
 * and handles validation errors appropriately.
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class InputValidatorTest extends UnitTestCase
{
    /**
     * The validator mock object.
     */
    private MockObject $validator;

    /**
     * The error aggregator mock object.
     */
    private MockObject $errorAggregator;

    /**
     * The error collection mocks object.
     *
     * This is used to simulate the collection of errors that can be aggregated
     * during validation.
     */
    private MockObject $errorCollection;

    /**
     * Sets up the test environment.
     *
     * This method initializes the mock objects for the validator and error aggregator
     * before each test is run.
     *
     * @throws Exception If there is an error creating the mock objects.
     */
    #[Override]
    protected function setUp(): void
    {
        $this->validator = $this->createMock(SymfonyValidatorInterface::class);
        assert($this->validator instanceof SymfonyValidatorInterface);

        $this->errorAggregator = $this->createMock(ErrorAggregatorInterface::class);
        assert($this->errorAggregator instanceof ErrorAggregatorInterface);

        $this->errorCollection = $this->createMock(ErrorCollectionInterface::class);
        assert($this->errorCollection instanceof ErrorCollectionInterface);

        parent::setUp();
    }

    /**
     * Scenario: Validate with no violations
     *
     * Given a valid input,
     * When the input is validated,
     * Then no exceptions should be thrown.
     */
    public function testValidateWithNoViolationsDoesNotThrow(): void
    {
        $this->validator
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $this->errorAggregator
            ->expects($this->once())
            ->method('count')
            ->willReturn(0);

        assert($this->validator instanceof SymfonyValidatorInterface);
        assert($this->errorAggregator instanceof ErrorAggregatorInterface);

        $validator = new InputValidator($this->validator, $this->errorAggregator);

        $validator->validate(new stdClass());
    }
}
