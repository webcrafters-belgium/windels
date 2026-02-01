<?php
// Lightweight JSON product list for admin React UI
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require_once __DIR__ . '/parent_column.php';
header('Content-Type: application/json');

$includeParent = hasProductParentColumn($conn);
$parentSelect = $includeParent ? ', p.parent_id' : '';

$result = $conn->query("
    SELECT p.id, p.name, p.sku, p.price, p.stock_status, p.stock_quantity, p.product_type, p.weight_grams{$parentSelect}
    FROM products p
    ORDER BY p.updated_at DESC
    LIMIT 500
");

$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $product = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'sku' => $row['sku'],
            'price' => (float)$row['price'],
            'stock_status' => $row['stock_status'] ?? null,
            'stock' => isset($row['stock_quantity']) ? (int)$row['stock_quantity'] : null,
            'product_type' => $row['product_type'] ?? null,
            'weight_grams' => isset($row['weight_grams']) ? (int)$row['weight_grams'] : null,
        ];

        if ($includeParent) {
            $product['parent_id'] = isset($row['parent_id']) ? (int)$row['parent_id'] : null;
        }

        $products[] = $product;
    }
}

echo json_encode($products);
