<?php

namespace App\Repository\Interface;

use App\Entity\Sport;
use Doctrine\Persistence\ObjectRepository;

interface SportRepositoryInterface extends TransactionalRepositoryInterface, ObjectRepository
{
    public function save(Sport $sport, bool $flush = false): Sport;
    // public function findOrCreate(string $name): Sport;

}