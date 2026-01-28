<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc'; // verwacht $packlink_api_key

header('Content-Type: application/json');

// Afzender defaults
$from_zip   = $_POST['from_zip']      ?? '3950';
$from_city  = $_POST['from_city']     ?? 'Bocholt';
$from_ctry  = $_POST['from_country']  ?? 'BE';

// Ontvanger (verplicht in POST)
$to_zip     = $_POST['to_zip']        ?? '';
$to_city    = $_POST['to_city']       ?? '';
$to_ctry    = $_POST['to_country']    ?? 'BE';

$weight     = (float)($_POST['weight'] ?? 1.0); // kg
$length     = (int)($_POST['length'] ?? 20);
$width      = (int)($_POST['width']  ?? 15);
$height     = (int)($_POST['height'] ?? 10);

if ($to_zip === '' || $to_city === '') {
    echo json_encode(["success" => false, "error" => "to_zip en to_city zijn verplicht"]);
    exit;
}

$payload = [
    "from" => [
        "country" => strtoupper($from_ctry),
        "zip"     => $from_zip,
        "city"    => $from_city
    ],
    "to" => [
        "country" => strtoupper($to_ctry),
        "zip"     => $to_zip,
        "city"    => $to_city
    ],
    "packages" => [[
        "weight" => max($weight, 0.01),
        "length" => max($length, 1),
        "width"  => max($width, 1),
        "height" => max($height, 1)
    ]]
];

$ch = curl_init("https://api.packlink.com/v1/services");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER     => [
        "Authorization: Bearer {$packlink_api_key}",
        "Content-Type: application/json",
        "Accept: application/json"
    ]
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode !== 200) {
    echo json_encode([
        "success" => false,
        "error"   => "Packlink API error ($httpcode)",
        "raw"     => $response
    ]);
    exit;
}

$data = json_decode($response, true);
echo json_encode([
    "success" => true,
    "rates"   => $data
]);
