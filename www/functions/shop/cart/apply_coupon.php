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

$code = strtoupper($code_in);
$today = date('Y-m-d');

// Alleen op code zoeken; kolommen verschillen tussen oude/nieuwe couponstructuren.
$stmt = $conn->prepare("SELECT * FROM coupons WHERE UPPER(code) = ? LIMIT 1");
$stmt->bind_param('s', $code);
$stmt->execute();
$res = $stmt->get_result();
$coupon = $res->fetch_assoc();
$stmt->close();

if (!$coupon) {
    unset($_SESSION['applied_coupon']);
    echo json_encode(['success' => false, 'error' => 'Ongeldige of verlopen kortingscode.']);
    exit;
}

// Status/validiteit op basis van aanwezige kolommen.
if (array_key_exists('is_active', $coupon) && (int)$coupon['is_active'] !== 1) {
    unset($_SESSION['applied_coupon']);
    echo json_encode(['success' => false, 'error' => 'Deze kortingscode is niet actief.']);
    exit;
}

$validFrom = $coupon['valid_from'] ?? null;
$validUntil = $coupon['valid_until'] ?? null;
$expiresAt = $coupon['expires_at'] ?? null;

if (!empty($validFrom) && $today < substr((string)$validFrom, 0, 10)) {
    unset($_SESSION['applied_coupon']);
    echo json_encode(['success' => false, 'error' => 'Deze kortingscode is nog niet geldig.']);
    exit;
}
if (!empty($validUntil) && $today > substr((string)$validUntil, 0, 10)) {
    unset($_SESSION['applied_coupon']);
    echo json_encode(['success' => false, 'error' => 'Deze kortingscode is verlopen.']);
    exit;
}
if (!empty($expiresAt) && $today > substr((string)$expiresAt, 0, 10)) {
    unset($_SESSION['applied_coupon']);
    echo json_encode(['success' => false, 'error' => 'Deze kortingscode is verlopen.']);
    exit;
}

// Subtotaal berekenen.
$session_id = session_id();
$subtotal = 0.0;
$stmt = $conn->prepare('SELECT price, quantity FROM cart_items WHERE session_id = ?');
$stmt->bind_param('s', $session_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $subtotal += ((float)$row['price']) * ((int)$row['quantity']);
}
$stmt->close();

// Type en waarde normaliseren.
$rawDiscountValue = $coupon['value'] ?? $coupon['discount'] ?? 0;
$discountValue = (float)$rawDiscountValue;
$rawType = strtolower((string)($coupon['type'] ?? $coupon['discount_type'] ?? 'percent'));
$discountType = in_array($rawType, ['percent', 'percentage'], true) ? 'percent' : 'amount';

$discountAmount = 0.0;
if ($discountType === 'amount') {
    $discountAmount = min($discountValue, $subtotal);
} else {
    $discountAmount = $subtotal * ($discountValue / 100);
}

$newCartTotal = max(0, $subtotal - $discountAmount);

$_SESSION['applied_coupon'] = [
    'code' => (string)$coupon['code'],
    'discount' => $discountValue,
    'discount_type' => $discountType,
];

echo json_encode([
    'success' => true,
    'code' => (string)$coupon['code'],
    'discount_type' => $discountType,
    'discount_value' => $discountValue,
    'subtotal' => round($subtotal, 2),
    'discount_amount' => round($discountAmount, 2),
    'new_cart_total' => round($newCartTotal, 2),
]);
