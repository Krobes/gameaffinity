<?php

namespace App\Tests\Repository;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testFindNextReleases(): void
    {
        $nextReleases = $this->entityManager->getRepository(Game::class)->findNextReleases(3);
        $this->assertCount(3, $nextReleases);
        foreach ($nextReleases as $nextRelease) {
            $this->assertInstanceOf(Game::class, $nextRelease);
        }
    }

    public function testFindLastReleases(): void
    {
        $lastReleases = $this->entityManager->getRepository(Game::class)->findLastReleases(7);
        $this->assertCount(7, $lastReleases);
        foreach ($lastReleases as $lastRelease) {
            $this->assertInstanceOf(Game::class, $lastRelease);
        }
    }
}
