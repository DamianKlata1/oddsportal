<?php

namespace App\Repository\Interface;

use App\DTO\Event\EventFiltersDTO;
use App\Entity\Event;
use DateTimeImmutable;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;

interface EventRepositoryInterface extends TransactionalRepositoryInterface, ObjectRepository
{
    public function save(Event $event, bool $flush = false): Event;
    public function remove(Event $event, bool $flush = false): void;
    public function findByLeague(int $leagueId, EventFiltersDTO $eventFiltersDTO): array;
    public function findByFiltersQueryBuilder(?int $leagueId, ?string $name, ?DateTimeImmutable $from, ?DateTimeImmutable $to): QueryBuilder;
    public function findPastEvents(): array;

}
