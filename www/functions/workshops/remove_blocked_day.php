<?php

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo "Ongeldige ID.";
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM workshop_blocked_days WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // Optioneel: redirect terug naar beheerpagina
    header('Location: /admin/pages/workshops/?blocked_removed=1');
    exit;
} else {
    http_response_code(500);
    echo "Fout bij verwijderen: " . $stmt->error;
}

$stmt->close();
$conn->close();
