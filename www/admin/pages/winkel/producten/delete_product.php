<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header("Location: /admin/pages/products/index.php?error=1");
    exit;
}

// Verwijder gerelateerde gegevens eerst (foreign key safe)
$conn->query("DELETE FROM product_images WHERE product_id = $id");
$conn->query("DELETE FROM product_categories WHERE product_id = $id");
$conn->query("DELETE FROM product_subcategories WHERE product_id = $id");

// Verwijder het product zelf
$conn->query("DELETE FROM products WHERE id = $id");

header("Location: /admin/pages/products/index.php?deleted=1");
exit;
