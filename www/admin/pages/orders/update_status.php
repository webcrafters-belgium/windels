<?php

include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

if (!isset($_POST['order_id']) || !isset($_POST['status'])) {
    die('Ongeldige invoer.');
}

$order_id = (int)$_POST['order_id'];
$status = $_POST['status'];
$toegestane_statussen = ['pending', 'paid', 'shipped', 'cancelled'];

if (!in_array($status, $toegestane_statussen)) {
    die('Ongeldige status.');
}

$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $order_id);
$stmt->execute();

header("Location: detail.php?id=$order_id");
exit;