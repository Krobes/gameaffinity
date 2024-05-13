<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    //    /**
    //     * @return Game[] Returns an array of Game objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function findNextReleases(int $numberOfGames): ?array
    {
        $currentDate = new \DateTime();

        return $this->createQueryBuilder('g')
            ->where("g.date_release > :currentDate and g.summary != ''")
            ->setParameter('currentDate', $currentDate->format('Y-m-d'))
            ->orderBy('g.date_release', 'DESC')
            ->setMaxResults($numberOfGames)
            ->getQuery()
            ->getResult();
    }

    public function findLastReleases($numberOfGames): ?array
    {
        $currentDate = new \DateTime();

        return $this->createQueryBuilder('g')
            ->where("g.date_release <= :currentDate and g.summary != '' and g.date_release != ''")
            ->setParameter('currentDate', $currentDate->format('Y-m-d'))
            ->orderBy('g.date_release', 'DESC')
            ->setMaxResults($numberOfGames)
            ->getQuery()
            ->getResult();
    }
}
