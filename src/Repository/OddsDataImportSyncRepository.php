<?php

namespace App\Repository;

use App\Entity\OddsDataImportSync;
use App\Repository\Interface\OddsDataImportSyncRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OddsDataImportSync>
 *
 * @method OddsDataImportSync|null find($id, $lockMode = null, $lockVersion = null)
 * @method OddsDataImportSync|null findOneBy(array $criteria, array $orderBy = null)
 * @method OddsDataImportSync[]    findAll()
 * @method OddsDataImportSync[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OddsDataImportSyncRepository extends ServiceEntityRepository implements OddsDataImportSyncRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OddsDataImportSync::class);
    }
    public function save(OddsDataImportSync $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
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
