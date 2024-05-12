<?php

namespace App\Tests\Entity;

use App\Entity\Game;
use App\Tests\AuxClasses\MockingDatabase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameTest extends KernelTestCase
{
    private $game;
    private $user;
    private $score;
    private $platform;
    private $genre;
    private $developer;
    private $mocking;
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->mocking = new MockingDatabase();

        $this->developer = $this->mocking->creatingDeveloper();
        $this->entityManager->persist($this->developer);

        $this->user = $this->mocking->creatingUser();
        $this->entityManager->persist($this->user);

        $this->platform = $this->mocking->creatingPlatform();
        $this->entityManager->persist($this->platform);

        $this->genre = $this->mocking->creatingGenre();
        $this->entityManager->persist($this->genre);

        $this->score = $this->mocking->creatingScore();
        $this->score->setUser($this->user);
        $this->entityManager->persist($this->score);

        $this->game = $this->mocking->creatingGame();
        $this->game->addScore($this->score);
        $this->game->addGenre($this->genre);
        $this->game->addPlatform($this->platform);
        $this->game->setDeveloper($this->developer);
        $this->entityManager->persist($this->game);

        $this->entityManager->flush();
    }

    public function testGameEntity(): void
    {
        $this->assertInstanceOf(Game::class, $this->game);
        $this->assertEquals(312342323, $this->game->getId());
        $this->assertEquals('2017-10-13', $this->game->getDateRelease());
        $this->assertEquals('co5wgu', $this->game->getCover());
        $this->assertEquals('The Evil Within 2', $this->game->getName());
        $this->assertEquals('The Evil Within 2 is the latest evolution of survival horror.', $this->game->getSummary());
        $this->assertEquals($this->developer, $this->game->getDeveloper());
        $this->assertEquals($this->genre, $this->game->getGenres()[0]);
        $this->game->removeGenre($this->genre);
        $this->assertNull($this->game->getGenres()[0]);
        $this->assertEquals($this->platform, $this->game->getPlatforms()[0]);
        $this->game->removePlatform($this->platform);
        $this->assertNull($this->game->getPlatforms()[0]);
        $this->assertEquals($this->score, $this->game->getScores()[0]);
        $this->game->removeScore($this->score);
        $this->assertNull($this->game->getScores()[0]);
    }

    protected function tearDown(): void
    {
        $this->entityManager->remove($this->score);
        $this->entityManager->remove($this->genre);
        $this->entityManager->remove($this->platform);
        $this->entityManager->remove($this->user);
        $this->entityManager->remove($this->game);
        $this->entityManager->remove($this->developer);
        $this->entityManager->flush();
    }
}
