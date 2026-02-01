<?php
header('Content-Type: application/json');

// Start session for CSRF
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load config - try multiple paths
$configPaths = [
    __DIR__ . '/../../config.php',
    $_SERVER['DOCUMENT_ROOT'] . '/admin/config.php',
    $_SERVER['DOCUMENT_ROOT'] . '/admin/admin/config.php'
];

$configLoaded = false;
foreach ($configPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $configLoaded = true;
        break;
    }
}

if (!$configLoaded) {
    echo json_encode(['success' => false, 'message' => 'Configuratie niet gevonden']);
    exit;
}

// Check database connection
if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database verbinding mislukt']);
    exit;
}

// Get POST data
$action = $_POST['action'] ?? '';
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

// CSRF validation for non-multipart forms
if (isset($_POST['csrf_token']) && !validateCSRFToken($_POST['csrf_token'])) {
    echo json_encode(['success' => false, 'message' => 'Ongeldige CSRF token']);
    exit;
}

try {
    if ($action === 'create') {
        // Get form data
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $sku = trim($_POST['sku'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $shortDescription = trim($_POST['short_description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $regularPrice = !empty($_POST['regular_price']) ? floatval($_POST['regular_price']) : null;
        $salePrice = !empty($_POST['sale_price']) ? floatval($_POST['sale_price']) : null;
        $stockQuantity = intval($_POST['stock_quantity'] ?? 0);
        $stockStatus = $_POST['stock_status'] ?? 'instock';
        $type = $_POST['type'] ?? 'simple';
        $productType = $_POST['product_type'] ?? 'other';
        $categoryId = intval($_POST['category_id'] ?? 0);
        
        // Validate required fields
        if (empty($name) || empty($sku) || $price <= 0) {
            throw new Exception('Vul alle verplichte velden in');
        }
        
        // Generate slug if empty
        if (empty($slug)) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        }
        
        // Insert product
        $stmt = $conn->prepare("
            INSERT INTO products (name, slug, sku, description, short_description, price, regular_price, sale_price, stock_quantity, stock_status, type, product_type, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->bind_param('sssssdddisss', $name, $slug, $sku, $description, $shortDescription, $price, $regularPrice, $salePrice, $stockQuantity, $stockStatus, $type, $productType);
        
        if (!$stmt->execute()) {
            throw new Exception('Fout bij opslaan product: ' . $conn->error);
        }
        
        $productId = $conn->insert_id;
        $stmt->close();
        
        // Insert category relation
        if ($categoryId > 0) {
            $stmt = $conn->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
            $stmt->bind_param('ii', $productId, $categoryId);
            $stmt->execute();
            $stmt->close();
        }
        
        // Handle image upload
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $ext = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            
            if (!in_array($ext, $allowedExts)) {
                throw new Exception('Ongeldig bestandstype. Alleen JPG, PNG, WebP en GIF toegestaan.');
            }
            
            $filename = $productId . '-' . time() . '.' . $ext;
            $filepath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $filepath)) {
                $imagePath = '/images/products/' . $filename;
                $stmt = $conn->prepare("INSERT INTO product_images (product_id, image_path, is_main) VALUES (?, ?, 1)");
                $stmt->bind_param('is', $productId, $imagePath);
                $stmt->execute();
                $stmt->close();
            }
        }
        
        // Handle materials
        saveMaterials($conn, $productId, $productType, $_POST);
        
        // Log activity
        $userId = getCurrentUser()['id'];
        logAdminActivity($conn, $userId, 'create', 'product', $productId, "Product '$name' toegevoegd");
        
        echo json_encode(['success' => true, 'message' => 'Product toegevoegd', 'product_id' => $productId]);
        
    } elseif ($action === 'update') {
        if ($productId <= 0) {
            throw new Exception('Ongeldig product ID');
        }
        
        // Get form data
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $sku = trim($_POST['sku'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $shortDescription = trim($_POST['short_description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $regularPrice = !empty($_POST['regular_price']) ? floatval($_POST['regular_price']) : null;
        $salePrice = !empty($_POST['sale_price']) ? floatval($_POST['sale_price']) : null;
        $stockQuantity = intval($_POST['stock_quantity'] ?? 0);
        $stockStatus = $_POST['stock_status'] ?? 'instock';
        $type = $_POST['type'] ?? 'simple';
        $productType = $_POST['product_type'] ?? 'other';
        $categoryId = intval($_POST['category_id'] ?? 0);
        
        // Update product
        $stmt = $conn->prepare("
            UPDATE products SET 
                name = ?, slug = ?, sku = ?, description = ?, short_description = ?,
                price = ?, regular_price = ?, sale_price = ?, stock_quantity = ?,
                stock_status = ?, type = ?, product_type = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->bind_param('sssssdddiisssi', $name, $slug, $sku, $description, $shortDescription, $price, $regularPrice, $salePrice, $stockQuantity, $stockStatus, $type, $productType, $productId);
        
        if (!$stmt->execute()) {
            throw new Exception('Fout bij bijwerken product: ' . $conn->error);
        }
        $stmt->close();
        
        // Update category
        $conn->query("DELETE FROM product_categories WHERE product_id = $productId");
        if ($categoryId > 0) {
            $stmt = $conn->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
            $stmt->bind_param('ii', $productId, $categoryId);
            $stmt->execute();
            $stmt->close();
        }
        
        // Handle image upload (if new image provided)
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/products/';
            
            $ext = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            
            if (in_array($ext, $allowedExts)) {
                $filename = $productId . '-' . time() . '.' . $ext;
                $filepath = $uploadDir . $filename;
                
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $filepath)) {
                    // Remove old main image
                    $conn->query("UPDATE product_images SET is_main = 0 WHERE product_id = $productId");
                    
                    $imagePath = '/images/products/' . $filename;
                    $stmt = $conn->prepare("INSERT INTO product_images (product_id, image_path, is_main) VALUES (?, ?, 1)");
                    $stmt->bind_param('is', $productId, $imagePath);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
        
        // Handle materials
        saveMaterials($conn, $productId, $productType, $_POST);
        
        // Log activity
        $userId = getCurrentUser()['id'];
        logAdminActivity($conn, $userId, 'update', 'product', $productId, "Product '$name' bijgewerkt");
        
        echo json_encode(['success' => true, 'message' => 'Product bijgewerkt']);
        
    } else {
        throw new Exception('Ongeldige actie');
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Helper function to save materials
function saveMaterials($conn, $productId, $productType, $postData) {
    // Delete existing materials
    $conn->query("DELETE FROM product_materials WHERE product_id = $productId");
    
    // Insert new materials based on product type
    if ($productType === 'candle') {
        $stearine = floatval($postData['stearine_grams'] ?? 0);
        $paraffine = floatval($postData['paraffine_grams'] ?? 0);
        
        if ($stearine > 0) {
            $stmt = $conn->prepare("INSERT INTO product_materials (product_id, material_type, grams) VALUES (?, 'stearine', ?)");
            $stmt->bind_param('id', $productId, $stearine);
            $stmt->execute();
            $stmt->close();
        }
        
        if ($paraffine > 0) {
            $stmt = $conn->prepare("INSERT INTO product_materials (product_id, material_type, grams) VALUES (?, 'paraffine', ?)");
            $stmt->bind_param('id', $productId, $paraffine);
            $stmt->execute();
            $stmt->close();
        }
        
    } elseif ($productType === 'terrazzo') {
        $powder = floatval($postData['terrazzo_powder_grams'] ?? 0);
        $notes = trim($postData['terrazzo_notes'] ?? '');
        
        if ($powder > 0) {
            $stmt = $conn->prepare("INSERT INTO product_materials (product_id, material_type, grams, notes) VALUES (?, 'terrazzo_powder', ?, ?)");
            $stmt->bind_param('ids', $productId, $powder, $notes);
            $stmt->execute();
            $stmt->close();
        }
        
    } elseif ($productType === 'epoxy') {
        $epoxy = floatval($postData['epoxy_grams'] ?? 0);
        
        if ($epoxy > 0) {
            $stmt = $conn->prepare("INSERT INTO product_materials (product_id, material_type, grams) VALUES (?, 'epoxy', ?)");
            $stmt->bind_param('id', $productId, $epoxy);
            $stmt->execute();
            $stmt->close();
        }
    }
}
?>
