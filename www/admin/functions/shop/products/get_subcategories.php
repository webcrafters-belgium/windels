<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
header('Content-Type: application/json');

$parentId = $_GET['parent_id'] ?? '';

/* 1. Eerste validatie */
if (!ctype_digit($parentId)) {          // strikter dan is_numeric()
    echo json_encode([]);
    exit;
}

/* 2. Database-check */
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Databaseverbinding mislukt']);
    exit;
}

/* 3. Subcategorieën ophalen */
$stmt = $conn->prepare(
    'SELECT slug, name 
     FROM   subcategories 
     WHERE  category_id = ?          -- ⬅️ kolomnaam gecorrigeerd
     ORDER  BY name'
);
$stmt->bind_param('i', $parentId);
$stmt->execute();

$result = $stmt->get_result();
$subcategories = [];
while ($row = $result->fetch_assoc()) {
    $subcategories[] = [
        'value' => $row['slug'],
        'text'  => $row['name'],
    ];
}

echo json_encode($subcategories);
$stmt->close();
$conn->close();

