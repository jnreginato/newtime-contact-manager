<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

use App\Infrastructure\Api\Request\Mapper\Filter\FilterJoinType;
use App\UnitTestCase;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

use function assert;

/**
 * Feature: ValidatedInput
 *
 * This test suite validates the functionality of the ValidatedInput class,
 * ensuring that it correctly handles input validation for filters, sorts,
 * includes, and fields according to the defined rules.
 */
final class ValidatedInputTest extends UnitTestCase
{
    /**
     * Scenario: ValidatedInput constructor defaults
     *
     * Given a new instance of ValidatedInput
     * When it is constructed without any data,
     * Then it should have default values for resourceId, pageSize, pageNumber, filter, and sort
     */
    public function testConstructorDefaults(): void
    {
        // @phpstan-ignore-next-line
        $input = new FakeConcreteInput([]);

        self::assertNull($input->getResourceId());
        self::assertSame(10, $input->getPageSize());
        self::assertSame(1, $input->getPageNumber());
        self::assertSame([], $input->getFilter());
        self::assertSame([], $input->getSort());
    }

    /**
     * Scenario: ValidatedInput constructor with data
     *
     * Given a new instance of ValidatedInput with specific data
     * When it is constructed with that data,
     * Then it should correctly set the resourceId, pageSize, pageNumber, filter, and sort
     */
    public function testConstructorWithData(): void
    {
        // @phpstan-ignore-next-line
        $input = new FakeConcreteInput([
            'resourceId' => '<script>1</script>',
            'pageSize' => 5,
            'pageNumber' => 2,
            'filterJoinType' => FilterJoinType::And->value,
            'filter' => ['firstName' => ['eq' => ['Jonatan']]],
            'sort' => ['firstName' => true],
            'include' => ['role'],
            'fields' => ['users' => ['firstName', 'lastName']],
        ]);

        self::assertSame('<script>1</script>', $input->getResourceId());
        self::assertSame(5, $input->getPageSize());
        self::assertSame(2, $input->getPageNumber());
    }

    /**
     * Scenario: ValidatedInput constructor with invalid data
     *
     * Given a new instance of ValidatedInput with invalid data
     * When it is constructed with that data,
     * Then it should throw an exception for invalid page size and number
     *
     * @throws Exception If the test fails due to an unexpected exception.
     */
    public function testValidateFiltersValid(): void
    {
        // @phpstan-ignore-next-line
        $input = new FakeConcreteInput(['filter' => ['firstName' => ['eq' => ['Jonatan']]]]);

        $context = $this->createMock(ExecutionContextInterface::class);
        assert($context instanceof ExecutionContextInterface);
        $context->expects($this->never())->method('buildViolation');
        $context->method('getObject')->willReturn($input);

        FakeConcreteInput::validateFilter($input->getFilter(), $context);
    }

    /**
     * Scenario: ValidatedInput constructor with invalid data
     *
     * Given a new instance of ValidatedInput with invalid data
     * When it is constructed with that data,
     * Then it should throw an exception for invalid page size and number
     *
     * @throws Exception If the test fails due to an unexpected exception.
     */
    public function testValidateFiltersInvalid(): void
    {
        // @phpstan-ignore-next-line
        $input = new FakeConcreteInput(['filter' => ['unknown' => ['eq' => ['value']]]]);

        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $builder->expects($this->once())->method('addViolation');

        $context = $this->createMock(ExecutionContextInterface::class);
        assert($context instanceof ExecutionContextInterface);
        $context->method('getObject')->willReturn($input);
        $context->expects($this->once())->method('buildViolation')->with("Invalid filter field: 'unknown'")
            ->willReturn($builder);

        FakeConcreteInput::validateFilter($input->getFilter(), $context);
    }

    /**
     * Scenario: ValidatedInput constructor with valid sort
     *
     * Given a new instance of ValidatedInput with valid sort data
     * When it is constructed with that data,
     * Then it should not throw any exceptions
     *
     * @throws Exception If the test fails due to an unexpected exception.
     */
    public function testValidateSortsValid(): void
    {
        // @phpstan-ignore-next-line
        $input = new FakeConcreteInput(['sort' => ['firstName' => true]]);

        $context = $this->createMock(ExecutionContextInterface::class);
        assert($context instanceof ExecutionContextInterface);
        $context->expects($this->never())->method('buildViolation');
        $context->method('getObject')->willReturn($input);

        FakeConcreteInput::validateSort($input->getSort(), $context);
    }

