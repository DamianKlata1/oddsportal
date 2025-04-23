<?php

namespace App\Repository\Trait;

trait TransactionManagement
{
    public function startTransaction(): void
    {
        $this->getEntityManager()->beginTransaction();
    }

    public function commitTransaction(): void
    {
        $this->getEntityManager()->commit();
    }

    public function addToCommit(object $entity): void
    {
        $this->getEntityManager()->flush();
    }

    public function rollbackTransaction(): void
    {
        $this->getEntityManager()->rollback();
    }

}
