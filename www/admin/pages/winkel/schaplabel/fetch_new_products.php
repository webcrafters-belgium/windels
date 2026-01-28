<?php
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ophalen van filters
$interval = isset($_POST['interval']) ? (int)$_POST['interval'] : null;
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : null;
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : null;
$categoryId = isset($_POST['categoryId']) ? (int)$_POST['categoryId'] : null;

// WHERE clausules opbouwen
$whereClauses = [];
$params = [];
$types = '';

// Datumfilter
if ($startDate && $endDate) {
    $whereClauses[] = 'p.created_at BETWEEN ? AND ?';
    $params[] = $startDate . ' 00:00:00';
    $params[] = $endDate . ' 23:59:59';
    $types .= 'ss';
} elseif ($interval) {
    $whereClauses[] = 'p.created_at >= NOW() - INTERVAL ? DAY';
    $params[] = $interval;
    $types .= 'i';
} else {
    // Standaard laatste 15 dagen
    $whereClauses[] = 'p.created_at >= NOW() - INTERVAL 15 DAY';
}

// Categorie-filter
if ($categoryId) {
    $whereClauses[] = 'pc.category_id = ?';
    $params[] = $categoryId;
    $types .= 'i';
}

$whereSql = implode(' AND ', $whereClauses);

// Query bouwen
$query = "
    SELECT 
        p.sku, 
        p.name AS title, 
        p.price AS total_product_price
    FROM products p
    LEFT JOIN product_categories pc 
        ON p.id = pc.product_id
    WHERE $whereSql
    GROUP BY p.sku
    ORDER BY p.name ASC
";

// Query uitvoeren
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['error' => 'Query fout: ' . $conn->error]);
    exit;
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = [
        'sku' => $row['sku'],
        'title' => $row['title'],
        'total_product_price' => $row['total_product_price']
    ];
}

echo json_encode($products);
?>