    /**
     * Scenario: ValidatedInput constructor with invalid sort
     *
     * Given a new instance of ValidatedInput with invalid sort data
     * When it is constructed with that data,
     * Then it should throw an exception for invalid sort field
     *
     * @throws Exception If the test fails due to an unexpected exception.
     */
    public function testValidateSortsInvalid(): void
    {
        // @phpstan-ignore-next-line
        $input = new FakeConcreteInput(['sort' => ['invalid' => true]]);

        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $builder->expects($this->once())->method('addViolation');

        $context = $this->createMock(ExecutionContextInterface::class);
        assert($context instanceof ExecutionContextInterface);
        $context->method('getObject')->willReturn($input);
        $context->expects($this->once())->method('buildViolation')->with("Sort field 'invalid' is not allowed.")
            ->willReturn($builder);

        FakeConcreteInput::validateSort($input->getSort(), $context);
    }

    /**
     * Scenario: ValidatedInput constructor with valid includes
     *
     * Given a new instance of ValidatedInput with valid include data
     * When it is constructed with that data,
     * Then it should not throw any exceptions
     *
     * @throws Exception If the test fails due to an unexpected exception.
     */
    public function testValidateIncludesValid(): void
    {
        // @phpstan-ignore-next-line
        $input = new FakeConcreteInput(['include' => ['role' => ['role']]]);

        $context = $this->createMock(ExecutionContextInterface::class);
        assert($context instanceof ExecutionContextInterface);
        $context->expects($this->never())->method('buildViolation');
        $context->method('getObject')->willReturn($input);

        FakeConcreteInput::validateInclude($input->getInclude(), $context);
    }

    /**
     * Scenario: ValidatedInput constructor with invalid includes
     *
     * Given a new instance of ValidatedInput with invalid include data
     * When it is constructed with that data,
     * Then it should throw an exception for invalid include relationship
     *
     * @throws Exception If the test fails due to an unexpected exception.
     */
    public function testValidateIncludesInvalid(): void
    {
        // @phpstan-ignore-next-line
        $input = new FakeConcreteInput(['include' => ['invalid' => ['x']]]);

        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $builder->expects($this->once())->method('addViolation');

        $context = $this->createMock(ExecutionContextInterface::class);
        assert($context instanceof ExecutionContextInterface);
        $context->method('getObject')->willReturn($input);
        $context->expects($this->once())->method('buildViolation')->with("Include 'invalid' is not allowed.")
            ->willReturn($builder);

        FakeConcreteInput::validateInclude($input->getInclude(), $context);
    }

    /**
     * Scenario: ValidatedInput constructor with valid fields
     *
     * Given a new instance of ValidatedInput with valid fields data
     * When it is constructed with that data,
     * Then it should not throw any exceptions
     *
     * @throws Exception If the test fails due to an unexpected exception.
     */
    public function testValidateFieldsValid(): void
    {
        // @phpstan-ignore-next-line
        $input = new FakeConcreteInput([
            'fields' => [
                'roles' => ['id', 'description'],
                'users' => ['firstName'],
            ],
        ]);

        $context = $this->createMock(ExecutionContextInterface::class);
        assert($context instanceof ExecutionContextInterface);
        $context->expects($this->never())->method('buildViolation');
        $context->method('getObject')->willReturn($input);

        FakeConcreteInput::validateFields($input->getFields(), $context);
    }

    /**
     * Scenario: ValidatedInput constructor with invalid fields
     *
     * Given a new instance of ValidatedInput with invalid fields data
     * When it is constructed with that data,
     * Then it should throw an exception for invalid resource type and field
     *
     * @throws Exception If the test fails due to an unexpected exception.
     */
    public function testValidateFieldsInvalid(): void
    {
        // @phpstan-ignore-next-line
        $input = new FakeConcreteInput([
            'fields' => [
                'unknownResource' => ['foo'],
                'users' => ['invalidField'],
            ],
        ]);

        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $builder->expects($this->exactly(2))->method('addViolation');

        $context = $this->createMock(ExecutionContextInterface::class);
        assert($context instanceof ExecutionContextInterface);
        $context->method('getObject')->willReturn($input);

        $context->expects($this->exactly(2))->method('buildViolation')->willReturnOnConsecutiveCalls(
            ["Invalid resource type 'unknownResource'."],
            ["Field 'invalidField' is not allowed in 'users'."],
        )->willReturn($builder);

        FakeConcreteInput::validateFields($input->getFields(), $context);
    }
}
