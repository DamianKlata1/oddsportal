<?php

namespace App\Repository;

use App\Entity\Event;
use DateTimeImmutable;
use Doctrine\ORM\QueryBuilder;
use App\DTO\Event\EventFiltersDTO;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Trait\TransactionManagement;
use App\Repository\Interface\EventRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository implements EventRepositoryInterface
{
    use TransactionManagement;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }
    public function save(Event $event, bool $flush = false): Event
    {
        $this->getEntityManager()->persist($event);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $event;
    }
    public function remove(Event $event, bool $flush = false): void
    {
        $this->getEntityManager()->remove($event);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByLeague(int $leagueId, EventFiltersDTO $eventFiltersDTO): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.outcomes', 'o')
            ->addSelect('o')
            ->where('e.league = :leagueId')
            ->setParameter('leagueId', $leagueId)
            ->getQuery()
            ->getResult();
    }
    public function findByFiltersQueryBuilder(
        ?int $leagueId,
        ?string $nameFilter,
        ?DateTimeImmutable $filterStartDate, 
        ?DateTimeImmutable $filterEndDate    
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.outcomes', 'o')
            ->addSelect('o');

        // League filter
        if($leagueId !== null) {
            $qb->andWhere('e.league = :leagueId')
                ->setParameter('leagueId', $leagueId);
        } 

        // Name filter
        if ($nameFilter !== null && $nameFilter !== '') {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('LOWER(e.homeTeam)', ':name'),
                $qb->expr()->like('LOWER(e.awayTeam)', ':name')
            ))
                ->setParameter('name', '%' . strtolower($nameFilter) . '%');
        }

        // Apply date window filters
        if ($filterStartDate !== null) {
            $qb->andWhere('e.commenceTime >= :filterStartDate')
                ->setParameter('filterStartDate', $filterStartDate);
        } else {
            // Default: If no specific start date (e.g. "Any Date"), ensure events are from now onwards.
            // This 'else' branch ensures that if calculateDateWindow returns null for start (meaning "Any Date"),
            // we still apply the "upcoming" logic.
            $qb->andWhere('e.commenceTime > :now')
                ->setParameter('now', new DateTimeImmutable());
        }

        if ($filterEndDate !== null) {
            $qb->andWhere('e.commenceTime <= :filterEndDate')
                ->setParameter('filterEndDate', $filterEndDate);
        }
        // If $filterStartDate is null and $filterEndDate is null, only the `e.commenceTime > :now` (from the else block) and leagueId filter apply.

        $qb->orderBy('e.commenceTime', 'ASC');

        return $qb;
    }

    public function findPastEvents(): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.commenceTime < :now')
            ->setParameter('now', new DateTimeImmutable())
            ->orderBy('e.commenceTime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
//     * @return Event[] Returns an array of Event objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?Event
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
