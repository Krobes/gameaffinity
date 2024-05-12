<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Tests\AuxClasses\MockingDatabase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
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

        $this->score = $this->mocking->creatingScore();
        $this->score->setGame($this->game);
        $this->entityManager->persist($this->score);

        $this->user = $this->mocking->creatingUser();
        $this->user->addScore($this->score);
        $this->entityManager->persist($this->user);

        $this->entityManager->flush();
    }

    public function testUserEntity(): void
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertEquals($this->entityManager->getRepository(User::class)->findBy([
            'email' => 'mock_mail@gmail.com',
        ])[0]->getId(), $this->user->getId());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEquals('mock_mail@gmail.com', $this->user->getEmail());
        $this->assertEquals('mock_mail@gmail.com', $this->user->getUserIdentifier());
        $this->assertEquals('Samus Aran', $this->user->getNick());
        $this->assertEquals('1', $this->user->getPassword());
        $this->assertEquals($this->score, $this->user->getScores()[0]);
        $this->user->removeScore($this->user->getScores()[0]);
        $this->assertNull($this->user->getScores()[0]);
    }

    protected function tearDown(): void
    {
        $this->entityManager->remove($this->score);
        $this->entityManager->remove($this->game);
        $this->entityManager->remove($this->user);
        $this->entityManager->flush();
    }
}
