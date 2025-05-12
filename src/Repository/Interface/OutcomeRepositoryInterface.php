<?php

namespace App\Repository\Interface;

use App\Entity\Outcome;
use Doctrine\Persistence\ObjectRepository;

interface OutcomeRepositoryInterface extends ObjectRepository
{
    public function save(Outcome $outcome, bool $flush = false): Outcome;

}
