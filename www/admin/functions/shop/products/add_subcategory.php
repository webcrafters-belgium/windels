<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$name = trim($data['name'] ?? '');
$parent_id = (int)($data['parent_id'] ?? 0);

if (!$name || !$parent_id) {
    echo json_encode(['success' => false, 'message' => 'Ongeldige invoer.']);
    exit;
}

$slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
$stmt = $conn->prepare("INSERT INTO subcategories (parent_id, name, slug) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $parent_id, $name, $slug);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'name' => $name, 'slug' => $slug]);
} else {
    echo json_encode(['success' => false, 'message' => 'Fout bij toevoegen.']);
}
