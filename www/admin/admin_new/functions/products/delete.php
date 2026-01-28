<?php
session_start();
require_once '../../config.php';

header('Content-Type: application/json');

try {
    requireAdmin();
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['csrf_token']) || !validateCSRFToken($data['csrf_token'])) {
        throw new Exception('Ongeldige CSRF token');
    }
    
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        throw new Exception('Ongeldig product ID');
    }
    
    $productId = (int)$data['id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    // Delete product (CASCADE will handle related records)
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $productId);
    
    if (!$stmt->execute()) {
        throw new Exception('Fout bij verwijderen product');
    }
    
    $stmt->close();
    
    // Log activity
    logAdminActivity(
        $conn,
        getCurrentUser()['id'],
        'delete',
        'product',
        $productId,
        "Product verwijderd (ID: $productId)"
    );
    
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Product succesvol verwijderd'
    ]);
    
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