<?php
// Bestand: /admin/pages/test-mail/index.php

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require $_SERVER['DOCUMENT_ROOT'] . '/functions/mail/customer_mail.php';
require $_SERVER['DOCUMENT_ROOT'] . '/functions/mail/admin_mail.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/pages/orders/pdf_invoice.php';

$orderId = $_GET['order_id'] ?? null;
if (!$orderId || !is_numeric($orderId)) {
    die("Geef een geldig order_id mee in de URL, bv. ?order_id=123");
}

// ────────── ORDER ophalen ──────────
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) die("Bestelling niet gevonden.");

// ────────── ITEMS ophalen ──────────
$stmt = $conn->prepare("
    SELECT oi.*, p.name AS product_name
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ────────── Factuur genereren & opslaan ──────────
$tempDir = $_SERVER['DOCUMENT_ROOT'] . '/temp';
if (!is_dir($tempDir)) mkdir($tempDir, 0775, true);

$pdfPath = $tempDir . "/factuur_{$orderId}.pdf";
$pdfOutput = generateInvoicePDF($order, $items, false); // geeft PDF-string terug
file_put_contents($pdfPath, $pdfOutput);

echo "<pre>✅ Factuur gegenereerd: {$pdfPath}\n";

try {
    // ────────── KLANTMAIL ──────────
    sendConfirmationEmail(
        (int)$orderId,
        (float)$order['total_price'],
        (float)$order['shipping_cost'],
        $order['email'],
        $pdfPath
    );
    echo "✅ Klantmail verzonden.\n";

    // ────────── ADMINMAIL ──────────
    sendAdminMail(
        (int)$orderId,
        (float)$order['total_price'],
        (float)$order['shipping_cost'],
        $pdfPath
    );
    echo "✅ Adminmail verzonden.\n";

    // ────────── Factuur opruimen ──────────
    unlink($pdfPath);
    echo "🧹 Tijdelijke factuur verwijderd.\n</pre>";

} catch (Exception $e) {
    echo "❌ Fout bij verzenden: " . $e->getMessage();
}
