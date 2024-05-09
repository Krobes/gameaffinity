<?php

namespace App\Repository;

use App\Entity\Platform;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Platform>
 */
class PlatformRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Platform::class);
    }

    //    /**
    //     * @return Platform[] Returns an array of Platform objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function findByName(array $platforms): ?array
    {
        $allPlatforms = [];
        foreach ($platforms as $platform) {
            $allPlatforms[] = $this->createQueryBuilder('p')
                ->andWhere('p.name = :name')
                ->setParameter('name', $platform)
                ->getQuery()
                ->getOneOrNullResult();
        }
        return $allPlatforms;
    }
}
