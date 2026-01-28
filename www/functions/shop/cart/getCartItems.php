<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

header('Content-Type: application/json');

$session_id = session_id();

$sql = "
    SELECT 
        ci.product_id,
        ci.quantity,
        ci.price,
        p.name,
        p.sku,
        (
            SELECT pi.webp_path 
            FROM product_images pi 
            WHERE pi.sku = p.sku AND pi.is_main = 1 
            LIMIT 1
        ) AS product_image
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.session_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $session_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
$total = 0;
$count = 0;

while ($row = $result->fetch_assoc()) {
    $price    = (float)$row['price'];
    $quantity = (int)$row['quantity'];
    $subtotal = $price * $quantity;

    $items[] = [
        'id'       => (int)$row['product_id'],
        'sku'      => $row['sku'],
        'name'     => $row['name'],
        'image'    => $row['product_image'] ?: '/images/no-image.png',
        'price'    => $price,
        'quantity' => $quantity,
        'subtotal' => $subtotal,
    ];

    $total += $subtotal;
    $count += $quantity;
}

echo json_encode([
    'success' => true,
    'items'   => $items,
    'total'   => $total,
    'count'   => $count
]);

$stmt->close();
$conn->close();