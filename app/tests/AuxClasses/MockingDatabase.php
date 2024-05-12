<?php

namespace App\Tests\AuxClasses;

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Entity\Score;
use App\Entity\User;

class MockingDatabase
{
    public function __construct()
    {
    }

    public function creatingGame(): ?Game
    {
        $game = new Game();
        $game->setId(312342323);
        $game->setName('The Evil Within 2');
        $game->setCover('co5wgu');
        $game->setDeveloper(null);
        $game->setDateRelease('2017-10-13');
        $game->setSummary('The Evil Within 2 is the latest evolution of survival horror.');
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

    public function creatingUser(): ?User
    {
        $user = new User();
        $user->setEmail('mock_mail@gmail.com');
        $user->setNick('Samus Aran');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('1'); //Puesto que no nos interesa ahora comprobar nada respecto a la pass la dejamos así
        return $user;
    }

    public function creatingPlatform(): ?Platform
    {
        $platform = new Platform();
        $platform->setId(34567);
        $platform->setName('Daw Master System');
        $platform->setLogo('cg356');
        return $platform;
    }

    public function creatingScore(): ?Score
    {
        $score = new Score();
        $score->setScore(7);
        return $score;
    }
}