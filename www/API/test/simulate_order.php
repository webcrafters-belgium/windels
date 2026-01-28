<?php
// FILE: /API/test/simulate_order.php
// Doel: 1) testorder maken in DB, 2) MyParcel shipment aanmaken, 3) shipment_id in DB opslaan

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
error_reporting(E_ALL);
ini_set('display_errors', '1');

function respond(int $code, array $data) {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}

function getJsonInput(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?? '', true);
    return is_array($data) ? $data : ($_POST ?: []);
}

$in = getJsonInput();

// 1. Verplichte velden check
$required = ['country','postcode','city','street','number','name','email','weight_g'];
$missing  = [];
foreach ($required as $k) {
    if (empty($in[$k])) $missing[] = $k;
}
if ($missing) respond(400, ['success' => false, 'error' => 'Ontbrekende velden', 'fields' => $missing]);

$country  = strtoupper(trim((string)$in['country']));
$postcode = trim((string)$in['postcode']);
$city     = trim((string)$in['city']);
$street   = trim((string)$in['street']);
$number   = trim((string)$in['number']);
$name     = trim((string)$in['name']);
$email    = trim((string)$in['email']);
$phone    = trim((string)($in['phone'] ?? ''));
$weightG  = max(1, (int)$in['weight_g']);

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$session        = session_id();
$shippingMethod = 'DPD';
$paymentMethod  = 'test';
$status         = 'paid';
$adminNotes     = 'Simulated order via /API/test/simulate_order.php';

// 2. Verzendkosten ophalen indien niet meegegeven
$shippingCost = isset($in['shipping_cost']) ? (float)$in['shipping_cost'] : null;
if ($shippingCost === null) {
    $qs = http_build_query([
        'country'=>$country,'postcode'=>$postcode,'city'=>$city,'street'=>$street,'number'=>$number,
        'name'=>$name,'email'=>$email,'phone'=>$phone,'weight_g'=>$weightG
    ], '', '&', PHP_QUERY_RFC3986);

    $calcUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on' ? 'https' : 'http')
        . '://' . $_SERVER['HTTP_HOST'] . '/API/myparcel/calculate_shipping.php?' . $qs;

    $calcResp = file_get_contents($calcUrl);
    if ($calcResp === false) {
        respond(502, ['success'=>false, 'error'=>'Calculator niet bereikbaar']);
    }
    $calcData = json_decode($calcResp, true);
    if (!is_array($calcData) || !empty($calcData['error'])) {
        respond(400, ['success'=>false, 'error'=>'Verzendkosten ophalen mislukt', 'raw'=>$calcData ?: $calcResp]);
    }
    $shippingCost = (float)$calcData['shipping_cost'];
}

$totalPrice = round(0.01 + $shippingCost, 2); // test product van €0,01

// 3. Order in DB opslaan
try {
    $sql = "INSERT INTO orders 
        (user_id, session_id, name, email, street, number, country, zipcode, city, phone,
         shipping_cost, shipping_method, payment_method, total_price, status, admin_notes)
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception("MySQL prepare error: " . $conn->error);

    $userId = 0;
    $stmt->bind_param(
        "isssssssssssdsss",
        $userId,
        $session,
        $name,
        $email,
        $street,
        $number,
        $country,
        $postcode,
        $city,
        $phone,
        $shippingCost,
        $shippingMethod,
        $paymentMethod,
        $totalPrice,
        $status,
        $adminNotes
    );

    if (!$stmt->execute()) {
        throw new Exception("MySQL execute error: " . $stmt->error);
    }
    $orderId = (int)$stmt->insert_id;
    $stmt->close();
} catch (Throwable $e) {
    respond(500, ['success'=>false, 'error'=>'DB-fout bij aanmaken order', 'detail'=>$e->getMessage()]);
}

// 4. Payload naar MyParcel (volgens JSON-schema)
$payload = [
    'recipient' => [
        'cc'          => $country,
        'postal_code' => $postcode,
        'city'        => $city,
        'street'      => $street,
        'number'      => $number,
        'person'      => $name,
        'email'       => $email,
        'phone'       => $phone,
    ],
    'options' => [
        'package_type' => 1
    ],
    'physical_properties' => [
        'weight' => intval($weightG)
    ],
    'carrier'              => 4,
    'reference_identifier' => 'ORDER-' . $orderId
];

// 5. Call naar create_shipment.php
$createUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on' ? 'https':'http')
    . '://' . $_SERVER['HTTP_HOST'] . '/API/myparcel/create_shipment.php';

$ch = curl_init($createUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json; charset=utf-8'],
    CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES),
    CURLOPT_TIMEOUT        => 30
]);
$resp = curl_exec($ch);
$http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
$cerr = curl_errno($ch) ? curl_error($ch) : null;
curl_close($ch);

if ($cerr) {
    respond(502, ['success'=>false, 'error'=>'Curl-fout bij MyParcel', 'detail'=>$cerr, 'order_id'=>$orderId]);
}

$data = json_decode((string)$resp, true);

// 6. Valideer MyParcel response
if ($http<200 || $http>=300 || !is_array($data) || empty($data['ok'])) {
    respond($http ?: 500, [
        'success'   => false,
        'error'     => $data['error'] ?? 'MyParcel aanmaak mislukt',
        'http'      => $http,
        'raw'       => $data ?: $resp,
        'order_id'  => $orderId
    ]);
}

$shipmentId = (int)($data['shipment_id'] ?? 0);

// 7. shipment_id in DB opslaan
if ($shipmentId > 0) {
    try {
        $up = $conn->prepare("UPDATE orders SET shipment_id = ? WHERE id = ?");
        if ($up) {
            $up->bind_param("ii", $shipmentId, $orderId);
            $up->execute();
            $up->close();
        }
    } catch (Throwable $e) { /* Niet fataal */ }
}

// 8. Terug naar frontend
respond(200, [
    'success'        => true,
    'order_id'       => $orderId,
    'shipping_cost'  => (float)$shippingCost,
    'total_price'    => (float)$totalPrice,
    'shipment_id'    => $shipmentId ?: null,
    'label_url'      => $shipmentId ? (
        (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on' ? 'https' : 'http')
        . '://' . $_SERVER['HTTP_HOST'] . '/API/myparcel/download_label.php?id=' . $shipmentId
    ) : null
]);
