<?php
// FILE: /admin/pages/orders/xml_invoice.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/pages/orders/_pdf_lib.php';

use RuntimeException;

$orderId = orderIdFromRequest();
if ($orderId <= 0) {
    http_response_code(400);
    echo 'Ongeldig order-ID';
    exit;
}

try {
    global $conn;
    [$order, $items] = fetchOrderAndItems($conn, $orderId);
    $xmlString = buildInvoiceXmlString($order, $items);
    while (ob_get_level() > 0) { ob_end_clean(); }
    header('Content-Type: application/xml; charset=UTF-8');
    header('Content-Disposition: attachment; filename="factuur_' . (int)$orderId . '.xml"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    echo $xmlString;
    exit;
} catch (RuntimeException $e) {
    http_response_code(404);
    echo htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE);
    exit;
}
