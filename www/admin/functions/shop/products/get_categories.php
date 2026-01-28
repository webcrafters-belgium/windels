<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');
header('Content-Type: application/json');

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Databaseverbinding mislukt']);
    exit;
}

$result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

echo json_encode($categories);
$conn->close();
