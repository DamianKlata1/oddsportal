<?php

namespace App\Repository;

use App\Entity\LeagueOddsImportSync;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Trait\PersistenceTrait;
use App\Repository\Trait\TransactionManagement;
use App\Repository\Interface\LeagueOddsImportSyncRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<LeagueOddsImportSync>
 *
 * @method LeagueOddsImportSync|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeagueOddsImportSync|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeagueOddsImportSync[]    findAll()
 * @method LeagueOddsImportSync[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeagueOddsImportSyncRepository extends ServiceEntityRepository implements LeagueOddsImportSyncRepositoryInterface
{

    use TransactionManagement;
    use PersistenceTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeagueOddsImportSync::class);
    }

    //    /**
//     * @return OddsDataImportSync[] Returns an array of OddsDataImportSync objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?OddsDataImportSync
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
