<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

header('Content-Type: application/json');

$sku = $_GET['sku'] ?? null;
$category = $_GET['category'] ?? null;

if ($sku) {
    $stmt = $conn->prepare("SELECT id, sku, name, price FROM products WHERE sku = ? LIMIT 1");
    $stmt->bind_param("s", $sku);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Product niet gevonden']);
    }
    exit;
}

if ($category) {
    // Haal category ID op indien nodig (optioneel via slug/naam)
    $stmt = $conn->prepare("SELECT id FROM product_categories WHERE slug = ? OR name = ? LIMIT 1");
    $stmt->bind_param("ss", $category, $category);
    $stmt->execute();
    $catResult = $stmt->get_result();
    if ($catRow = $catResult->fetch_assoc()) {
        $categoryId = $catRow['id'];

        $stmt = $conn->prepare("SELECT p.id, p.sku, p.name, p.price FROM products p
                                JOIN product_categories pc ON pc.id = ?
                                JOIN product_categories_products pcp ON p.id = pcp.product_id AND pcp.category_id = pc.id");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products);
    } else {
        echo json_encode([]);
    }
    exit;
}

echo json_encode(['error' => 'Geen SKU of categorie opgegeven']);
