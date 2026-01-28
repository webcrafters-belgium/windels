<?php
// /api/haversine_distance.php

session_start();
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1) Payload ophalen (POST of JSON)
$inputs = $_POST;
if (empty($inputs)) {
    $ct = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($ct, 'application/json') !== false) {
        $raw  = file_get_contents('php://input');
        $json = json_decode($raw, true);
        if (is_array($json)) {
            $inputs = $json;
        }
    }
}

// 2) Vereiste velden
$address = trim($inputs['address'] ?? '');
$zipcode = trim($inputs['zipcode'] ?? '');
$city    = trim($inputs['city']    ?? '');

if (!$address || !$zipcode || !$city) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Adres onvolledig.']);
    exit;
}

// 3) Normaliseren
if (function_exists('mb_convert_case')) {
    $address = mb_convert_case(mb_strtolower($address, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    $city    = mb_convert_case(mb_strtolower($city,    'UTF-8'), MB_CASE_TITLE, 'UTF-8');
} else {
    $address = ucwords(strtolower($address));
    $city    = ucwords(strtolower($city));
}

// Belgie → Belgium (Nominatim werkt niet met “België”)
$full_address = urlencode("{$address}, {$zipcode} {$city}, Belgium");

// 4) Nominatim-request
$url = "https://nominatim.openstreetmap.org/search?q={$full_address}&format=json&limit=1";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => [
        'User-Agent: haversine-api/1.0',
        'Accept-Language: nl'
    ],
    CURLOPT_TIMEOUT        => 5,
]);
$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
$err  = curl_error($ch);
curl_close($ch);

if ($err || $code !== 200) {
    echo json_encode([
        'success' => false,
        'error'   => 'Locatiebepaling mislukt: ' . ($err ?: "HTTP {$code}")
    ]);
    exit;
}

$data = json_decode($response, true);
if (!is_array($data) || empty($data[0]['lat']) || empty($data[0]['lon'])) {
    echo json_encode(['success' => false, 'error' => 'Adres niet gevonden.']);
    exit;
}

$lat = (float)$data[0]['lat'];
$lng = (float)$data[0]['lon'];

// 5) Haversine
function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float {
    $r = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2)**2 +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($dLon/2)**2;
    return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
}

// Jouw winkel-coördinaten
$shop_lat = 51.258904;
$shop_lng = 5.457830;

$distance = haversine($shop_lat, $shop_lng, $lat, $lng);

// 6) Output
echo json_encode([
    'success'     => true,
    'distance'    => round($distance, 1),
    'within_15km' => ($distance <= 15.0)
]);
