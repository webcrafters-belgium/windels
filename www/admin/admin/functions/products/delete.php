<?php
header('Content-Type: application/json');

// Start session
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

if (!$configLoaded || !isset($conn)) {
    echo json_encode(['success' => false, 'message' => 'Configuratie niet gevonden']);
    exit;
}

// Get JSON input for AJAX requests
$input = json_decode(file_get_contents('php://input'), true);
$productId = isset($input['id']) ? (int)$input['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Ongeldig product ID']);
    exit;
}

try {
    // Delete product materials
    $conn->query("DELETE FROM product_materials WHERE product_id = $productId");
    
    // Delete product categories
    $conn->query("DELETE FROM product_categories WHERE product_id = $productId");
    
    // Delete product images (files and records)
    $images = $conn->query("SELECT image_path FROM product_images WHERE product_id = $productId");
    while ($img = $images->fetch_assoc()) {
        $filepath = $_SERVER['DOCUMENT_ROOT'] . $img['image_path'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
    $conn->query("DELETE FROM product_images WHERE product_id = $productId");
    
    // Get product name for logging
    $result = $conn->query("SELECT name FROM products WHERE id = $productId");
    $product = $result->fetch_assoc();
    $productName = $product['name'] ?? 'Onbekend';
    
    // Delete product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $productId);
    
    if ($stmt->execute()) {
        // Log activity
        $userId = getCurrentUser()['id'];
        logAdminActivity($conn, $userId, 'delete', 'product', $productId, "Product '$productName' verwijderd");
        
        echo json_encode(['success' => true, 'message' => 'Product verwijderd']);
    } else {
        throw new Exception($conn->error);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Fout bij verwijderen: ' . $e->getMessage()]);
}
