<?php
// functions/shop/cart/check_distance.php

session_start();
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// 1) Payload bepalen (form-urlencoded of JSON)
$inputs = $_POST;
if (empty($inputs)) {
    $ct = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($ct, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        if (is_array($json)) {
            $inputs = $json;
        }
    }
}

// 2) Input valideren & normaliseren
$address = trim($inputs['address'] ?? '');
$zipcode = trim($inputs['zipcode'] ?? '');
$city    = trim($inputs['city']    ?? '');

if (!$address || !$zipcode || !$city) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Adres onvolledig.']);
    exit;
}

// Title Case (één hoofdletter per woord)
if (function_exists('mb_convert_case')) {
    $address = mb_convert_case(mb_strtolower($address, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    $city    = mb_convert_case(mb_strtolower($city,    'UTF-8'), MB_CASE_TITLE, 'UTF-8');
} else {
    $address = ucwords(strtolower($address));
    $city    = ucwords(strtolower($city));
}

// 3) Bouw query voor Nominatim
$full_address = urlencode("{$address}, {$zipcode} {$city}, België");
$url = "https://nominatim.openstreetmap.org/search?q={$full_address}&format=json&limit=1";

// 4) cURL-aanroep
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => [
        'User-Agent: windels-shop/1.0',
        'Accept-Language: nl'
    ],
    CURLOPT_TIMEOUT        => 5,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($curlErr || $httpCode !== 200) {
    http_response_code(502);
    echo json_encode([
        'success' => false,
        'error'   => 'Locatiebepaling mislukt: ' . ($curlErr ?: "HTTP {$httpCode}")
    ]);
    exit;
}

$data = json_decode($response, true);
if (!is_array($data) || empty($data[0]['lat']) || empty($data[0]['lon'])) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Adres niet gevonden.']);
    exit;
}

$customer_lat = (float)$data[0]['lat'];
$customer_lng = (float)$data[0]['lon'];

// 5) Haversine-functie
function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float {
    $r = 6371; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) ** 2
        + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
        * sin($dLon/2) ** 2;
    return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
}

// 6) Bereken afstand
$shop_lat = 51.258904;
$shop_lng = 5.457830;
$distance = haversine($shop_lat, $shop_lng, $customer_lat, $customer_lng);

// 7) Response
echo json_encode([
    'success'     => true,
    'distance'    => round($distance, 1),      // afgerond op 0,1 km
    'within_15km' => ($distance <= 15.0)
]);
