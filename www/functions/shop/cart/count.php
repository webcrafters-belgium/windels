<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

header('Content-Type: application/json');

$session_id = session_id();

$stmt = $conn->prepare("
    SELECT SUM(quantity) AS total_qty, SUM(quantity * price) AS total_price
    FROM cart_items
    WHERE session_id = ?
");
$stmt->bind_param('s', $session_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

$totalQty   = (int)($result['total_qty'] ?? 0);
$totalPrice = (float)($result['total_price'] ?? 0);

echo json_encode([
    'success' => true,
    'count'   => $totalQty,
    'total'   => $totalPrice
]);