<?php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$category = $_GET['category'] ?? '';
$limit = isset($_GET['limit']) && ctype_digit($_GET['limit']) ? (int)$_GET['limit'] : 500;
$limit = max(1, min($limit, 500));

$where = '';
$params = [];
$types = '';

if ($category !== '') {
    if (ctype_digit($category)) {
        $where = "WHERE c.id = ?";
        $params[] = (int)$category;
        $types .= 'i';
    } else {
        $where = "WHERE c.slug = ?";
        $params[] = $category;
        $types .= 's';
    }
}

$sql = "
SELECT 
    p.id,
    p.name,
    p.price,
    p.sku,
    c.slug AS category_slug,
    COALESCE(pi.webp_path, pi.image_path) AS image
FROM products p
LEFT JOIN product_subcategories ps ON ps.product_id = p.id
LEFT JOIN subcategories s ON s.id = ps.subcategory_id
LEFT JOIN categories c ON c.id = s.category_id
LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_main = 1
$where
GROUP BY p.id
ORDER BY p.updated_at DESC
LIMIT ?
";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode([]);
    exit;
}

// bind params
if ($types === '') {
    $stmt->bind_param('i', $limit);
} else {
    $types .= 'i';
    $params[] = $limit;
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = [
        'id' => (int)$row['id'],
        'name' => $row['name'],
        'price' => (float)$row['price'],
        'sku' => $row['sku'],
        'category' => $row['category_slug'],
        'image' => $row['image'],
    ];
}

echo json_encode($products);
$stmt->close();
$conn->close();
