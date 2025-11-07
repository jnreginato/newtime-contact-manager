<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Request;

/**
 * Abstract base class for validated query input data.
 */
abstract class QueryInput extends Input
{
    /**
     * Converts the validated input to a query object.
     *
     * This method should be implemented by subclasses to convert the validated
     * input into a query object that can be used for data retrieval.
     *
     * @return object The query object representing the validated input.
     */
    abstract public function toQuery(): object;
}
