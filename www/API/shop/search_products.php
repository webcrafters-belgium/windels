<?php

header('Content-Type: application/json');
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($q == '') {
    echo json_encode([]);
    exit;
}

// ---- Zoekopdracht opslaan ----
$insert = $conn->prepare("INSERT INTO search_queries (query, search_date) VALUES (?, NOW())");
$insert->bind_param("s", $q);
$insert->execute();
$insert->close();

// ---- Producten zoeken ----
$stmt = $conn->prepare("
    SELECT p.id, p.name, p.price, p.sku, i.image_path, i.webp_path
    FROM products p 
    LEFT JOIN product_images i ON p.id = i.product_id 
    WHERE p.name LIKE CONCAT('%', ?, '%')
    GROUP BY p.id
    LIMIT 10
");
$stmt->bind_param("s", $q);
$stmt->execute();
$stmt->bind_result($id, $name, $price, $sku, $image, $webp);

$results = [];
while ($stmt->fetch()) {
    $results[] = [
        'id' => $id,
        'name' => $name,
        'price' => number_format($price, 2, ',', '.'),
        'sku' => $sku,
        'image' => $image ? $image : "/images/placeholder.png",
        'webp'  => $webp ?: null
    ];
}
$stmt->close();

echo json_encode($results);
