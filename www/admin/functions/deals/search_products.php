<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

header('Content-Type: application/json');

// SKU-filter
$sku = isset($_GET['sku']) ? $conn->real_escape_string($_GET['sku']) : '';

$sql = "SELECT id, name, sku FROM products ";
if ($sku) {
    $sql .= "WHERE sku LIKE '%$sku%' ";
}
$sql .= "ORDER BY name ASC LIMIT 50";

$result = $conn->query($sql);
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);
$conn->close();
