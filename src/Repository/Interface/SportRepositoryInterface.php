<?php

namespace App\Repository\Interface;

use App\Entity\Sport;

interface SportRepositoryInterface extends TransactionalRepositoryInterface
{
    public function save(Sport $sport, bool $flush = false): Sport;
    public function findOrCreate(string $name): Sport;
    public function find($id, $lockMode = null, $lockVersion = null);


}