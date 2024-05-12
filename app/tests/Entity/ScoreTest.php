<?php

namespace App\Tests\Entity;

use App\Entity\Score;
use App\Tests\AuxClasses\MockingDatabase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ScoreTest extends KernelTestCase
{
    private $score;
    private $game;
    private $user;
    private $mocking;
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->mocking = new MockingDatabase();

        $this->game = $this->mocking->creatingGame();
        $this->entityManager->persist($this->game);

        $this->user = $this->mocking->creatingUser();
        $this->entityManager->persist($this->user);

        $this->score = $this->mocking->creatingScore();
        $this->score->setGame($this->game);
        $this->score->setUser($this->user);
        $this->entityManager->persist($this->score);

        $this->entityManager->flush();
    }

    public function testScoreEntity(): void
    {
        $this->assertInstanceOf(Score::class, $this->score);
        $this->assertEquals($this->entityManager->getRepository(Score::class)->findBy([
            "user" => $this->user,
            "game" => $this->game
        ])[0]->getId(), $this->score->getId());
        $this->assertEquals(7, $this->score->getScore());
        $this->assertEquals($this->game, $this->score->getGame());
    }

    protected function tearDown(): void
    {
        $this->entityManager->remove($this->game);
        $this->entityManager->remove($this->user);
        $this->entityManager->remove($this->score);
        $this->entityManager->flush();
    }
}
