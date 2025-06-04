<?php

namespace App\Repository\Interface;

use App\Entity\Interface\EntityInterface;
use Doctrine\Persistence\ObjectRepository;
/**
 * @template T of EntityInterface
 * @extends ObjectRepository<T>
 */
interface RepositoryInterface extends ObjectRepository
{
    /**
     * @param T $entity
     * @return T
     */
    public function save(EntityInterface $entity, bool $flush = false): EntityInterface;

    /**
     * @param T $entity
     */
    public function remove(EntityInterface $entity, bool $flush = false): void;
}
