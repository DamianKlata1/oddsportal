<?php

namespace App\Repository\Interface;

use App\DTO\Event\EventFiltersDTO;
use App\Entity\Event;
use DateTimeImmutable;
use Doctrine\ORM\QueryBuilder;
/**
 * @extends RepositoryInterface<Event>
 */
interface EventRepositoryInterface extends RepositoryInterface, TransactionalRepositoryInterface
{
    public function findByLeague(int $leagueId, EventFiltersDTO $eventFiltersDTO): array;
    public function findByFiltersQueryBuilder(
        ?int $leagueId,
        ?string $name,
        ?DateTimeImmutable $from,
        ?DateTimeImmutable $to
    ): QueryBuilder;
    public function findPastEvents(): array;

}
