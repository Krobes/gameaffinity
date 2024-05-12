<?php

namespace App\Tests\Entity;

use App\Entity\Platform;
use App\Tests\AuxClasses\MockingDatabase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlatformTest extends KernelTestCase
{
    private $platform;
    private $game;
    private $mocking;
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->mocking = new MockingDatabase();

        $this->game = $this->mocking->creatingGame();
        $this->entityManager->persist($this->game);

        $this->platform = $this->mocking->creatingPlatform();
        $this->platform->addGame($this->game);
        $this->entityManager->persist($this->platform);

        $this->entityManager->flush();
    }

    public function testPlatformEntity(): void
    {
        $this->assertInstanceOf(Platform::class, $this->platform);
        $this->assertEquals(34567, $this->platform->getId());
        $this->assertEquals('Daw Master System', $this->platform->getName());
        $this->assertEquals('cg356', $this->platform->getLogo());
        $this->assertEquals($this->game, $this->platform->getGames()[0]);
        $this->platform->removeGame($this->game);
        $this->assertNull($this->platform->getGames()[0]);
    }

    protected function tearDown(): void
    {
        $this->entityManager->remove($this->game);
        $this->entityManager->remove($this->platform);
        $this->entityManager->flush();
    }
}
