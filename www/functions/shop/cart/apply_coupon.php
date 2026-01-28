<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '1');

$code_in = trim($_POST['coupon_code'] ?? '');
if ($code_in === '') {
    echo json_encode(['success' => false, 'error' => 'Geen kortingscode opgegeven.']);
    exit;
}

$code  = strtoupper($code_in);
$today = date('Y-m-d');

// 🔹 Alleen zoeken in coupons
$stmt = $conn->prepare("
    SELECT code, discount, discount_type
    FROM coupons
    WHERE UPPER(code) = ?
      AND valid_from <= ?
      AND valid_until >= ?
    LIMIT 1
");
$stmt->bind_param('sss', $code, $today, $today);
$stmt->execute();
$res = $stmt->get_result();
$coupon = $res->fetch_assoc();
$stmt->close();

if (!$coupon) {
    unset($_SESSION['applied_coupon']);
    echo json_encode(['success' => false, 'error' => 'Ongeldige of verlopen kortingscode.']);
    exit;
}

// 🔹 Subtotaal berekenen
$session_id = session_id();
$subtotal = 0.0;
$stmt = $conn->prepare("SELECT price, quantity FROM cart_items WHERE session_id = ?");
$stmt->bind_param("s", $session_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $subtotal += ((float)$row['price']) * ((int)$row['quantity']);
}
$stmt->close();

// 🔹 Korting berekenen
$discountValue = (float)$coupon['discount'];
$discountType  = strtolower($coupon['discount_type'] ?? 'percent'); // 'percent' of 'amount'
$discountAmount = 0.0;

if ($discountType === 'amount') {
    $discountAmount = min($discountValue, $subtotal); // max tot subtotal
} else { // percent
    $discountAmount = $subtotal * ($discountValue / 100);
}

// 🔹 Nieuwe totaalprijs
$newCartTotal = max(0, $subtotal - $discountAmount);

// 🔹 In sessie opslaan
$_SESSION['applied_coupon'] = [
    'code'          => $coupon['code'],
    'discount'      => $discountValue,
    'discount_type' => $discountType
];

// 🔹 JSON response
echo json_encode([
    'success'         => true,
    'code'            => $coupon['code'],
    'discount_type'   => $discountType,
    'discount_value'  => $discountValue,
    'subtotal'        => round($subtotal, 2),
    'discount_amount' => round($discountAmount, 2),
    'new_cart_total'  => round($newCartTotal, 2)
]);
