<?php

namespace App\Tests\Entity;

use App\Entity\Genre;
use App\Tests\AuxClasses\MockingDatabase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenreTest extends KernelTestCase
{
    private $genre;
    private $game;
    private $mocking;

    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->mocking = new MockingDatabase($this->entityManager);

        $this->game = $this->mocking->creatingGame();
        $this->entityManager->persist($this->game);

        $this->genre = $this->mocking->creatingGenre();
        $this->genre->addGame($this->game);
        $this->entityManager->persist($this->genre);

        $this->entityManager->flush();
    }

    public function testGenreEntity(): void
    {
        $this->assertInstanceOf(Genre::class, $this->genre);
        $this->assertEquals(307, $this->genre->getId());
        $this->assertEquals('Horror', $this->genre->getName());
        $this->assertEquals($this->game, $this->genre->getGames()[0]);
        $this->genre->removeGame($this->game);
        $this->assertNull($this->genre->getGames()[0]);
    }

    protected function tearDown(): void
    {
        $this->entityManager->remove($this->game);
        $this->entityManager->remove($this->genre);
        $this->entityManager->flush();
    }
}
