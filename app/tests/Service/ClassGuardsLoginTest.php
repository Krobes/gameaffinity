<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\ClassGuardsLogin;
use App\Tests\AuxClasses\MockingDatabase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ClassGuardsLoginTest extends KernelTestCase
{
    private $entityManager;
    private $mocking;
    private $classGuards;

    protected function setUp(): void
    {
        $this->classGuards = static::getContainer()->get(ClassGuardsLogin::class);;
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->mocking = new MockingDatabase($this->entityManager);
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
        $newUser = new User();
        $newUser->setEmail('mock_mail@gmail.com');
        $newUser->setNick('Samus Aran');
        $newUser->setPassword('1'); //Puesto que no nos interesa ahora comprobar nada respecto a la pass la dejamos en blanco

        $this->mocking->creatingEntity($newUser);
        $existsNickOrEmail = $this->classGuards->guardAgainstExistingNickorEmail('Samus Aran', 'mock_mail@gmail.com');
        $this->assertFalse($existsNickOrEmail);
    }

    public function testNotExistingNickAndEmail(): void
    {
        $existsNickOrEmail = $this->classGuards->guardAgainstExistingNickorEmail('Games14', 'player1@gmail.com');
        $this->assertTrue($existsNickOrEmail);
    }

    public function testNotExistingNickOrEmail(): void
    {
        $newUser = new User();
        $newUser->setEmail('mock_mail@gmail.com');
        $newUser->setNick('Samus Aran');
        $newUser->setPassword('1'); //Puesto que no nos interesa ahora comprobar nada respecto a la pass la dejamos en blanco
        $this->mocking->creatingEntity($newUser);
        $existsNickAndNotEmail = $this->classGuards->guardAgainstExistingNickorEmail('Samus Aran',
            'fake-mail@outlook.com');
        $this->assertFalse($existsNickAndNotEmail);
        $existsEmailAndNotNick = $this->classGuards->guardAgainstExistingNickorEmail('Player45',
            'mock_mail@gmail.com');
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
        $repository = $this->entityManager->getRepository(User::class);
        $lastUser = $repository->findOneBy(['email' => 'mock_mail@gmail.com', 'nick' => 'Samus Aran']);

        if ($lastUser instanceof User) {
            $this->entityManager->remove($lastUser);
            $this->entityManager->flush();
        }
    }
}