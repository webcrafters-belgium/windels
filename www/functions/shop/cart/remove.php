<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $session_id = session_id();

    // ✅ Check of item in cart staat
    $stmt = $conn->prepare("SELECT quantity FROM cart_items WHERE session_id = ? AND product_id = ? LIMIT 1");
    $stmt->bind_param('si', $session_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_item = $result->fetch_assoc();
    $stmt->close();

    if (!$cart_item) {
        echo json_encode(["success" => false, "message" => "Product niet gevonden in winkelwagen."]);
        exit;
    }

    // ✅ Aantal verminderen of volledig verwijderen
    if ($cart_item['quantity'] > 1) {
        $stmt = $conn->prepare("UPDATE cart_items SET quantity = quantity - 1 WHERE session_id = ? AND product_id = ?");
        $stmt->bind_param('si', $session_id, $product_id);
    } else {
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE session_id = ? AND product_id = ?");
        $stmt->bind_param('si', $session_id, $product_id);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Kon product niet verwijderen."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Ongeldig verzoek."]);
}
