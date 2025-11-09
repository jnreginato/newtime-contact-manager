<?php

declare(strict_types=1);

namespace App\Adapter\Api\Middleware;

/**
 * Lightweight test DTO used to verify the middleware instantiates the provided
 * DTO class with parser data.
 */
final class TestInput
{
    public array $data;

    /**
     * Constructor.
     *
     * @param array $data The data to be passed to the DTO.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}
