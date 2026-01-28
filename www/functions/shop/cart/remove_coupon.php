<?php
// FILE: /functions/shop/cart/remove_coupon.php
session_start();
header('Content-Type: application/json');

// Coupon verwijderen uit sessie
unset($_SESSION['applied_coupon']);

echo json_encode([
    'success' => true,
    'message' => 'Coupon verwijderd'
]);
