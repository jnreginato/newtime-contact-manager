<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\EntityManager;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use Override;

/**
 * EntityManagerHydrationAccess trait.
 *
 * This trait provides methods for creating and managing hydrators in the
 * Doctrine EntityManager.
 */
trait EntityManagerHydrationAccess
{
    /**
     * Create a new instance for the given hydration mode.
     *
     * @phpstan-param string|AbstractQuery::HYDRATE_* $hydrationMode
     * @return AbstractHydrator The created hydrator instance
     * @throws ORMException
     */
    #[Override]
    public function newHydrator(int | string $hydrationMode): AbstractHydrator
    {
        return $this->entityManager->newHydrator($hydrationMode);
    }
}
