<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$subject = trim($data['subject'] ?? '');
$message = trim($data['message'] ?? '');

if (!$subject || !$message) {
    echo json_encode(['success' => false, 'message' => 'Onderwerp en inhoud zijn verplicht.']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO newsletters (subject, message, created_at) VALUES (?, ?, NOW())");
$stmt->bind_param("ss", $subject, $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Nieuwsbrief succesvol opgeslagen.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Opslaan mislukt.']);
}

$stmt->close();
$conn->close();
