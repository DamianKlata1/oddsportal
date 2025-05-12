<?php

namespace App\Repository\Interface;

use App\Entity\Event;
use Doctrine\Persistence\ObjectRepository;

interface EventRepositoryInterface extends TransactionalRepositoryInterface, ObjectRepository
{
    public function save(Event $event, bool $flush = false): Event;
    public function findWithOutcomesByLeague(int $leagueId): array;

}
