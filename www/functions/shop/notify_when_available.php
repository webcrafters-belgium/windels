<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// JSON input uitlezen
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ongeldig e-mailadres']);
    exit;
}

if (!isset($data['product_id']) || !is_numeric($data['product_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ongeldig product-ID']);
    exit;
}

$email = strtolower(trim($data['email']));
$product_id = (int) $data['product_id'];

// Controleren of het e-mailadres al is ingeschreven voor dit product
$stmt = $conn->prepare("SELECT id FROM product_subscribers WHERE email = ? AND product_id = ?");
$stmt->bind_param('si', $email, $product_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Je staat al op de lijst voor dit product.']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Inschrijving opslaan
$stmt = $conn->prepare("INSERT INTO product_subscribers (email, product_id, created_at) VALUES (?, ?, NOW())");
$stmt->bind_param('si', $email, $product_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Je wordt verwittigd zodra dit product opnieuw beschikbaar is.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Er ging iets mis bij het opslaan.']);
}

$stmt->close();
$conn->close();
