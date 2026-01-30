<?php
// JSON delete endpoint for products
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
header('Content-Type: application/json');

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if (!$id) {
    echo json_encode(['success' => false, 'error' => 'id_missing']);
    exit;
}

// Delete related data first
$conn->query("DELETE FROM product_images WHERE product_id = $id");
$conn->query("DELETE FROM product_categories WHERE product_id = $id");
$conn->query("DELETE FROM product_subcategories WHERE product_id = $id");

// Delete product
$conn->query("DELETE FROM products WHERE id = $id");

echo json_encode(['success' => true]);
