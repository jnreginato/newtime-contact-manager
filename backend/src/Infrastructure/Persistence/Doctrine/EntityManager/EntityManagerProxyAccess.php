<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\EntityManager;

use Doctrine\ORM\Proxy\ProxyFactory;
use Override;

/**
 * EntityManagerProxyAccess trait.
 *
 * This trait provides methods for managing the proxy factory in the Doctrine
 * EntityManager.
 */
trait EntityManagerProxyAccess
{
    /**
     * Gets the ProxyFactory used by the EntityManager.
     *
     * @return ProxyFactory The ProxyFactory instance
     */
    #[Override]
    public function getProxyFactory(): ProxyFactory
    {
        return $this->entityManager->getProxyFactory();
    }

    /**
     * Helper method to initialize a lazy loading proxy or persistent
     * collection.
     *
     * This method is a no-op for other objects.
     *
     * @param object $obj The object to initialize
     */
    #[Override]
    public function initializeObject(object $obj): void
    {
        $this->entityManager->initializeObject($obj);
    }

    /**
     * Helper method to check whether a lazy loading proxy or persistent
     * collection has been initialized.
     *
     * @param mixed $value The value to check
     * @return bool True if the object is uninitialized, false otherwise
     */
    #[Override]
    public function isUninitializedObject(mixed $value): bool
    {
        return $this->entityManager->isUninitializedObject($value);
    }
}
