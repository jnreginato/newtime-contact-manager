<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Console;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use InvalidArgumentException;
use Override;

/**
 * MultiEntityManagerProvider allows managing multiple Doctrine Entity Managers.
 *
 * It provides a way to retrieve the default manager or a specific manager by name.
 */
final readonly class MultiEntityManagerProvider implements EntityManagerProvider
{
    /**
     * Constructor.
     *
     * @param array<string, EntityManagerInterface> $managers An associative array of EntityManager instances.
     * @param string $defaultManagerName The name of the default manager.
     */
    public function __construct(private array $managers, private string $defaultManagerName = 'default')
    {
    }

    /**
     * Returns the default EntityManager.
     *
     * @return EntityManagerInterface The default EntityManager instance.
     */
    #[Override]
    public function getDefaultManager(): EntityManagerInterface
    {
        return $this->managers[$this->defaultManagerName];
    }

    /**
     * Returns the EntityManager by name.
     *
     * @param string|null $name The name of the EntityManager to retrieve.
     * @return EntityManagerInterface The EntityManager instance corresponding to the specified name.
     * @throws InvalidArgumentException If the specified manager does not exist.
     */
    #[Override]
    public function getManager(?string $name = null): EntityManagerInterface
    {
        $name ??= $this->defaultManagerName;

        if (!isset($this->managers[$name])) {
            throw new InvalidArgumentException("EntityManager '$name' not found.");
        }

        return $this->managers[$name];
    }
}
