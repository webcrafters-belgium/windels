<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');

// Controleer of formulier is gepost
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id'])) {
    header('Location: /admin/');
    exit;
}

$id             = (int)$_POST['id'];
$name           = trim($_POST['name']);
$price          = (float)$_POST['price'];
$stock          = (int)$_POST['stock'];
$description    = trim($_POST['description']);
$category_id    = (int)$_POST['category'];
$subcategory_id = (int)$_POST['sub_category'];
$image_path      = isset($_POST['image_path']) ? trim($_POST['image_path']) : '';

// Product updaten
$stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, stock_quantity = ?, description = ?, updated_at = NOW() WHERE id = ?");
$stmt->bind_param("sddsi", $name, $price, $stock, $description, $id);
$stmt->execute();

// Categorie bijwerken
$conn->query("DELETE FROM product_categories WHERE product_id = $id");
if ($category_id > 0) {
    $stmtCat = $conn->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
    $stmtCat->bind_param("ii", $id, $category_id);
    $stmtCat->execute();
}

// Subcategorie bijwerken
$conn->query("DELETE FROM product_subcategories WHERE product_id = $id");
if ($subcategory_id > 0) {
    $stmtSubCat = $conn->prepare("INSERT INTO product_subcategories (product_id, subcategory_id) VALUES (?, ?)");
    $stmtSubCat->bind_param("ii", $id, $subcategory_id);
    $stmtSubCat->execute();
}

// Verwerk nieuwe afbeelding indien geüpload
if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    $tmpPath = $_FILES['image_file']['tmp_name'];
    $fileInfo = pathinfo($_FILES['image_file']['name']);
    $ext = strtolower($fileInfo['extension']);

    // Bepaal categorie-slug
    $catSlugQuery = $conn->query("SELECT slug FROM categories WHERE id = $category_id LIMIT 1");
    $catSlugRow = $catSlugQuery->fetch_assoc();
    $slug = $catSlugRow['slug'] ?? 'uncategorized';

    // Pad waar bestand wordt opgeslagen
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/images/products/$slug/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

    $newFilename = $id . '.webp';
    $destination = $targetDir . $newFilename;
    $imageUrl = "/images/products/$slug/" . $newFilename;

    // Converteer naar .webp indien jpg/jpeg/png
    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
        $image = ($ext === 'png') ? imagecreatefrompng($tmpPath) : imagecreatefromjpeg($tmpPath);
        if ($image) {
            imagewebp($image, $destination, 85);
            imagedestroy($image);

            // Oude afbeelding verwijderen
            $conn->query("DELETE FROM product_images WHERE product_id = $id");

            // Nieuwe invoegen
            $stmtImg = $conn->prepare("INSERT INTO product_images (product_id, webp_path, is_main, is_edited) VALUES (?, ?, 1, 1)");
            $stmtImg->bind_param("is", $id, $imageUrl);
            $stmtImg->execute();
        }
    }
} else if (!empty($image_path)) {
    // Als geen nieuw bestand, maar image_path is gevuld (bv. via form hidden field), update dan image_path in database
    $conn->query("DELETE FROM product_images WHERE product_id = $id");

    $stmtImg = $conn->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
    $stmtImg->bind_param("is", $id, $image_path);
    $stmtImg->execute();
}

// Redirect terug naar edit-pagina met succesmelding
header("Location: https://windelsgreen-decoresin.com/admin/pages/winkel/producten/edit_product.php?id=$id&updated=1");
exit;
