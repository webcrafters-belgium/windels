<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$title = $_POST['title'];
$subtitle = $_POST['subtitle'];
$discount = $_POST['discount_percentage'];
$promo_type = $_POST['promo_type'];

$product_sku = $_POST['product_sku'] ?? null;
$category_id = $_POST['category_id'] ?? null;
$subcategory_id = $_POST['subcategory_id'] ?? null;

$start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
$end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
$image_path = null;

// Afbeelding uploaden
if (!empty($_FILES['image']['name'])) {
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/promos/';
    $filename = uniqid() . '_' . basename($_FILES['image']['name']);
    $target_file = $upload_dir . $filename;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $image_path = 'images/promos/' . $filename;
    }
}

// Zet alle waarden op NULL behalve gekozen type
if ($promo_type === 'product') {
    $category_id = null;
    $subcategory_id = null;
} elseif ($promo_type === 'category') {
    $product_sku = null;
    $subcategory_id = null;
} elseif ($promo_type === 'subcategory') {
    $product_sku = null;
    $category_id = null;
}

// Voeg promo toe
$stmt = $conn->prepare("INSERT INTO promos 
    (product_sku, category_id, subcategory_id, title, subtitle, discount_percentage, promo_type, image_path, start_date, end_date, created_by) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$created_by = $_SESSION['user_id'] ?? null;

$stmt->bind_param(
    "siissdssssi",
    $product_sku,
    $category_id,
    $subcategory_id,
    $title,
    $subtitle,
    $discount,
    $promo_type,
    $image_path,
    $start_date,
    $end_date,
    $created_by
);

$stmt->execute();
$stmt->close();

header("Location: add.php?success=1");
exit;
