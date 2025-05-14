<?php

namespace App\Repository\Interface;

use App\Entity\Bookmaker;
use Doctrine\Persistence\ObjectRepository;

interface BookmakerRepositoryInterface extends TransactionalRepositoryInterface, ObjectRepository
{
    public function save(Bookmaker $bookmaker, bool $flush = false): Bookmaker;
    public function findOrCreate(string $name): Bookmaker;


}
