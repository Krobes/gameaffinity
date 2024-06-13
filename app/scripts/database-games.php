<?php

use App\Entity\Developer;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Platform;
use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

$kernel = new Kernel($_ENV['APP_ENV'], true);
$kernel->boot();

$container = $kernel->getContainer();

$entityManager = $container->get('doctrine.orm.entity_manager');

const DEVELOPERS = __DIR__ . '/../data/developers.json';

$developers = [];

if (DEVELOPERS) {
    $json = file_get_contents(DEVELOPERS);
    $developers = json_decode($json);
}

$headers = [
    'Client-ID: dvcu8zuq30ki7t2flp8ckcr9fo6or6',
    'Authorization: Bearer p0yhifnr6vfwwhuzlxb2mzsnq900b2'
];

$curl = curl_init();

$options = [
    CURLOPT_URL => "https://api.igdb.com/v4/games/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => $headers
];

foreach ($developers as $k => $developer) {
    try {
        foreach ($developer->developed as $gameId) {
            $body = "fields *; where id=" . $gameId . ';';
            $options[CURLOPT_POSTFIELDS] = $body;
            curl_setopt_array($curl, $options);
            $gameDeveloper = json_decode(curl_exec($curl));
            $developerObj = $entityManager->getRepository(Developer::class)->find($developer->id);

            if (!empty($gameDeveloper) && !empty($gameDeveloper[0]->release_dates[0])) {
                $game = $entityManager->getRepository(Game::class)->find($gameId);
                if (!$game) {
                    $game = new Game();
                    $game->setId($gameDeveloper[0]->id);
                }
                $game->setName($gameDeveloper[0]->name);
                if (isset($gameDeveloper[0]->summary)) {
                    $game->setSummary($gameDeveloper[0]->summary);
                } else {
                    $game->setSummary('');
                }
                $release_date = new DateTime();
                $game->setDateRelease(date('Y-m-d', getReleaseDate($gameDeveloper[0]->release_dates[0])));
                $game->setDeveloper($developerObj);
                if (isset($gameDeveloper[0]->genres)) {
                    foreach ($gameDeveloper[0]->genres as $genre) {
                        $game->addGenre($entityManager->getRepository(Genre::class)->find($genre));
                    }
                }
                if (isset($gameDeveloper[0]->platforms)) {
                    foreach ($gameDeveloper[0]->platforms as $platform) {
                        $game->addPlatform($entityManager->getRepository(Platform::class)->find($platform));
                    }
                }
                if (isset($gameDeveloper[0]->cover)) {
                    $game->setCover(findCover($gameDeveloper[0]->cover, $headers));
                } else {
                    $game->setCover('');
                }
                $entityManager->persist($game);
            }

        }
        $entityManager->flush();
        print_r('Developer ' . $k . ' completed.' . PHP_EOL);
    } catch (Exception $e) {
        print_r('Error processing developer ' . $k . ': ' . $e->getMessage() . PHP_EOL);
    }
}


function findCover($coverId, $headers)
{
    $curl = curl_init();

    $options = [
        CURLOPT_URL => "https://api.igdb.com/v4/covers",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => "fields image_id; where id=" . $coverId . ';'
    ];
    curl_setopt_array($curl, $options);
    $coverJson = json_decode(curl_exec($curl));
    return $coverJson[0]->image_id;
}

function getReleaseDate($release_date)
{
    $headers = [
        'Client-ID: dvcu8zuq30ki7t2flp8ckcr9fo6or6',
        'Authorization: Bearer p0yhifnr6vfwwhuzlxb2mzsnq900b2'
    ];

    $curl = curl_init();

    $options = [
        CURLOPT_URL => "https://api.igdb.com/v4/release_dates",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => "fields *; where id=" . $release_date . ';'
    ];
    curl_setopt_array($curl, $options);
    $releaseJson = json_decode(curl_exec($curl));

    if (!empty($releaseJson) && isset($releaseJson[0]->date)) {
        return $releaseJson[0]->date;
    }
    return null;
}