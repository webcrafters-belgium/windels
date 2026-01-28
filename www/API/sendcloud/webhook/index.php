<?php

$sendcloud_public_key = "2d47dcd8-0fe1-486f-9707-4720171409f4";
$sendcloud_private_key = "749e5696dd634e88be137932ad316205";


// /API/sendcloud/webhook/index.php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// 🔒 (optioneel) check op Sendcloud IP / Signature hier

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['id'], $data['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Ongeldige payload']);
    exit;
}

// Voorbeelddata:
$shipmentId = $data['id'];
$status = $data['status']; // bv. 'delivered', 'cancelled', etc.

// 🎯 Update bv. orders-tabel op basis van metadata
// Eerst de order_id ophalen als je dat hebt opgeslagen in metadata
$orderId = $data['order_number'] ?? null; // Alleen beschikbaar als je 'order_number' meegaf

if ($orderId) {
    $stmt = $conn->prepare("UPDATE orders SET shipping_status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("si", $status, $orderId);
    $stmt->execute();
    $stmt->close();
}

// Log eventueel voor debug
file_put_contents('/tmp/sendcloud_webhook.log', date('Y-m-d H:i:s') . " - $shipmentId => $status\n", FILE_APPEND);

echo json_encode(['success' => true]);
