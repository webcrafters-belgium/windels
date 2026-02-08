// FILE: /admin/pages/winkel/producten/add/save.php
<?php
function build_manual_image_path(string $directory, string $filename): ?string
{
    $directory = trim($directory);
    $filename  = trim($filename);

    if ($directory === '' && $filename === '') return null;

    $directory = str_replace('\\', '/', $directory);
    $filename  = str_replace('\\', '/', $filename);

    // full path pasted into filename
    if ($directory === '' && str_contains($filename, '/')) {
        $directory = dirname($filename);
        $filename  = basename($filename);
    }

    if ($directory === '' || $filename === '') return null;

    if (str_contains($directory, '..') || str_contains($filename, '..')) return null;

    // Strip query/fragment
    $filename = preg_replace('/[?#].*$/', '', $filename);

    $directory = '/' . trim(preg_replace('#/+#', '/', $directory), '/');
    $filename  = basename($filename);

    if ($filename === '' || $filename === '.' || $filename === '..') return null;

    return $directory === '/' ? '/' . $filename : $directory . '/' . $filename;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

$errors = [];

$category_id        = (int)($_POST['category'] ?? 0);
$subcategory_slug   = trim($_POST['sub_category'] ?? '');
$sku                = trim($_POST['sku'] ?? '');
$name               = trim($_POST['name'] ?? '');
$slug               = trim($_POST['slug'] ?? '');
$description        = trim($_POST['description'] ?? '');
$short_description  = trim($_POST['short_description'] ?? '');
$type               = trim($_POST['type'] ?? 'simple');
$product_type       = trim($_POST['product_type'] ?? '');
$stock              = (int)($_POST['stock_quantity'] ?? 0);
$stock_status       = trim($_POST['stock_status'] ?? 'instock');
$price              = trim($_POST['price'] ?? '0.00');
$regular_price      = trim($_POST['regular_price'] ?? $price);
$sale_price_raw     = trim($_POST['sale_price'] ?? '');
$weight_grams_raw   = trim($_POST['weight_grams'] ?? '');
$created_at         = date('Y-m-d H:i:s');
$updated_at         = $created_at;

// Handmatige afbeelding-optie
$manual_image_path  = trim($_POST['manual_image_path'] ?? '');
$manual_image_name  = trim($_POST['manual_image_name'] ?? '');

// Sale price naar NULL indien leeg
$sale_price = ($sale_price_raw === '' ? null : $sale_price_raw);

// Weight naar NULL indien leeg
$weight_grams = ($weight_grams_raw === '' ? null : $weight_grams_raw);

// --- Validatie ---
if (!preg_match('/^\d+-\d+$/', $sku)) {
    $errors[] = "❌ SKU is ongeldig. Verwacht formaat: {categorie-id}-{nummer}.";
}
if (!$sku || !$category_id || !$subcategory_slug || !$name || !$slug || !$description) {
    $errors[] = "❌ Verplichte velden ontbreken.";
}
if ($stock < 0) {
    $errors[] = "❌ Voorraad mag niet negatief zijn.";
}
if (!preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
    $errors[] = "❌ Prijs heeft een ongeldig formaat.";
}
if ($regular_price !== '' && !preg_match('/^\d+(\.\d{1,2})?$/', $regular_price)) {
    $errors[] = "❌ Normale prijs heeft een ongeldig formaat.";
}
if ($sale_price !== null && $sale_price !== '' && !preg_match('/^\d+(\.\d{1,2})?$/', $sale_price)) {
    $errors[] = "❌ Actieprijs heeft een ongeldig formaat.";
}
if ($weight_grams !== null && $weight_grams !== '' && !is_numeric($weight_grams)) {
    $errors[] = "❌ weight_grams moet een getal zijn (laat leeg als niet van toepassing).";
}

if (!empty($errors)) {
    echo "<h3>Foutmelding:</h3><ul>";
    foreach ($errors as $e) echo "<li>$e</li>";
    echo "</ul>";
    exit;
}

// SKU uniek?
$stmt_check = $conn->prepare("SELECT id FROM products WHERE sku = ? LIMIT 1");
$stmt_check->bind_param("s", $sku);
$stmt_check->execute();
$stmt_check->store_result();
if ($stmt_check->num_rows > 0) {
    echo "❌ SKU '$sku' bestaat al.";
    exit;
}
$stmt_check->close();

$conn->begin_transaction();

try {
    // ✅ EXACT jouw kolommen
    $sql = "
        INSERT INTO products
        (name, slug, sku, type, product_type, description, short_description, price, regular_price, sale_price,
         weight_grams, stock_quantity, stock_status, created_at, updated_at)
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
         ?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Fout bij voorbereiden product insert: " . $conn->error);
    }

    // types: 10x s (t/m sale_price) + s(weight) + i(stock) + s(status) + s + s
    $stmt->bind_param(
            "sssssssssssisss",
            $name, $slug, $sku, $type, $product_type, $description, $short_description, $price, $regular_price, $sale_price,
            $weight_grams, $stock, $stock_status, $created_at, $updated_at
    );

    if (!$stmt->execute()) {
        throw new Exception("Fout bij toevoegen product: " . $stmt->error);
    }
    $product_id = $stmt->insert_id;
    $stmt->close();

    // Koppelen hoofdcategorie
    $stmt_cat = $conn->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
    $stmt_cat->bind_param("ii", $product_id, $category_id);
    if (!$stmt_cat->execute()) {
        throw new Exception("Fout categorie-koppeling: " . $stmt_cat->error);
    }
    $stmt_cat->close();

    // Koppelen subcategorie (op slug)
    $stmt_sub = $conn->prepare("SELECT id FROM subcategories WHERE slug = ? LIMIT 1");
    $stmt_sub->bind_param("s", $subcategory_slug);
    $stmt_sub->execute();
    $res_sub = $stmt_sub->get_result();
    if ($r = $res_sub->fetch_assoc()) {
        $subcategory_id = (int)$r['id'];
        $stmt_link = $conn->prepare("INSERT INTO product_subcategories (subcategory_id, product_id) VALUES (?, ?)");
        $stmt_link->bind_param("ii", $subcategory_id, $product_id);
        if (!$stmt_link->execute()) {
            throw new Exception("Fout subcategorie-koppeling: " . $stmt_link->error);
        }
        $stmt_link->close();
    }
    $stmt_sub->close();

    $image_saved = false;

    // Afbeelding upload (optioneel)
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Uploadfout (code: {$_FILES['product_image']['error']}).");
        }

        // Categorie-slug ophalen voor pad
        $stmt_cat_slug = $conn->prepare("SELECT slug FROM categories WHERE id = ? LIMIT 1");
        $stmt_cat_slug->bind_param("i", $category_id);
        $stmt_cat_slug->execute();
        $cat_result = $stmt_cat_slug->get_result();
        $row_cat = $cat_result->fetch_assoc();
        $stmt_cat_slug->close();

        $category_slug = $row_cat['slug'] ?? '';
        if (!$category_slug) {
            throw new Exception("Kan categorie-slug niet vinden.");
        }

        // Ext + mimetype whitelisting
        $image = $_FILES['product_image'];
        $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg','jpeg','png','webp'];
        if (!in_array($ext, $allowed_ext, true)) {
            throw new Exception("Bestandstype niet toegestaan. Toegestaan: jpg, jpeg, png, webp.");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $image['tmp_name']);
        finfo_close($finfo);
        $allowed_mime = ['image/jpeg','image/png','image/webp'];
        if (!in_array($mime, $allowed_mime, true)) {
            throw new Exception("Ongeldig MIME-type: $mime.");
        }

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/images/products/$category_slug/$sku/";
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            throw new Exception("Kon uploadmap niet aanmaken.");
        }

        $filename   = "main.$ext";
        $targetFile = $uploadDir . $filename;

        if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
            throw new Exception("Uploaden van afbeelding is mislukt.");
        }

        $relativePath = "/images/products/$category_slug/$sku/$filename";
        $stmt_img = $conn->prepare("
            INSERT INTO product_images (product_id, sku, image_path, is_main)
            VALUES (?, ?, ?, 1)
        ");
        $stmt_img->bind_param("iss", $product_id, $sku, $relativePath);
        if (!$stmt_img->execute()) {
            throw new Exception("Fout bij opslaan productafbeelding: " . $stmt_img->error);
        }
        $stmt_img->close();
        $image_saved = true;
    }

    // Manual image fallback
    $manualImagePath = build_manual_image_path($manual_image_path, $manual_image_name);
    if (!$image_saved && $manualImagePath) {
        $stmt_img = $conn->prepare("
            INSERT INTO product_images (product_id, sku, image_path, is_main)
            VALUES (?, ?, ?, 1)
        ");
        $stmt_img->bind_param("iss", $product_id, $sku, $manualImagePath);
        if (!$stmt_img->execute()) {
            throw new Exception("Fout bij opslaan productafbeelding: " . $stmt_img->error);
        }
        $stmt_img->close();
        $image_saved = true;
    }

    $conn->commit();

    header("Location: /admin/pages/winkel/producten/add/index.php?product_id=$product_id&success=1");
    exit;

} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    echo "❌ Transactie geannuleerd: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit;
}
