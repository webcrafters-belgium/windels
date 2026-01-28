<?php
// FILE: /API/shipping/calculate_shipping.php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
header('Content-Type: application/json; charset=utf-8');

global $easyship_api_key;
$apiKey = (string)($easyship_api_key ?? '');
if ($apiKey === '') {
    echo json_encode(['success' => false, 'error' => 'Geen Easyship API key gevonden']);
    exit;
}

function get_request_input(): array {
    $ctype = strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '', 2)[0]));
    if ($ctype === 'application/json') {
        $raw = file_get_contents('php://input');
        $j = $raw ? json_decode($raw, true) : null;
        return is_array($j) ? $j : [];
    }
    if (!empty($_POST)) return $_POST;
    return $_GET ?? [];
}

$in = get_request_input();

// ✅ Flexibele input
$city     = trim((string)($in['city'] ?? ''));
$country  = strtoupper(trim((string)($in['country'] ?? '')));
$email    = trim((string)($in['email'] ?? ''));
$name     = trim((string)($in['name'] ?? ''));
$number   = trim((string)($in['number'] ?? ''));
$phone    = trim((string)($in['phone'] ?? ''));
$street   = trim((string)($in['street'] ?? $in['address'] ?? ''));
$zipcode  = trim((string)(
    $in['zipcode'] ?? $in['postcode'] ?? $in['postal_code'] ?? $in['zip'] ?? ''
));
$weight_g  = (int)($in['weight_g'] ?? 1000);
$weight_kg = max(0.01, round($weight_g / 1000, 3));

if ($city === '' || $country === '' || $street === '' || $zipcode === '') {
    echo json_encode([
        'success' => false,
        'error'   => 'Onvolledige adresgegevens',
        'received'=> $in
    ]);
    exit;
}

// ✅ Herkomstadres
$origin_address = [
    'line_1'         => 'Beukenlaan 8',
    'city'           => 'Hamont-Achel',
    'state'          => 'Limburg',
    'postal_code'    => '3930',
    'country_alpha2' => 'BE',
    'company_name'   => 'Windels Green Deco Resin',
    'contact_name'   => 'A. Windels', // <= 22 tekens
    'contact_phone'  => '+3211753319',
    'contact_email'  => 'info@windelsgreen-decoresin.com'
];

// ✅ Request body
$body = [
    'origin_address'      => $origin_address,
    'destination_address' => [
        'line_1'         => trim($street . ' ' . $number),
        'city'           => $city,
        'postal_code'    => $zipcode,
        'country_alpha2' => $country,
        'contact_name'   => $name,
        'contact_phone'  => $phone,
        'contact_email'  => $email,
    ],
    'parcels' => [[
        'total_actual_weight' => $weight_kg,
        'box' => ['length' => 20, 'width' => 20, 'height' => 10],
        'items' => [[
            'description'           => 'Webshop order',
            'category'              => 'General',
            'sku'                   => 'SKU-WINDELS',
            'origin_country_alpha2' => 'BE',
            'quantity'              => 1,
            'actual_weight'         => $weight_kg,
            'declared_currency'     => 'EUR',
            'declared_customs_value'=> 20,
            'hs_code'               => '392690'
        ]]
    ]]
];

// ✅ cURL call naar Easyship
$ch = curl_init('https://public-api.easyship.com/2024-09/rates');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: ' . 'Bearer ' . $apiKey,
    ],
    CURLOPT_POSTFIELDS     => json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    CURLOPT_TIMEOUT        => 30,
]);
$response = curl_exec($ch);
$err      = curl_error($ch);
curl_close($ch);

if ($err) {
    echo json_encode(['success' => false, 'error' => 'cURL fout', 'detail' => $err]);
    exit;
}

$data = json_decode($response, true);
if (!is_array($data) || !isset($data['rates'])) {
    echo json_encode([
        'success' => false,
        'error'   => $data['error']['message'] ?? 'Ongeldige response van Easyship',
        'detail'  => $data,
        'raw'     => $response
    ]);
    exit;
}

// ✅ Filter alleen DPD
$rates = [];
foreach ($data['rates'] as $rate) {
    $courier = $rate['courier_service']['umbrella_name'] ?? '';
    $service = $rate['courier_service']['name'] ?? '';

    if (stripos($courier, 'DPD') === false && stripos($service, 'DPD') === false) {
        continue;
    }

    $rates[] = [
        'courier'  => $courier,
        'service'  => $service,
        'currency' => $rate['currency'] ?? 'EUR',
        'price'    => (float)($rate['total_charge'] ?? 0),
        'eta'      => $rate['full_description'] ?? '',
        'min_days' => $rate['min_delivery_time'] ?? null,
        'max_days' => $rate['max_delivery_time'] ?? null
    ];
}
echo json_encode([
    'success' => true,
    'rates'   => $data['rates']
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
