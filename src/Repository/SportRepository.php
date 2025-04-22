<?php

namespace App\Repository;

use App\Entity\Sport;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Interface\SportRepositoryInterface;
use App\Service\Interface\LogoPath\SportLogoPathResolverInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
    public function __construct(
        ManagerRegistry $registry,
        private readonly SportLogoPathResolverInterface $sportLogoPathResolver
        )
    {
        parent::__construct($registry, Sport::class);
    }

    public function save(Sport $sport): Sport
    {
        $this->getEntityManager()->persist($sport);
        $this->getEntityManager()->flush();
        return $sport;
    }
    

    public function findOrCreate(string $name): Sport
    {
        $sport = $this->findOneBy(['name' => $name]);
        if ($sport === null) {
            $sport = new Sport();
            $sport->setName($name);
            $sport->setLogoPath($this->sportLogoPathResolver->resolve($name));
            $this->save($sport);
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
