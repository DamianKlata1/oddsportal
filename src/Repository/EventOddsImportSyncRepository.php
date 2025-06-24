<?php

namespace App\Repository;

use App\Entity\EventOddsImportSync;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Trait\PersistenceTrait;
use App\Repository\Trait\TransactionManagement;
use App\Repository\Interface\EventOddsImportSyncRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<EventOddsImportSync>
 *
 * @method EventOddsImportSync|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventOddsImportSync|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventOddsImportSync[]    findAll()
 * @method EventOddsImportSync[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventOddsImportSyncRepository extends ServiceEntityRepository implements EventOddsImportSyncRepositoryInterface
{
    use TransactionManagement;
    use PersistenceTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventOddsImportSync::class);
    }

//    /**
//     * @return EventOddsImportSync[] Returns an array of EventOddsImportSync objects
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

//    public function findOneBySomeField($value): ?EventOddsImportSync
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
