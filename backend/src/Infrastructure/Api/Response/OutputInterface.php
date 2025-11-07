<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

use App\Application\Result\Result;

/**
 * Interface OutputInterface.
 *
 * This interface defines the contract for output DTOs in the application.
 * It includes methods for retrieving the type, ID, fields, and relationships of
 * the output.
 */
interface OutputInterface
{
    /**
     * Create an instance of the output from a Result object.
     *
     * This static method is responsible for creating an instance of the output DTO
     * from a given Result object. It ensures that the output is correctly initialized
     * with the data contained in the Result.
     *
     * @param Result $result The result containing the entity and other relevant data.
     * @return self An instance of the output DTO.
     */
    public static function fromResult(Result $result): self;

    /**
     * Returns the fields of the output.
     *
     * This method retrieves the attributes defined in the output DTO,
     * which are essential for representing the data in a structured format.
     *
     * @return array<string, mixed> The fields of the output.
     */
    public function getFields(): array;
}
