<?php

namespace App\Tests\AuxClasses;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;

class MockingDatabase
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function creatingEntity($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function creatingGame(): ?Game
    {
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
        return $game;
    }

    public function creatingDeveloper(): ?Developer
    {
        $developer = new Developer();
        $developer->setId(4234234);
        $developer->setName('Tango Gameworks');
        $developer->setLogo('cl5ua');
        $developer->setCountry('EEUU');
        $developer->setFoundationYear(2010);

        return $developer;
    }

    public function creatingGenre(): ?Genre
    {
        $genre = new Genre();
        $genre->setName('Horror');
        $genre->setId(307);

        return $genre;
    }

}