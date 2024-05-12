<?php

namespace App\Tests\Entity;

use App\Entity\Developer;
use App\Tests\AuxClasses\MockingDatabase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DeveloperTest extends KernelTestCase
{
    private $game;
    private $developer;
    private $mocking;
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->mocking = new MockingDatabase();

        $this->game = $this->mocking->creatingGame();
        $this->entityManager->persist($this->game);

        $this->developer = $this->mocking->creatingDeveloper();
        $this->developer->addGame($this->game);
        $this->entityManager->persist($this->developer);

        $this->entityManager->flush();
    }

    public function testDeveloperEntity(): void
    {
        $this->assertInstanceOf(Developer::class, $this->developer);
        $this->assertEquals(4234234, $this->developer->getId());
        $this->assertEquals('Tango Gameworks', $this->developer->getName());
        $this->assertEquals('cl5ua', $this->developer->getLogo());
        $this->assertEquals('EEUU', $this->developer->getCountry());
        $this->assertEquals(2010, $this->developer->getFoundationYear());
        $this->assertEquals($this->game, $this->developer->getGames()[0]);
        $this->developer->removeGame($this->game);
        $this->assertNull($this->developer->getGames()[0]);
    }

    protected function tearDown(): void
    {
        $this->entityManager->remove($this->game);
        $this->entityManager->remove($this->developer);
        $this->entityManager->flush();
    }
}
