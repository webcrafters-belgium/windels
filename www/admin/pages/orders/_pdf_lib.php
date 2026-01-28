<?php
// FILE: /admin/pages/orders/_pdf_lib.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/fpdf/fpdf.php';

/**
 * Veilig orderId lezen uit GET/POST.
 */
function orderIdFromRequest(): int {
    return (int)($_GET['id'] ?? $_GET['order_id'] ?? $_POST['id'] ?? $_POST['order_id'] ?? 0);
}

/**
 * Haal order + items op.
 */
function fetchOrderAndItems(mysqli $conn, int $orderId): array {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$order) throw new RuntimeException('Order niet gevonden');

    $stmt = $conn->prepare("
        SELECT oi.*, p.name AS product_name
        FROM order_items oi
        LEFT JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = ?
    ");
    $stmt->bind_param('i', $orderId);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return [$order, $items];
}

/**
 * Genereer FACTUUR als PDF-string (geen directe output).
 * - Toont verzendkosten als aparte regel (incl. btw)
 * - Totalen worden berekend inclusief verzendkosten
 * - Print Track & Trace indien $order['barcode'] aanwezig is
 * @return string Binary PDF-content
 */
function buildInvoicePdfString(array $order, array $items): string {
    $btw_perc = 21;                         // Pas aan indien nodig
    $btw_mul  = 1 + ($btw_perc / 100);
    $ship_incl = (float)($order['shipping_cost'] ?? 0.0);
    $ship_excl = $ship_incl / $btw_mul;

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);

    // Titel
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetXY(10, 50);
    $pdf->Cell(190, 10, iconv("UTF-8", "Windows-1252//IGNORE", 'FACTUUR'), 0, 1, 'L');

    // Logo
    $logo = $_SERVER['DOCUMENT_ROOT'] . '/images/windels-logo.png';
    if (file_exists($logo)) $pdf->Image($logo, 10, 10, 40);

    // Klant
    $pdf->SetXY(10, 60);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(100, 6, 'Klant:', 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetX(10);
    $pdf->MultiCell(100, 5, iconv("UTF-8", "Windows-1252//IGNORE",
        ($order['name'] ?? '') . "\n" .
        ($order['street'] ?? '') . ' ' . ($order['number'] ?? '') . "\n" .
        ($order['zipcode'] ?? '') . ' ' . ($order['city'] ?? '') . "\n" .
        ($order['country'] ?? '')
    ));

    // Bedrijf + factuurinfo
    $pdf->SetXY(130, 10);
    $pdf->MultiCell(70, 5, iconv("UTF-8", "Windows-1252//IGNORE",
        "Windels Green & Deco resin\nBeukenlaan 8\n3930 Hamont-Achel\nBelgië\nBTW BE0803859883\nRPR 0803859883\nTel.: 011753319\ninfo@windelsgreen-decoresin.com\nhttps://windelsgreen-decoresin.com\n\nFACTUUR " . str_pad((string)$order['id'], 6, '0', STR_PAD_LEFT) .
        "\nDatum: " . date('d-m-Y', strtotime($order['created_at'])) .
        "\nVervaldatum: " . date('d-m-Y', strtotime("+14 days", strtotime($order['created_at']))) .
        "\nLeveringsdatum: " . date('d-m-Y', strtotime($order['created_at'])) .
        "\nKlantnummer: C" . str_pad((string)($order['user_id'] ?? 0), 5, '0', STR_PAD_LEFT)
    ), 0, 'R');

    // Tabel header
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(38, 204, 99);
    $pdf->SetTextColor(255);
    $pdf->Cell(80, 8, 'Omschrijving', 1, 0, 'L', true);
    $pdf->Cell(20, 8, 'Aantal', 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'Eenheidsprijs', 1, 0, 'R', true);
    $pdf->Cell(20, 8, 'BTW', 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'Totaal incl.', 1, 1, 'R', true);

    // Tabel inhoud
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(0);

    $items_excl = 0.0;
    $items_incl = 0.0;

    foreach ($items as $item) {
        $naam = iconv("UTF-8", "Windows-1252//IGNORE", $item['product_name'] ?? '');
        $incl = (float)($item['total_price'] ?? 0.0); // lijnbedrag incl.
        $qty  = max(1, (int)($item['quantity'] ?? 1));
        $unit_incl = $qty > 0 ? ($incl / $qty) : $incl;

        $excl = $incl / $btw_mul;
        $items_excl += $excl;
        $items_incl += $incl;

        $pdf->Cell(80, 8, $naam, 1);
        $pdf->Cell(20, 8, (string)$qty, 1, 0, 'C');
        $pdf->Cell(30, 8, chr(128) . number_format($unit_incl, 2, ',', '.'), 1, 0, 'R');
        $pdf->Cell(20, 8, $btw_perc.'%', 1, 0, 'C');
        $pdf->Cell(30, 8, chr(128) . number_format($incl, 2, ',', '.'), 1, 1, 'R');
    }

    // Verzendkosten als aparte regel (indien > 0)
    if ($ship_incl > 0) {
        $pdf->Cell(80, 8, iconv("UTF-8", "Windows-1252//IGNORE", 'Verzendkosten'), 1);
        $pdf->Cell(20, 8, '1', 1, 0, 'C');
        $pdf->Cell(30, 8, chr(128) . number_format($ship_incl, 2, ',', '.'), 1, 0, 'R');
        $pdf->Cell(20, 8, $btw_perc.'%', 1, 0, 'C');
        $pdf->Cell(30, 8, chr(128) . number_format($ship_incl, 2, ',', '.'), 1, 1, 'R');
    }

    // Totalen
    $totaal_excl = $items_excl + $ship_excl;
    $totaal_incl = $items_incl + $ship_incl;
    $btw_bedrag  = $totaal_incl - $totaal_excl;

    $pdf->Ln(5);
    $pdf->Cell(150, 6, 'Totaal excl.:', 0, 0, 'R');
    $pdf->Cell(30, 6, chr(128) . number_format($totaal_excl, 2, ',', '.'), 0, 1, 'R');

    $pdf->Cell(150, 6, $btw_perc . "% btw op " . chr(128) . number_format($totaal_excl, 2, ',', '.'), 0, 0, 'R');
    $pdf->Cell(30, 6, chr(128) . number_format($btw_bedrag, 2, ',', '.'), 0, 1, 'R');

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(150, 8, 'Totaal voldaan:', 0, 0, 'R');
    $pdf->Cell(30, 8, chr(128) . number_format($totaal_incl, 2, ',', '.'), 0, 1, 'R');

    // Optioneel: Track & Trace
    if (!empty($order['barcode'])) {
        $pdf->Ln(4);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, iconv("UTF-8", "Windows-1252//IGNORE", 'Track & Trace: ' . $order['barcode']), 0, 1, 'L');
    }

    $pdf->Ln(6);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 6, iconv("UTF-8", "Windows-1252//IGNORE",
        "Bedankt voor uw vertrouwen in Windels Green & Deco resin"));

    $pdf->SetY(-40);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 10, iconv("UTF-8", "Windows-1252//IGNORE", 'Algemene voorwaarden beschikbaar op aanvraag.'), 0, 1, 'C');

    return $pdf->Output('S');
}

