<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $session_id = session_id();
    $priceInput = $_POST['price'] ?? null;

    // ✅ Product ophalen uit DB
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        echo json_encode(["success" => false, "message" => "Product niet gevonden."]);
        exit;
    }

    $dbPrice = (float)$product['price'];

    // ✅ Bepaal prijs: gebruik meegegeven kortingsprijs als die geldig is
    if ($priceInput !== null) {
        $price = (float)str_replace(',', '.', $priceInput);
        // Beveiliging: kortingsprijs moet >0 en <= originele DB-prijs
        if ($price <= 0 || $price > $dbPrice) {
            $price = $dbPrice;
        }
    } else {
        $price = $dbPrice;
    }

    // ✅ Bestaat product al in cart_items?
    $stmt = $conn->prepare("SELECT quantity FROM cart_items WHERE session_id = ? AND product_id = ? LIMIT 1");
    $stmt->bind_param('si', $session_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_item = $result->fetch_assoc();
    $stmt->close();

    if ($cart_item) {
        // ✅ Verhoog aantal
        $stmt = $conn->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE session_id = ? AND product_id = ?");
        $stmt->bind_param('si', $session_id, $product_id);
    } else {
        // ✅ Nieuw product toevoegen
        $stmt = $conn->prepare("INSERT INTO cart_items (session_id, product_id, price, quantity) VALUES (?, ?, ?, 1)");
        $stmt->bind_param('sid', $session_id, $product_id, $price);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Kon product niet toevoegen aan winkelmandje."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Ongeldig verzoek."]);
}
