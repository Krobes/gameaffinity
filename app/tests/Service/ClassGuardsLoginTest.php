<?php

namespace App\Tests\Service;

use App\Service\ClassGuardsLogin;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ClassGuardsLoginTest extends KernelTestCase
{
    public function testSamePassword(): void
    {
        $classGuards = static::getContainer()->get(ClassGuardsLogin::class);
        $isSamePassword = $classGuards->guardAgainstDifferentPasswords('hello', 'hello');
        $this->assertTrue($isSamePassword);
    }

    public function testDifferentPassword(): void
    {
        $classGuards = static::getContainer()->get(ClassGuardsLogin::class);
        $isSamePassword = $classGuards->guardAgainstDifferentPasswords('hello', 'bye');
        $this->assertFalse($isSamePassword);
    }

    public function testExistingNickorEmail(): void
    {
        $classGuards = static::getContainer()->get(ClassGuardsLogin::class);
        $existsNickorEmail = $classGuards->guardAgainstExistingNickorEmail('Krobes', 'rafa_lara@hotmail.es');
        $this->assertFalse($existsNickorEmail);
    }

    public function testNotExistingNickandEmail(): void
    {
        $classGuards = static::getContainer()->get(ClassGuardsLogin::class);
        $existsNickorEmail = $classGuards->guardAgainstExistingNickorEmail('Games14', 'player1@gmail.com');
        $this->assertTrue($existsNickorEmail);
    }

    public function testNotExistingNickorEmail(): void
    {
        $classGuards = static::getContainer()->get(ClassGuardsLogin::class);
        $existsNickandNotEmail = $classGuards->guardAgainstExistingNickorEmail('Krobes', 'player1@gmail.com');
        $this->assertFalse($existsNickandNotEmail);
        $existsEmailandNotNick = $classGuards->guardAgainstExistingNickorEmail('Player45', 'rafa_lara@hotmail.es');
        $this->assertFalse($existsEmailandNotNick);
    }
}