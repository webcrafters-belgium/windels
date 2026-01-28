<?php
// Admin Configuration
// DO NOT hardcode credentials - use existing ini.inc

require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Admin settings
define('ADMIN_PATH', '/admin_new');
define('ADMIN_TITLE', 'Windels Green Admin');
define('ITEMS_PER_PAGE', 20);

// Verify admin session
function requireAdmin() {
    if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: /pages/account/login?referer=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

// Log admin activity
function logAdminActivity($conn, $userId, $actionType, $entityType, $entityId, $description) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt = $conn->prepare(
        "INSERT INTO admin_activity_log (user_id, action_type, entity_type, entity_id, description, ip_address) 
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('ississ', $userId, $actionType, $entityType, $entityId, $description, $ip);
    $stmt->execute();
    $stmt->close();
}

// Get current admin user
function getCurrentUser() {
    return [
        'id' => $_SESSION['user']['id'] ?? 0,
        'name' => $_SESSION['user']['name'] ?? 'Admin',
        'email' => $_SESSION['user']['email'] ?? '',
        'role' => $_SESSION['user']['role'] ?? 'user'
    ];
}

// CSRF Protection
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>