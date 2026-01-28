<?php
declare(strict_types=1);

header('Content-Type: application/json');

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();

/* ----------------------------------------------
   AUTH CONTROL (ADMIN ONLY)
---------------------------------------------- */
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'Onvoldoende rechten.'
    ]);
    exit;
}

/* ----------------------------------------------
   INPUT VALIDATION
---------------------------------------------- */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Order ID is ongeldig.'
    ]);
    exit;
}

$orderId = (int)$_GET['id'];

/* ----------------------------------------------
   BESTAAT DE ORDER?
---------------------------------------------- */
$check = $conn->prepare("SELECT id FROM orders WHERE id = ?");
$check->bind_param("i", $orderId);
$check->execute();
$exists = $check->get_result()->num_rows > 0;

if (!$exists) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Order niet gevonden.'
    ]);
    exit;
}

/* ----------------------------------------------
   DELETE ORDER
---------------------------------------------- */
$del = $conn->prepare("DELETE FROM orders WHERE id = ?");
$del->bind_param("i", $orderId);
$success = $del->execute();

if (!$success) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Fout bij verwijderen van de bestelling.'
    ]);
    exit;
}

/* ----------------------------------------------
   DONE
---------------------------------------------- */
echo json_encode([
    'success' => true,
    'message' => "Bestelling #$orderId is verwijderd."
]);
exit;
