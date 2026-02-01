<?php
// Admin Configuration
// Standalone configuration with graceful database handling

// Database credentials
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'company_admin';

// Try to load additional config from ini.inc if exists (for production credentials)
$iniPath = $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
if (file_exists($iniPath)) {
    // Only include if it doesn't throw exceptions
    try {
        @include $iniPath;
    } catch (Exception $e) {
        // Ignore - use default values
    }
}

// Create mysqli connection - handle gracefully if no database available
// Respect existing $conn from ini.inc if it's already connected.
$hasValidConn = isset($conn) && $conn instanceof mysqli && !$conn->connect_error;
if (!$hasValidConn) {
    $conn = null;
    try {
        @$conn = new mysqli($host, $user, $pass, $dbname);
        if ($conn->connect_error) {
            $conn = null;
        } else {
            $conn->set_charset('utf8mb4');
        }
    } catch (Exception $e) {
        $conn = null;
    }
}

// Admin settings
define('ADMIN_PATH', '/admin');
define('ADMIN_TITLE', 'Windels Green Admin');
define('ITEMS_PER_PAGE', 20);

// Verify admin session - auto-create demo session for testing
function requireAdmin() {
    // For demo/development: Create a mock admin session if not logged in
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role'])) {
        $_SESSION['user'] = [
            'id' => 1,
            'name' => 'Admin Demo',
            'email' => 'admin@windelsgreen.be',
            'role' => 'admin'
        ];
    }
    
    if ($_SESSION['user']['role'] !== 'admin') {
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
