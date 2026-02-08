<?php
ob_start();
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');
ob_end_clean();

header('Content-Type: application/json; charset=utf-8');

try {
    if ($conn->connect_error) {
        throw new RuntimeException('Databaseverbinding mislukt.');
    }

    $result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
    if (!$result) {
        throw new RuntimeException('Categorieën konden niet worden opgehaald.');
    }

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode($categories, JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    error_log('get_categories.php fout: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => 'Kan categorieën niet ophalen. Probeer opnieuw.',
    ]);
}

exit;
