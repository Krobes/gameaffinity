<?php

namespace App\Tests\Entity;

use App\Entity\Developer;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DeveloperTest extends KernelTestCase
{
    private $game;
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $game = new Game();
        $game->setId(312342323);
        $game->setName('The Evil Within 2');
        $game->setCover('co5wgu');
        $game->setDeveloper(null);
        $game->setDateRelease('2017-10-13');
        $game->setSummary('The Evil Within 2 is the latest evolution of survival horror. 
        Detective Sebastian Castellanos has lost it all. But when given a chance to save his daughter, 
        he must descend once more into the nightmarish world of STEM. Horrifying threats emerge from every corner as the world twists and warps around him. 
        Will Sebastian face adversity head on with weapons and traps, or sneak through the shadows to survive.');
        $this->entityManager->persist($game);
        $this->entityManager->flush();
        $this->game = $game;
    }

    public function testDeveloperEntity(): void
    {
        $developer = new Developer();
        $developer->setId(4234234);
        $developer->setName('Tango Gameworks');
        $developer->setLogo('cl5ua');
        $developer->setCountry('EEUU');
        $developer->setFoundationYear(2010);
        $developer->addGame($this->game);

        $this->entityManager->persist($developer);
        $this->entityManager->flush();

        $this->assertInstanceOf(Developer::class, $developer);
        $this->assertEquals(4234234, $developer->getId());
        $this->assertEquals('Tango Gameworks', $developer->getName());
        $this->assertEquals('cl5ua', $developer->getLogo());
        $this->assertEquals('EEUU', $developer->getCountry());
        $this->assertEquals(2010, $developer->getFoundationYear());
        $this->assertEquals($this->game, $developer->getGames()[0]);

        $developer->removeGame($this->game);
        $this->assertNull($developer->getGames()[0]);
    }

    protected function tearDown(): void
    {
        $gameRepository = $this->entityManager->getRepository(Game::class);
        $developerRepository = $this->entityManager->getRepository(Developer::class);
        $game = $gameRepository->find($this->game->getId());
        $developer = $developerRepository->find(4234234);
        $this->entityManager->remove($game);
        $this->entityManager->remove($developer);
        $this->entityManager->flush();
    }
}
