<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// ✅ Alleen admins toelaten
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /pages/account/login?referer=/admin/orders/");
    exit;
}

$order_id = intval($_POST['order_id'] ?? 0);

if ($order_id > 0) {
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    if ($stmt->execute()) {
        $_SESSION['order_message'] = "Bestelling #{$order_id} is verwijderd.";
    } else {
        $_SESSION['order_message'] = "Fout bij verwijderen van bestelling #{$order_id}.";
    }
    $stmt->close();
}

header("Location: /admin/orders/index.php");
exit;
?>
