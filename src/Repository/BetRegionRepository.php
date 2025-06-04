<?php

namespace App\Repository;

use App\Entity\BetRegion;
use App\Repository\Trait\PersistenceTrait;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Trait\TransactionManagement;
use App\Repository\Interface\BetRegionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<BetRegion>
 *
 * @method BetRegion|null find($id, $lockMode = null, $lockVersion = null)
 * @method BetRegion|null findOneBy(array $criteria, array $orderBy = null)
 * @method BetRegion[]    findAll()
 * @method BetRegion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetRegionRepository extends ServiceEntityRepository implements BetRegionRepositoryInterface
{
    use TransactionManagement;
    use PersistenceTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BetRegion::class);
    }
   

    //    /**
//     * @return BetRegion[] Returns an array of BetRegion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?BetRegion
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
