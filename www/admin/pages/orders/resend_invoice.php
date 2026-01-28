<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/functions/mail/factuur_mail.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/fpdf/fpdf.php';

if (!isset($_POST['order_id']) || !is_numeric($_POST['order_id'])) {
    die('Geen geldig order-ID opgegeven.');
}

$order_id = (int)$_POST['order_id'];

// Ophalen van ordergegevens
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die('Order niet gevonden.');
}
$order = $result->fetch_assoc();

// Ophalen van order items en opbouw van factuur
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/mail/factuur_mail.php';
if (verzendFactuurEmail($order)) {
    $conn->query("UPDATE orders SET invoice_sent = 1 WHERE id = $order_id");
}

header("Location: detail.php?id=$order_id");
exit;
