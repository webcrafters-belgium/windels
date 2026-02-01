<?php

header('Content-Type: application/json');

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc'; // Verwijzing naar config.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Controleer dat de connectie bestaat
if (!isset($conn)) {
    die('Databaseverbinding is niet ingesteld.');
}

// ---------- SKU ZOEKEN ----------
if (isset($_GET['sku'])) {
    $sku = "%" . $_GET['sku'] . "%";

    $stmt = $conn->prepare("
        SELECT sku, name, price AS total_product_price
        FROM products
        WHERE sku LIKE ?
    ");
    $stmt->bind_param('s', $sku);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($products);
    exit;
}

// ---------- CATEGORIE ZOEKEN ----------
if (isset($_GET['category'])) {
    $categorySlug = $_GET['category'];

    // Zoek category_id
    $stmt = $conn->prepare("SELECT id FROM categories WHERE slug = ?");
    $stmt->bind_param('s', $categorySlug);
    $stmt->execute();
    $stmt->bind_result($categoryId);
    $stmt->fetch();
    $stmt->close();

    if (!$categoryId) {
        echo json_encode([]);
        exit;
    }

    $stmt = $conn->prepare("
        SELECT p.sku, p.name, p.price AS total_product_price
        FROM products p
        JOIN product_categories pc ON p.id = pc.product_id
        WHERE pc.category_id = ?
    ");
    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($products);
    exit;
}

?>
