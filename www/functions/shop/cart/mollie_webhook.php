<?php
declare(strict_types=1);
session_start();
ini_set('display_errors','1'); error_reporting(E_ALL);

require $_SERVER['DOCUMENT_ROOT'].'/ini.inc';
require $_SERVER['DOCUMENT_ROOT'].'/lib/mollie/vendor/autoload.php';

use Mollie\Api\MollieApiClient;

// ───────────────── Beveiliging ─────────────────
if (!isset($_GET['key']) || $_GET['key'] !== $mollie_webhook_key) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

// Mollie POST payload moet een payment id bevatten
$paymentId = $_POST['id'] ?? null;
if (!$paymentId) {
    http_response_code(400);
    echo 'Missing id';
    exit;
}

// ───────────────── Mollie ophalen ─────────────────
$mollie = new MollieApiClient();
$mollie->setApiKey($mollie_key);

try {
    $payment = $mollie->payments->get($paymentId);
} catch (Throwable $e) {
    error_log('Mollie fetch failed: '.$e->getMessage());
    http_response_code(500);
    echo 'Payment fetch failed';
    exit;
}

$status = (string)$payment->status;
$meta   = (array)($payment->metadata ?? []);
$order_id = (int)($meta['order_id'] ?? 0);

if ($order_id <= 0) {
    http_response_code(400);
    echo 'No order_id in metadata';
    exit;
}

// ───────────────── Status updaten (idempotent) ─────────────────
$stmt = $conn->prepare("UPDATE orders 
    SET mollie_id = ?, status = ?, 
        paid_at = CASE WHEN ? IN ('paid','authorized') AND paid_at IS NULL THEN NOW() ELSE paid_at END
    WHERE id = ? LIMIT 1");
$stmt->bind_param('sssi', $paymentId, $status, $status, $order_id);
$stmt->execute();
$stmt->close();

// ───────────────── Succesflow triggeren ─────────────────
if ($status === 'paid' || $status === 'authorized') {
    try {
        on_order_paid($conn, $order_id);
    } catch (Throwable $e) {
        // Niet falen naar Mollie toe; log en geef 200 terug zodat Mollie niet blijft retrien
        error_log('[on_order_paid] order '.$order_id.' error: '.$e->getMessage());
    }
}

http_response_code(200);
echo 'OK';