<?php
// FILE: /pages/account/logout/index.php
session_start();

// Wis sessiedata.
$_SESSION = [];
unset($_SESSION['cart'], $_SESSION['cart_items']);

// Verwijder session cookie.
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 3600,
        $params['path'] ?? '/',
        $params['domain'] ?? '',
        (bool)($params['secure'] ?? false),
        (bool)($params['httponly'] ?? false)
    );
}

session_unset();
session_destroy();

// Voorkom caching van sessie-afhankelijke pagina's.
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

header('Location: /pages/account/login/');
exit;
?>
