<?php
session_start();
require_once '../../config.php';

header('Content-Type: application/json');

// Verify admin and CSRF
try {
    requireAdmin();
    
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        throw new Exception('Ongeldige CSRF token');
    }
    
    $action = $_POST['action'] ?? 'create';
    $productId = $_POST['id'] ?? null;
    
    // Validate required fields
    $required = ['name', 'slug', 'sku', 'category_id', 'product_type', 'price', 'stock_quantity', 'stock_status'];
    foreach ($required as $field) {
        if (empty($_POST[$field]) && $_POST[$field] !== '0') {
            throw new Exception("Veld '$field' is verplicht");
        }
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception('Alleen JPG, PNG en WEBP afbeeldingen zijn toegestaan');
        }
        
        $filename = uniqid('prod_') . '.' . $extension;
        $targetPath = $uploadDir . $filename;
        
        if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $targetPath)) {
            throw new Exception('Fout bij uploaden afbeelding');
        }
        
        $imagePath = '/images/products/' . $filename;
    }
    
    if ($action === 'create') {
        // Insert product
        $stmt = $conn->prepare(
            "INSERT INTO products (name, slug, sku, product_type, type, description, short_description, 
             price, regular_price, sale_price, stock_quantity, stock_status, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())"
        );
        
        $stmt->bind_param(
            'sssssssdddis',
            $_POST['name'],
            $_POST['slug'],
            $_POST['sku'],
            $_POST['product_type'],
            $_POST['type'] ?? 'simple',
            $_POST['description'] ?? '',
            $_POST['short_description'] ?? '',
            $_POST['price'],
            $_POST['regular_price'] ?? null,
            $_POST['sale_price'] ?? null,
            $_POST['stock_quantity'],
            $_POST['stock_status']
        );
        
        if (!$stmt->execute()) {
            throw new Exception('Fout bij opslaan product: ' . $stmt->error);
        }
        
        $productId = $conn->insert_id;
        $stmt->close();
        
        // Insert category relationship
        $stmt = $conn->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
        $stmt->bind_param('ii', $productId, $_POST['category_id']);
        $stmt->execute();
        $stmt->close();
        
        // Insert image if uploaded
        if ($imagePath) {
            $stmt = $conn->prepare(
                "INSERT INTO product_images (product_id, image_path, is_main, created_at) 
                 VALUES (?, ?, 1, NOW())"
            );
            $stmt->bind_param('is', $productId, $imagePath);
            $stmt->execute();
            $stmt->close();
        }
        
        // Insert materials based on product type
        $productType = $_POST['product_type'];
        
        if ($productType === 'candle') {
            // Insert stearine
            if (!empty($_POST['stearine_grams']) && $_POST['stearine_grams'] > 0) {
                $stmt = $conn->prepare(
                    "INSERT INTO product_materials (product_id, material_type, grams, created_at, updated_at)
                     VALUES (?, 'stearine', ?, NOW(), NOW())"
                );
                $stmt->bind_param('id', $productId, $_POST['stearine_grams']);
                $stmt->execute();
                $stmt->close();
            }
            
            // Insert paraffine
            if (!empty($_POST['paraffine_grams']) && $_POST['paraffine_grams'] > 0) {
                $stmt = $conn->prepare(
                    "INSERT INTO product_materials (product_id, material_type, grams, created_at, updated_at)
                     VALUES (?, 'paraffine', ?, NOW(), NOW())"
                );
                $stmt->bind_param('id', $productId, $_POST['paraffine_grams']);
                $stmt->execute();
                $stmt->close();
            }
        } elseif ($productType === 'terrazzo') {
            if (!empty($_POST['terrazzo_powder_grams']) && $_POST['terrazzo_powder_grams'] > 0) {
                $stmt = $conn->prepare(
                    "INSERT INTO product_materials (product_id, material_type, grams, notes, created_at, updated_at)
                     VALUES (?, 'terrazzo_powder', ?, ?, NOW(), NOW())"
                );
                $notes = $_POST['terrazzo_notes'] ?? '';
                $stmt->bind_param('ids', $productId, $_POST['terrazzo_powder_grams'], $notes);
                $stmt->execute();
                $stmt->close();
            }
        } elseif ($productType === 'epoxy') {
            if (!empty($_POST['epoxy_grams']) && $_POST['epoxy_grams'] > 0) {
                $stmt = $conn->prepare(
                    "INSERT INTO product_materials (product_id, material_type, grams, created_at, updated_at)
                     VALUES (?, 'epoxy', ?, NOW(), NOW())"
                );
                $stmt->bind_param('id', $productId, $_POST['epoxy_grams']);
                $stmt->execute();
                $stmt->close();
            }
        }
        
        // Log activity
        logAdminActivity(
            $conn,
            getCurrentUser()['id'],
            'create',
            'product',
            $productId,
            "Product toegevoegd: {$_POST['name']}"
        );
        
        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Product succesvol toegevoegd',
            'product_id' => $productId
        ]);
        
    } elseif ($action === 'update') {
        // Update logic would go here
        // For now, just create is implemented
        throw new Exception('Update functionaliteit nog niet geïmplementeerd');
    }
    
} catch (Exception $e) {
    if (isset($conn)) {
        $conn->rollback();
    }
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
