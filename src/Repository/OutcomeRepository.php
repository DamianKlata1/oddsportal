<?php

namespace App\Repository;

use App\Entity\Outcome;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Trait\PersistenceTrait;
use App\Repository\Trait\TransactionManagement;
use App\Repository\Interface\OutcomeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Outcome>
 *
 * @method Outcome|null find($id, $lockMode = null, $lockVersion = null)
 * @method Outcome|null findOneBy(array $criteria, array $orderBy = null)
 * @method Outcome[]    findAll()
 * @method Outcome[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutcomeRepository extends ServiceEntityRepository implements OutcomeRepositoryInterface
{
    use TransactionManagement;
    use PersistenceTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outcome::class);
    }
 
//    /**
//     * @return Outcome[] Returns an array of Outcome objects
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

//    public function findOneBySomeField($value): ?Outcome
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
