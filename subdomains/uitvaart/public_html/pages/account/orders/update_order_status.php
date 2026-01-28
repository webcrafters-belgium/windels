<?php
session_start();
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/login.php");
    exit;
}

$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$new_status = $_POST['status'] ?? '';

$allowed = ['geannuleerd', 'as_verzonden'];
if ($order_id <= 0 || !in_array($new_status, $allowed)) {
    die("Ongeldige status.");
}

// Controleer of de order nog in verwerking is
$stmt = $mysqli->prepare("SELECT status FROM orders WHERE id=? AND funeral_partner_id=?");
$stmt->bind_param('ii', $order_id, $_SESSION['partner_id']);
$stmt->execute();
$stmt->bind_result($current_status);
$stmt->fetch();
$stmt->close();

if ($current_status !== 'in_verwerking') {
    die("Deze bestelling kan niet meer worden aangepast.");
}

// Status bijwerken
$stmt = $mysqli->prepare("UPDATE orders SET status=? WHERE id=? AND funeral_partner_id=?");
$stmt->bind_param('sii', $new_status, $order_id, $_SESSION['partner_id']);
$stmt->execute();
$stmt->close();

header("Location: view_order.php?id=".$order_id);
exit;
