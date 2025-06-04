<?php

namespace App\Service\Entity;

use App\Entity\Interface\EntityInterface;
use Doctrine\Persistence\ObjectRepository;
use App\Exception\ResourceNotFoundException;

/**
 * @template T of EntityInterface
 */
abstract class AbstractEntityService
{

    /**
     * @param ObjectRepository<T> $repository
     */
    public function __construct(
        private readonly ObjectRepository $repository
    ) {
    }

    /**
     * @return T
     */
    public function findOrFail(int $id): EntityInterface
    {
        $entity = $this->repository->find($id);

        if (!$entity) {
            throw new ResourceNotFoundException(sprintf(
                '%s with ID %d not found.',
                $this->repository->getClassName(),
                $id
            ));
        }

        return $entity;
    }
    /**
     * @return T
     */
    public function findOrFailBy(array $criteria): EntityInterface
    {
        $entity = $this->repository->findOneBy($criteria);

        if (!$entity) {
            throw new ResourceNotFoundException(sprintf(
                '%s with criteria %s not found.',
                $this->repository->getClassName(),
                json_encode($criteria)
            ));
        }

        return $entity;
    }
}
