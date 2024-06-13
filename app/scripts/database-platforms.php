<?php

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

const PLATFORMS = __DIR__ . '/../data/platforms.json';


$body = "fields *; limit 500;";

$headers = [
    'Client-ID: dvcu8zuq30ki7t2flp8ckcr9fo6or6',
    'Authorization: Bearer p0yhifnr6vfwwhuzlxb2mzsnq900b2'
];

$curl = curl_init();

$options = [
    CURLOPT_URL => "https://api.igdb.com/v4/platforms",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_HTTPHEADER => $headers
];

curl_setopt_array($curl, $options);

$platforms = json_decode(curl_exec($curl));

foreach ($platforms as $k => $platform) {
    $platform2 = new Platform();
    $platform2->setId($platform->id);
    $platform2->setName($platform->name);
    if (isset($platform->platform_logo)) {
        $platform2->setLogo(getLogo($platform->platform_logo, $headers));
    } else {
        $platform2->setLogo('');
    }
    $entityManager->persist($platform2);
    dump('Platform injected ' . $k . PHP_EOL);
}

$entityManager->flush();

$jsonData = json_encode($platforms);

file_put_contents(PLATFORMS, $jsonData);

function getLogo($logo, $headers)
{
    $body = "fields *; where id=" . $logo . ';';

    $curl = curl_init();

    $options = [
        CURLOPT_URL => "https://api.igdb.com/v4/platform_logos",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_HTTPHEADER => $headers
    ];

    curl_setopt_array($curl, $options);

    $logo = json_decode(curl_exec($curl));
    if (isset($logo[0]->image_id)) {
        return $logo[0]->image_id;
    } else {
        return '';
    }
}