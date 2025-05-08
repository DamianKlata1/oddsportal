<?php

namespace App\Repository;

use App\Entity\Bookmaker;
use App\Repository\Interface\BookmakerRepositoryInterface;
use App\Repository\Trait\TransactionManagement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bookmaker>
 *
 * @method Bookmaker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookmaker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookmaker[]    findAll()
 * @method Bookmaker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookmakerRepository extends ServiceEntityRepository implements BookmakerRepositoryInterface
{
    use TransactionManagement;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bookmaker::class);
    }

    public function save(Bookmaker $bookmaker, bool $flush = false): Bookmaker
    {
        $this->getEntityManager()->persist($bookmaker);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $bookmaker;
    }

//    /**
//     * @return Bookmaker[] Returns an array of Bookmaker objects
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

//    public function findOneBySomeField($value): ?Bookmaker
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
