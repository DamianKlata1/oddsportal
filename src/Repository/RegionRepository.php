<?php

namespace App\Repository;

use App\Entity\Region;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Interface\SportRepositoryInterface;
use App\Repository\Interface\RegionRepositoryInterface;
use App\Service\Interface\LogoPath\RegionLogoPathResolverInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Region>
 *
 * @method Region|null find($id, $lockMode = null, $lockVersion = null)
 * @method Region|null findOneBy(array $criteria, array $orderBy = null)
 * @method Region[]    findAll()
 * @method Region[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegionRepository extends ServiceEntityRepository implements RegionRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly RegionLogoPathResolverInterface $regionLogoPathResolver,
        private readonly SportRepositoryInterface $sportRepository
    ) {
        parent::__construct($registry, Region::class);
    }
    public function save(Region $region): Region
    {
        $this->getEntityManager()->persist($region);
        $this->getEntityManager()->flush();

        return $region;
    }
    public function findOrCreateForSport(string $name, int $sportId): Region
    {
        $region = $this->findOneBy(['name' => $name, 'sport' => $sportId]);
        if ($region === null) {
            $region = new Region();
            $region->setName($name);
            $region->setSport($this->sportRepository->find($sportId));
            $region->setLogoPath($this->regionLogoPathResolver->resolve($name));
            $this->save($region);
        }
        return $region;
    }
    //    /**
//     * @return Region[] Returns an array of Region objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?Region
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
