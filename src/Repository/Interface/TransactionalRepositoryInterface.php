<?php

namespace App\Repository\Interface;

interface TransactionalRepositoryInterface
{
    public function startTransaction(): void;
    public function commitTransaction(): void;
    public function addToCommit(object $entity): void;
    public function rollbackTransaction(): void;

}
