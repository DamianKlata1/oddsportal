<?php

namespace App\Repository;

use App\Entity\League;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Trait\TransactionManagement;
use App\Repository\Interface\LeagueRepositoryInterface;
use App\Repository\Interface\TransactionalRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<League>
 *
 * @method League|null find($id, $lockMode = null, $lockVersion = null)
 * @method League|null findOneBy(array $criteria, array $orderBy = null)
 * @method League[]    findAll()
 * @method League[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeagueRepository extends ServiceEntityRepository implements LeagueRepositoryInterface, TransactionalRepositoryInterface
{
    use TransactionManagement;
    public function __construct(
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, League::class);
    }
    public function save(League $league, bool $flush = false): League
    {
        $this->getEntityManager()->persist($league);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
        return $league;
    }
    public function remove(League $league, bool $flush = false): void
    {
        $this->getEntityManager()->remove($league);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    //    /**
    //     * @return League[] Returns an array of League objects
    //     */
    //    public function findByExampleField($value): array
    //    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?League
    //    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

// public function findOrCreateForRegion(string $name, int $regionId): League
// {
//     $league = $this->findOneBy(['name' => $name, 'region' => $regionId]);
//     if ($league === null) {
//         $league = new League();
//         $league->setName($name);
//         $league->setRegion($this->regionRepository->find($regionId));
//     }
//     return $league;
// }
}
