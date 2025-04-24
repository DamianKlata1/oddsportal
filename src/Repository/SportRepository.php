<?php

namespace App\Repository;

use App\Entity\Sport;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Trait\TransactionManagement;
use App\Repository\Interface\SportRepositoryInterface;
use App\Repository\Interface\TransactionalRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Sport>
 *
 * @method Sport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sport[]    findAll()
 * @method Sport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SportRepository extends ServiceEntityRepository implements SportRepositoryInterface, TransactionalRepositoryInterface
{
    use TransactionManagement;
    public function __construct(
        ManagerRegistry $registry,
        )
    {
        parent::__construct($registry, Sport::class);
    }

    public function save(Sport $sport, bool $flush = false): Sport
    {
        $this->getEntityManager()->persist($sport);
        if($flush){
            $this->getEntityManager()->flush();
        }
        return $sport;
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

// public function findOrCreate(string $name): Sport
// {   
//     $sport = $this->findOneBy(['name' => $name]);
//     if ($sport === null) {
//         $sport = new Sport();
//         $sport->setName($name);
//         $sport->setLogoPath($this->sportLogoPathResolver->resolve($name));
//     }
//     return $sport;
// }