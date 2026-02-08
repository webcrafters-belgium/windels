<?php
ob_start();
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
ob_end_clean();

header('Content-Type: application/json; charset=utf-8');

$parentId = $_GET['parent_id'] ?? '';

/* 1. Eerste validatie */
if (!ctype_digit($parentId)) {
    echo json_encode([]);
    exit;
}

try {
    if ($conn->connect_error) {
        throw new RuntimeException('Databaseverbinding mislukt.');
    }

    $stmt = $conn->prepare(
        'SELECT slug, name 
         FROM   subcategories 
         WHERE  category_id = ?
         ORDER  BY name'
    );
    if (!$stmt) {
        throw new RuntimeException('Subcategorieën konden niet worden opgehaald.');
    }

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

    echo json_encode($subcategories, JSON_UNESCAPED_UNICODE);
    $stmt->close();
} catch (Throwable $e) {
    error_log('get_subcategories.php fout: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Kan subcategorieën niet ophalen.']);
}

exit;
