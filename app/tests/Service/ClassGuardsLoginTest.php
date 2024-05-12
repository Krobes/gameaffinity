<?php

namespace App\Tests\Service;

use App\Service\ClassGuardsLogin;
use App\Tests\AuxClasses\MockingDatabase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ClassGuardsLoginTest extends KernelTestCase
{
    private $user;
    private $entityManager;
    private $mocking;
    private $classGuards;

    protected function setUp(): void
    {
        $this->classGuards = static::getContainer()->get(ClassGuardsLogin::class);;
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->mocking = new MockingDatabase();
        $this->user = $this->mocking->creatingUser();

        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
    }

    public function testSamePassword(): void
    {
        $isSamePassword = $this->classGuards->guardAgainstDifferentPasswords('hello', 'hello');
        $this->assertTrue($isSamePassword);
    }

    public function testDifferentPassword(): void
    {
        $isSamePassword = $this->classGuards->guardAgainstDifferentPasswords('hello', 'bye');
        $this->assertFalse($isSamePassword);
    }

    public function testExistingNickOrEmail(): void
    {
        $existsNickOrEmail = $this->classGuards->guardAgainstExistingNickorEmail($this->user->getNick(),
            $this->user->getEmail());
        $this->assertFalse($existsNickOrEmail);
    }

    public function testNotExistingNickAndEmail(): void
    {
        $existsNickOrEmail = $this->classGuards->guardAgainstExistingNickorEmail('Games14', 'player1@gmail.com');
        $this->assertTrue($existsNickOrEmail);
    }

    public function testNotExistingNickOrEmail(): void
    {
        $existsNickAndNotEmail = $this->classGuards->guardAgainstExistingNickorEmail($this->user->getNick(),
            'fake-mail@outlook.com');
        $this->assertFalse($existsNickAndNotEmail);
        $existsEmailAndNotNick = $this->classGuards->guardAgainstExistingNickorEmail('Player45',
            $this->user->getEmail());
        $this->assertFalse($existsEmailAndNotNick);
    }

    public function testWeakPassword(): void
    {
        $notUppercase = $this->classGuards->guardAgainstWeakPassword('estoyprobando9/');
        $notLowercase = $this->classGuards->guardAgainstWeakPassword('ESTOYPROBANDO9/');
        $notNumber = $this->classGuards->guardAgainstWeakPassword('Estoyprobando/');
        $notSpecialChar = $this->classGuards->guardAgainstWeakPassword('Estoyprobando9');
        $notMinLength = $this->classGuards->guardAgainstWeakPassword('Estoy9,');
        $this->assertFalse($notUppercase);
        $this->assertFalse($notLowercase);
        $this->assertFalse($notNumber);
        $this->assertFalse($notSpecialChar);
        $this->assertFalse($notMinLength);
    }

    public function testStrongPassword(): void
    {
        $strongPass = $this->classGuards->guardAgainstWeakPassword('vayaMelonQueTieneCiceron9!');
        $this->assertTrue($strongPass);
    }

    protected function tearDown(): void
    {
        $this->entityManager->remove($this->user);
        $this->entityManager->flush();
    }
}