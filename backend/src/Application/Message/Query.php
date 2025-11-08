<?php

declare(strict_types=1);

namespace App\Application\Message;

use Override;

/**
 * Abstract base class for queries in the CQRS pattern.
 *
 * This class provides a common structure for all query classes, including a
 * timestamp indicating when the query was created.
 *
 * @SuppressWarnings("PHPMD.ShortVariable")
 */
abstract readonly class Query extends Message implements QueryInterface
{
    /**
     * Constructor for the Query class.
     *
     * @param string|int|null $id The ID of the resource to be read.
     */
    protected function __construct(public string | int | null $id)
    {
        parent::__construct();
    }

    /**
     * Create an instance of the specific Query subclass from an associative array.
     *
     * @param array<string, mixed> $data The input data.
     * @return static An instance of the specific Query subclass.
     */
    #[Override]
    abstract public static function fromArray(array $data): self;

    /**
     * Convert the query to an associative array.
     *
     * @return array<string, mixed> The query data as an associative array.
     */
    #[Override]
    public function toArray(): array
    {
        return array_merge($this->baseToArray(), ['id' => $this->id]);
    }
}
