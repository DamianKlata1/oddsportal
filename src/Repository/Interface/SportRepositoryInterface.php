<?php

namespace App\Repository\Interface;

use App\Entity\Sport;

interface SportRepositoryInterface
{
    public function save(Sport $sport): Sport;
    public function findOrCreate(string $name): Sport;
    public function find($id, $lockMode = null, $lockVersion = null);


}