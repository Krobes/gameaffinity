<?php

use App\Entity\Developer;
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
const ISO_COUNTRY = __DIR__ . '/../data/iso_country.json';

$iso = [];

if (ISO_COUNTRY) {
    $jsonIsoCountry = file_get_contents(ISO_COUNTRY);
    $iso = json_decode($jsonIsoCountry);
}

$body = "fields *; where developed != null & country != null & start_date != null; limit 500;";

$headers = [
    'Client-ID: dvcu8zuq30ki7t2flp8ckcr9fo6or6',
    'Authorization: Bearer p0yhifnr6vfwwhuzlxb2mzsnq900b2'
];

$curl = curl_init();

$options = [
    CURLOPT_URL => "https://api.igdb.com/v4/companies",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $body,
    CURLOPT_HTTPHEADER => $headers
];

curl_setopt_array($curl, $options);

$games = json_decode(curl_exec($curl));

foreach ($games as $k => $game) {
    $games2 = new Developer();
    $games2->setId($game->id);
    $games2->setName($game->name);
    $games2->setCountry(isoTranslate($game->country, $iso));
    $games2->setFoundationYear(date('Y', $game->start_date));
    if (isset($game->logo)) {
        $games2->setLogo(getLogo($game->logo, $headers));
    }
    $entityManager->persist($games2);
    dump('Developer injected ' . $k . PHP_EOL);
}

$entityManager->flush();

$jsonData = json_encode($games);


file_put_contents(DEVELOPERS, $jsonData);

function isoTranslate($countryIso, $iso)
{
    $countryIso = agregarCeros($countryIso);
    $filteredData = array_values(array_filter($iso, function ($object) use ($countryIso) {
        return $object->{'country-code'} === $countryIso;
    }));
    return $filteredData[0]->name;

}

function getLogo($logo, $headers)
{
    $body = "fields *; where id=" . $logo . ';';

    $curl = curl_init();

    $options = [
        CURLOPT_URL => "https://api.igdb.com/v4/company_logos",
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

function agregarCeros($number)
{
    $strNumber = (string)$number;

    $longitud = strlen($strNumber);

    switch ($longitud) {
        case 1:
            return '00' . $strNumber;
            break;
        case 2:
            return '0' . $strNumber;
            break;
        default:
            return $strNumber;
            break;
    }
}
