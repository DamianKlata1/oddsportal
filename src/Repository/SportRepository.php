<?php

namespace App\Repository;

use App\Entity\Sport;
use App\Repository\Interface\SportRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sport>
 *
 * @method Sport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sport[]    findAll()
 * @method Sport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SportRepository extends ServiceEntityRepository implements SportRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sport::class);
    }

    public function save(Sport $sport): Sport
    {
        $this->getEntityManager()->persist($sport);
        $this->getEntityManager()->flush();
        return $sport;
    }

    public function checkIfSportsNameExists(string $name): bool
    {
        return count($this->findBy(['name' => $name])) > 0;
    }

//    /**
//     * @return Sport[] Returns an array of Sport objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sport
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
