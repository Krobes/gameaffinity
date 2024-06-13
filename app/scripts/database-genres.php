<?php

use App\Entity\Genre;
use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

$kernel = new Kernel($_ENV['APP_ENV'], true);
$kernel->boot();

$container = $kernel->getContainer();

$entityManager = $container->get('doctrine.orm.entity_manager');

const GENRES = __DIR__ . '/../data/genres.json';


$body = "fields *; limit 500;";

$headers = [
    'Client-ID: dvcu8zuq30ki7t2flp8ckcr9fo6or6',
    'Authorization: Bearer p0yhifnr6vfwwhuzlxb2mzsnq900b2'
];

$curl = curl_init();

$options = [
    CURLOPT_URL => "https://api.igdb.com/v4/genres",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_HTTPHEADER => $headers
];

curl_setopt_array($curl, $options);

$genres = json_decode(curl_exec($curl));

foreach ($genres as $k => $genre) {
    $genre2 = new Genre();
    $genre2->setId($genre->id);
    $genre2->setName($genre->slug);
    $entityManager->persist($genre2);
    dump('Genre injected ' . $k . PHP_EOL);
}

$entityManager->flush();

$jsonData = json_encode($genres);

file_put_contents(GENRES, $jsonData);

