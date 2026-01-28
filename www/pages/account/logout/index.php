<?php
// logout/producten.php

session_start();

$_SESSION = [];

unset($_SESSION['cart']);
unset($_SESSION['cart_items']);
$session_id = 0;

session_unset();
session_abort();
session_destroy();

// Voorkom caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

// ✅ Redirect naar de loginpagina
header("Location: /pages/account/login");
exit;

?>
