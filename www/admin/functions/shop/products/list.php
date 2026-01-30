<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
header('Content-Type: application/json');

$result = $conn->query("
    SELECT p.id, p.name, p.sku, p.price, p.stock
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
            'stock' => isset($row['stock']) ? (int)$row['stock'] : null,
        ];
    }
}

echo json_encode($products);
