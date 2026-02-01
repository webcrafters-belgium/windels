<?php
// Lightweight JSON product list for admin UI
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
header('Content-Type: application/json');

$result = $conn->query("
    SELECT p.id, p.name, p.sku, p.price, p.stock_status, p.stock_quantity, p.product_type, p.weight_grams, p.parent_id
    FROM products p
    ORDER BY p.updated_at DESC
    LIMIT 500
");

$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'sku' => $row['sku'],
            'price' => (float)$row['price'],
            'stock_status' => $row['stock_status'] ?? null,
            'stock' => isset($row['stock_quantity']) ? (int)$row['stock_quantity'] : null,
            'product_type' => $row['product_type'] ?? null,
            'weight_grams' => isset($row['weight_grams']) ? (int)$row['weight_grams'] : null,
            'parent_id' => isset($row['parent_id']) ? (int)$row['parent_id'] : null,
        ];
    }
}

echo json_encode($products);
