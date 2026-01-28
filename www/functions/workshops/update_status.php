<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$referer = $_GET['referer'];

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    http_response_code(400);
    echo 'Ongeldige parameters.';
    exit;
}

$id = intval($_GET['id']);
$status = $_GET['status'];

$geldige_statussen = ['approved', 'declined'];

if (!in_array($status, $geldige_statussen)) {
    http_response_code(400);
    echo 'Ongeldige status.';
    exit;
}

$query = $conn->prepare("UPDATE workshop_bookings SET status = ?, updated_at = NOW() WHERE id = ?");
$query->bind_param("si", $status, $id);

if ($query->execute()) {
    echo "Status succesvol bijgewerkt naar: $status";
} else {
    http_response_code(500);
    echo "Er is een fout opgetreden: " . $query->error;
}

$query->close();
$conn->close();

header($referer);
?>