/**
 * Genereer PAKBON als PDF-string (geen directe output).
 * @return string Binary PDF-content
 */
function buildPackingPdfString(array $order, array $items): string {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);

    $logo = $_SERVER['DOCUMENT_ROOT'] . '/images/windels-logo.png';
    if (file_exists($logo)) $pdf->Image($logo, 10, 10, 40);

    $pdf->SetXY(130, 10);
    $pdf->MultiCell(70, 5, iconv("UTF-8", "Windows-1252//IGNORE",
        "Windels Green & Deco resin\nBeukenlaan 8\n3930 Hamont-Achel\nBelgië\nTel.: 011753319\nhttps://windelsgreen-decoresin.com\n\nPAKBON #" . str_pad((string)$order['id'], 6, '0', STR_PAD_LEFT) .
        "\nDatum: " . date('d-m-Y', strtotime($order['created_at'])) .
        "\nOrdernummer: " . $order['id']
    ), 0, 'R');

    $pdf->SetXY(10, 50);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, iconv("UTF-8", "Windows-1252//IGNORE", 'PAKBON'), 0, 1, 'L');

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(100, 6, 'Verzenden naar:', 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(100, 5, iconv("UTF-8", "Windows-1252//IGNORE",
        trim(($order['name'] ?? '') . "\n" .
            ($order['street'] ?? '') . ' ' . ($order['number'] ?? '') . "\n" .
            ($order['zipcode'] ?? '') . ' ' . ($order['city'] ?? '') . "\n" .
            ($order['country'] ?? ''))));

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFillColor(38, 204, 99);
    $pdf->SetTextColor(255);
    $pdf->Cell(120, 8, 'Product', 1, 0, 'L', true);
    $pdf->Cell(30, 8, 'Aantal', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(0);

    foreach ($items as $item) {
        $naam = iconv("UTF-8", "Windows-1252//IGNORE", $item['product_name'] ?? '');
        $pdf->Cell(120, 8, $naam, 1);
        $pdf->Cell(30, 8, (string)$item['quantity'], 1, 1, 'C');
    }

    $pdf->Ln(10);
    $pdf->MultiCell(0, 6, iconv("UTF-8", "Windows-1252//IGNORE",
        "Bedankt voor je bestelling!\nControleer deze pakbon bij ontvangst."));

    $pdf->SetY(-40);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 10, iconv("UTF-8", "Windows-1252//IGNORE",
        'Pakbon zonder prijzen of btw. Niet geldig als factuur.'), 0, 1, 'C');

    return $pdf->Output('S');
}
