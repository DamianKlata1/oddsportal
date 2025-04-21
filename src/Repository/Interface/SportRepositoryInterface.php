<?php

namespace App\Repository\Interface;

use App\Entity\Sport;

interface SportRepositoryInterface
{
    public function save(Sport $sport): Sport;
    public function checkIfSportsNameExists(string $name): bool;

}