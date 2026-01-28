<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Zorg dat het formulier via POST is verzonden
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /admin/pages/winkel/producten/index.php");
    exit;
}

// Valideer velden
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;

if ($product_id <= 0 || $category_id <= 0) {
    header("Location: /admin/pages/winkel/producten/index.php?error=invalid_input");
    exit;
}

// Controleer of combinatie al bestaat
$checkStmt = $conn->prepare("SELECT 1 FROM product_categories WHERE product_id = ? AND category_id = ?");
$checkStmt->bind_param("ii", $product_id, $category_id);
$checkStmt->execute();
$exists = $checkStmt->get_result()->num_rows > 0;
$checkStmt->close();

if (!$exists) {
    // Voeg categorie toe
    $insertStmt = $conn->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
    $insertStmt->bind_param("ii", $product_id, $category_id);
    $insertStmt->execute();
    $insertStmt->close();
}

// Optioneel: timestamp bijwerken
$updateStmt = $conn->prepare("UPDATE products SET updated_at = NOW() WHERE id = ?");
$updateStmt->bind_param("i", $product_id);
$updateStmt->execute();
$updateStmt->close();

// Redirect terug naar overzicht met filter op uncategorized
header("Location: /admin/pages/winkel/producten/index.php?category=none&success=1");
exit;
