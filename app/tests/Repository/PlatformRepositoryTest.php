<?php

namespace App\Tests\Repository;

use App\Entity\Platform;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlatformRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testFindByName(): void
    {
        $platforms = $this->entityManager->getRepository(Platform::class)->findByName(['Game Boy Color']);
        $this->assertEquals('Game Boy Color', $platforms[0]->getName());
        $this->assertInstanceOf(Platform::class, $platforms[0]);
    }
}