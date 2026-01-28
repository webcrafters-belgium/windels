<?php

header('Content-Type: application/json');

require $_SERVER['DOCUMENT_ROOT'].'/ini.inc'; // Verwijzing naar config.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($conn)) {
    die('Databaseverbinding is niet ingesteld.');
}

// Functie om products op te halen op basis van SKU of categorie
function fetchProducts($conn, $query, $param, $param_type) {
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die('Fout bij voorbereiden van query: ' . $conn->error);
    }

    $stmt->bind_param($param_type, $param);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

if (isset($_GET['sku'])) {
    $sku = $_GET['sku'];
    $query = "
        SELECT sku, title, total_product_price
        FROM (
            SELECT sku, title, total_product_price 
            FROM epoxy_products
            UNION ALL
            SELECT sku, title, total_product_price 
            FROM kaarsen_products
            UNION ALL
            SELECT sku, title, total_product_price 
            FROM vers_products
        ) AS combined
        WHERE sku COLLATE utf8mb4_general_ci LIKE ?
    ";

    $sku = "%" . $sku . "%"; // Voeg wildcards toe aan SKU voor LIKE-matching
    $products = fetchProducts($conn, $query, $sku, 's');
    echo json_encode($products);
}

if (isset($_GET['category'])) {
    $category = $_GET['category'];
    $query = "
        SELECT sku, title, total_product_price
        FROM (
            SELECT sku, title, total_product_price, 'epoxy' AS category 
            FROM epoxy_products
            UNION ALL
            SELECT sku, title, total_product_price, 'kaars' AS category 
            FROM kaarsen_products
            UNION ALL
            SELECT sku, title, total_product_price, 'vers' AS category 
            FROM vers_products
        ) AS combined
        WHERE category COLLATE utf8mb4_general_ci = ?
    ";

    $products = fetchProducts($conn, $query, $category, 's');
    echo json_encode($products);
}

