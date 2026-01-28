<?php
// FILE: /admin/pages/orders/pdf_packing.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/pages/orders/_pdf_lib.php';

if (realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    $orderId = orderIdFromRequest();
    if ($orderId <= 0) {
        http_response_code(400);
        exit('Ongeldig order-ID');
    }

    try {
        global $conn;
        [$order, $items] = fetchOrderAndItems($conn, $orderId);
        $pdfString = buildPackingPdfString($order, $items);

        while (ob_get_level() > 0) { ob_end_clean(); }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="pakbon_' . (int)$orderId . '.pdf"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        echo $pdfString;
        exit;
    } catch (RuntimeException $e) {
        http_response_code(404);
        exit($e->getMessage());
    }
}
// Bij include: geen output.
