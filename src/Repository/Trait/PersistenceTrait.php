<?php

namespace App\Repository\Trait;

use App\Entity\Interface\EntityInterface;


/**
 * @template T of EntityInterface
 */
trait PersistenceTrait
{
    /**
     * @param T $entity
     * @return T
     */
    public function save(EntityInterface $entity, bool $flush = false): EntityInterface
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return $entity;
    }

    /**
     * @param T $entity
     */
    public function remove(EntityInterface $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
