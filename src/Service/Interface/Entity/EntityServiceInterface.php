<?php

namespace App\Service\Interface\Entity;

use App\Entity\Interface\EntityInterface;

/**
 * @template T of EntityInterface
 */
interface EntityServiceInterface
{
    /**
     * @return T
     */
    public function findOrFail(int $id): EntityInterface;
    /**
     * @return T
     */
    public function findOrFailBy(array $criteria): EntityInterface;

}
