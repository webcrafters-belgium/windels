# /admin/functions/shop/products/insert_product.php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');
require_once __DIR__ . '/parent_column.php';

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
$stock              = (int)($_POST['stock_quantity'] ?? 0);
$stock_status       = trim($_POST['stock_status'] ?? 'instock');
$price              = trim($_POST['price'] ?? '0.00');
$regular_price      = trim($_POST['regular_price'] ?? $price);
$sale_price_raw     = trim($_POST['sale_price'] ?? '');
$parent_id_raw      = $_POST['parent_id'] ?? ''; // kan leeg zijn
$created_at         = date('Y-m-d H:i:s');
$updated_at         = $created_at;

$amount_grams       = trim($_POST['amount_grams'] ?? '');
$paraffin_percentage = trim($_POST['paraffin_percentage'] ?? '');
$stearin_percentage  = trim($_POST['stearin_percentage'] ?? '');

// Sale price naar NULL indien leeg
$sale_price = ($sale_price_raw === '' ? null : $sale_price_raw);

// Parent-id casten of NULL
$parent_id = ($parent_id_raw === '' || $parent_id_raw === null) ? null : (int)$parent_id_raw;

$parentColumnAvailable = ensureProductParentColumn($conn);
if (!$parentColumnAvailable) {
    $parent_id = null;
}

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

foreach ([
    'amount_grams' => $amount_grams,
    'paraffin_percentage' => $paraffin_percentage,
    'stearin_percentage' => $stearin_percentage
] as $field => $value) {
    if ($value !== '' && !is_numeric($value)) {
        $errors[] = "❌ {$field} moet een getal zijn (laat leeg als niet van toepassing).";
    }
}

// Als parent is opgegeven: moet bestaan en zelf géén parent hebben (top-level)
if ($parentColumnAvailable && $parent_id !== null) {
    $stmt_p = $conn->prepare("SELECT id FROM products WHERE id = ? AND parent_id IS NULL LIMIT 1");
    $stmt_p->bind_param("i", $parent_id);
    $stmt_p->execute();
    $stmt_p->store_result();
    if ($stmt_p->num_rows === 0) {
        $errors[] = "❌ Ongeldige parent geselecteerd (bestaat niet of is zelf een variant).";
    }
    $stmt_p->close();
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

// Begin transactie
$conn->begin_transaction();

try {
    // Product inserten (met parent_id)
    // Let op: sale_price kan NULL zijn → we binden als string en zetten via conditional set_null
    $base_columns = [
        'name', 'slug', 'sku', 'type',
        'description', 'short_description',
        'price', 'regular_price', 'sale_price',
        'stock_quantity', 'stock_status'
    ];

    $base_values = [
        $name,
        $slug,
        $sku,
        $type,
        $description,
        $short_description,
        $price,
        $regular_price,
        $sale_price,
        $stock,
        $stock_status,
    ];

    $base_types = 'sssssssssiss';

    $all_columns = $base_columns;
    $all_values = $base_values;
    $all_types = $base_types;

    if ($parentColumnAvailable) {
        $all_columns[] = 'parent_id';
        $all_values[] = $parent_id;
        $all_types .= 'i';
    }

    $all_columns[] = 'created_at';
    $all_values[] = $created_at;
    $all_columns[] = 'updated_at';
    $all_values[] = $updated_at;
    $all_types .= 'ss';

    $optional_definitions = [
        'amount_grams' => $amount_grams,
        'paraffin_percentage' => $paraffin_percentage,
        'stearin_percentage' => $stearin_percentage,
    ];

    $optional_columns = [];
    foreach ($optional_definitions as $column => $value) {
        $stmt_col = $conn->prepare("SHOW COLUMNS FROM products LIKE ?");
        $stmt_col->bind_param("s", $column);
        $stmt_col->execute();
        $stmt_col->store_result();
        if ($stmt_col->num_rows > 0) {
            $optional_columns[$column] = ($value === '' ? null : $value);
        }
        $stmt_col->free_result();
        $stmt_col->close();
    }

    foreach ($optional_columns as $column => $value) {
        $all_columns[] = $column;
        $all_values[] = $value;
        $all_types .= 's';
    }

    $placeholders = implode(', ', array_fill(0, count($all_columns), '?'));
    $sql = sprintf(
        "INSERT INTO products (%s) VALUES (%s)",
        implode(', ', $all_columns),
        $placeholders
    );
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Fout bij voorbereiden product insert: " . $conn->error);
    }

    $params = array_merge([$all_types], $all_values);
    $refs = [];
    foreach ($params as $key => $value) {
        $refs[$key] = &$params[$key];
    }

    if (!call_user_func_array([$stmt, 'bind_param'], $refs)) {
        throw new Exception("Fout bij binden productwaarden: " . $stmt->error);
    }

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
    }

    // Commit
    $conn->commit();

    // ✅ Redirect met succesmelding
    header("Location: /admin/pages/winkel/producten/add/index.php?product_id=$product_id&success=1");
    exit;

} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    echo "❌ Transactie geannuleerd: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    exit;
}
