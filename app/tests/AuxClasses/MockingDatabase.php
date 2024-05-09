<?php

namespace App\Tests\AuxClasses;

use Doctrine\ORM\EntityManagerInterface;

class MockingDatabase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        public array $errors = []
    ) {
    }

    public function creatingEntity($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

}