<?php
$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($orderId <= 0) {
    http_response_code(400);
    exit('Ongeldig order-ID.');
}

// Redirect naar bestaand script
header("Location: /admin/pages/orders/pdf_invoice.php?id=" . $orderId);
exit;
