<?php
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 0);
error_reporting(E_ALL);

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed = [
    'https://dev.windelsgreen-decoresin.com',
    'https://uitvaart.windelsgreen-decoresin.com'
];

if (in_array($origin, $allowed, true)) {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
}

header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");


/**
 * Haversine-afstand in km tussen twee punten (lat/lng in graden)
 */
function haversine_km(float $lat1,float $lon1,float $lat2,float $lon2,float $earthRadius = 6371.0): float {
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    $dLat = $lat2 - $lat1;
    $dLon = $lon2 - $lon1;

    $a = sin($dLat/2)**2 + cos($lat1)*cos($lat2)*sin($dLon/2)**2;
    $c = 2 * asin(min(1, sqrt($a)));

    return $earthRadius * $c;
}

/**
 * Coördinaten uit querystring halen.
 * Ondersteunt:
 *  - origin_lat / origin_lng
 *  - origin_lat / origin_lon
 *  - origin="lat,lng"
 * zelfde voor "destination".
 */
function parse_point(string $prefix): ?array {
    $lat = filter_input(INPUT_GET, $prefix.'_lat', FILTER_VALIDATE_FLOAT);
    $lng = filter_input(INPUT_GET, $prefix.'_lng', FILTER_VALIDATE_FLOAT);
    if ($lng === false || $lng === null) {
        $lng = filter_input(INPUT_GET, $prefix.'_lon', FILTER_VALIDATE_FLOAT);
    }

    if ($lat !== false && $lat !== null && $lng !== false && $lng !== null) {
        return ['lat'=>$lat,'lng'=>$lng];
    }

    // fallback: "?origin=51.25,5.52"
    $combined = $_GET[$prefix] ?? '';
    if ($combined !== '') {
        $parts = explode(',', $combined);
        if (count($parts) === 2) {
            $lat = filter_var(trim($parts[0]), FILTER_VALIDATE_FLOAT);
            $lng = filter_var(trim($parts[1]), FILTER_VALIDATE_FLOAT);
            if ($lat !== false && $lng !== false) {
                return ['lat'=>$lat,'lng'=>$lng];
            }
        }
    }
    return null;
}

// origin en destination inlezen
$origin      = parse_point('origin');
$destination = parse_point('destination');

if (!$origin || !$destination) {
    http_response_code(422);
    echo json_encode([
        'ok'    => false,
        'error' => 'MISSING_OR_INVALID_COORDS'
    ]);
    exit;
}

// afstand berekenen
$km = haversine_km(
    $origin['lat'],
    $origin['lng'],
    $destination['lat'],
    $destination['lng']
);

echo json_encode([
    'ok' => true,
    'km' => $km
]);
