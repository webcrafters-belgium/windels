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

function addCbcElement(DOMDocument $doc, DOMElement $parent, string $name, ?string $value, array $attributes = []): DOMElement {
    $element = $doc->createElement('cbc:' . $name);
    if ($value !== null && $value !== '') {
        $element->appendChild($doc->createTextNode($value));
    }
    foreach ($attributes as $attr => $attrValue) {
        $element->setAttribute($attr, $attrValue);
    }
    $parent->appendChild($element);
    return $element;
}

function normalizeCountryCode(?string $raw): string {
    $raw = trim((string)$raw);
    if ($raw === '') {
        return 'BE';
    }
    $map = [
        'belgië'      => 'BE', 'belgie'      => 'BE', 'belgium'    => 'BE',
        'nederland'   => 'NL', 'netherlands' => 'NL', 'nl'         => 'NL',
        'frankrijk'   => 'FR', 'france'      => 'FR', 'fr'         => 'FR',
        'duitsland'   => 'DE', 'germany'     => 'DE', 'de'         => 'DE',
        'luxemburg'   => 'LU', 'luxembourg'  => 'LU', 'lu'         => 'LU',
        'zwitserland' => 'CH', 'switzerland' => 'CH', 'ch'         => 'CH',
        'italië'      => 'IT', 'italie'      => 'IT', 'italy'      => 'IT', 'it' => 'IT',
        'spanje'      => 'ES', 'españa'      => 'ES', 'spain'      => 'ES', 'es' => 'ES',
        'polen'       => 'PL', 'poland'      => 'PL', 'pl'         => 'PL',
    ];
    $lower = mb_strtolower($raw);
    if (isset($map[$lower])) {
        return $map[$lower];
    }
    if (mb_strlen($raw) === 2) {
        return mb_strtoupper($raw);
    }
    return 'BE';
}

function formatXmlNumber(float $value): string {
    return number_format(round($value, 2), 2, '.', '');
}

function buildInvoiceXmlString(array $order, array $items): string {
    global $bedrijfsnaam, $bedrijfsadres, $bedrijfsBTWnr, $bedrijfsemail, $bedrijfstelefoon;

    $vatPercent = 21.0;
    $vatFactor = 1 + ($vatPercent / 100);

    $shippingIncl = max(0.0, (float)($order['shipping_cost'] ?? 0.0));
    $shippingExcl = $shippingIncl / $vatFactor;

    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->formatOutput = true;
    $invoice = $doc->createElement('Invoice');
    $doc->appendChild($invoice);
    $invoice->setAttribute('xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
    $invoice->setAttribute('xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
    $invoice->setAttribute('xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
    $invoice->setAttribute('xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
    $invoice->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
    $invoice->setAttribute('xsi:schemaLocation', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2 http://docs.oasis-open.org/ubl/os-UBL-2.1/xml/maindoc/UBL-Invoice-2.1.xsd');

    $issueDate = (new DateTimeImmutable($order['created_at'] ?? 'now'))->format('Y-m-d');
    $dueDate = (new DateTimeImmutable($order['created_at'] ?? 'now'))->modify('+14 days')->format('Y-m-d');
    $invoiceNumber = 'INV-' . str_pad((string)$order['id'], 6, '0', STR_PAD_LEFT);

    addCbcElement($doc, $invoice, 'UBLVersionID', '2.1');
    addCbcElement($doc, $invoice, 'CustomizationID', 'urn:cen.eu:en16931:2017');
    addCbcElement($doc, $invoice, 'ProfileID', 'urn:fdc:peppol.eu:2017:poacc:billing:01:1.0');
    addCbcElement($doc, $invoice, 'ID', $invoiceNumber);
    addCbcElement($doc, $invoice, 'IssueDate', $issueDate);
    addCbcElement($doc, $invoice, 'DocumentCurrencyCode', 'EUR');
    addCbcElement($doc, $invoice, 'InvoiceTypeCode', '380');

    $addressLines = array_filter(array_map('trim', preg_split('/\\r?\\n/', (string)$bedrijfsadres)));
    $companyStreet = $addressLines[0] ?? '';
    $companyPostalLine = $addressLines[1] ?? '';
    $companyCountryLine = $addressLines[2] ?? '';
    $companyPostalZone = '';
    $companyCity = $companyPostalLine;
    if (preg_match('/^(\\d{3,4})\\s*(.+)$/u', $companyPostalLine, $matches)) {
        $companyPostalZone = $matches[1];
        $companyCity = $matches[2];
    }
    $supplierCountryCode = normalizeCountryCode($companyCountryLine);

    $supplierParty = $doc->createElement('cac:AccountingSupplierParty');
    $supplier = $doc->createElement('cac:Party');
    addCbcElement($doc, $supplier, 'Name', $bedrijfsnaam);
    $supplierAddress = $doc->createElement('cac:PostalAddress');
    addCbcElement($doc, $supplierAddress, 'StreetName', $companyStreet);
    addCbcElement($doc, $supplierAddress, 'CityName', $companyCity);
    if ($companyPostalZone !== '') {
        addCbcElement($doc, $supplierAddress, 'PostalZone', $companyPostalZone);
    }
    $countryEl = $doc->createElement('cac:Country');
    addCbcElement($doc, $countryEl, 'IdentificationCode', $supplierCountryCode);
    $supplierAddress->appendChild($countryEl);
    $supplier->appendChild($supplierAddress);
    $supplierContact = $doc->createElement('cac:Contact');
    addCbcElement($doc, $supplierContact, 'Telephone', $bedrijfstelefoon);
    addCbcElement($doc, $supplierContact, 'ElectronicMail', $bedrijfsemail);
    $supplier->appendChild($supplierContact);
    $taxScheme = $doc->createElement('cac:PartyTaxScheme');
    addCbcElement($doc, $taxScheme, 'CompanyID', $bedrijfsBTWnr);
    $taxSchemeInner = $doc->createElement('cac:TaxScheme');
    addCbcElement($doc, $taxSchemeInner, 'ID', 'VAT');
    $taxScheme->appendChild($taxSchemeInner);
    $supplier->appendChild($taxScheme);
    $supplierParty->appendChild($supplier);
    $invoice->appendChild($supplierParty);

    $customerCountryCode = normalizeCountryCode($order['country']);
    $customerParty = $doc->createElement('cac:AccountingCustomerParty');
    $customer = $doc->createElement('cac:Party');
    addCbcElement($doc, $customer, 'Name', $order['name'] ?? '');
    $customerAddress = $doc->createElement('cac:PostalAddress');
    addCbcElement($doc, $customerAddress, 'StreetName', trim(($order['street'] ?? '') . ' ' . ($order['number'] ?? '')));
    addCbcElement($doc, $customerAddress, 'CityName', $order['city'] ?? '');
    addCbcElement($doc, $customerAddress, 'PostalZone', $order['zipcode'] ?? '');
    $countryCust = $doc->createElement('cac:Country');
    addCbcElement($doc, $countryCust, 'IdentificationCode', $customerCountryCode);
    $customerAddress->appendChild($countryCust);
    $customer->appendChild($customerAddress);
    $customerContact = $doc->createElement('cac:Contact');
    addCbcElement($doc, $customerContact, 'Telephone', $order['phone'] ?? '');
    addCbcElement($doc, $customerContact, 'ElectronicMail', $order['email'] ?? '');
    $customer->appendChild($customerContact);
    $customerParty->appendChild($customer);
    $invoice->appendChild($customerParty);

    $paymentMeans = $doc->createElement('cac:PaymentMeans');
    $paymentFields = [
        'bancontact'       => '42',
        'card'             => '42',
        'bancontactmrcash' => '42',
        'ideal'            => '42',
        'paypal'           => '42',
        'banktransfer'     => '31',
        'overschrijving'   => '31',
        'transfer'         => '31',
        'stripe'           => '42',
    ];
    $methodKey = strtolower(str_replace(' ', '', (string)($order['payment_method'] ?? '')));
    $paymentCode = $paymentFields[$methodKey] ?? '31';
    addCbcElement($doc, $paymentMeans, 'PaymentMeansCode', $paymentCode);
    $invoice->appendChild($paymentMeans);

    $itemsExcl = 0.0;
    $itemsIncl = 0.0;
    $lineIndex = 0;
    foreach ($items as $item) {
        $lineIndex++;
        $quantity = max(1, (int)($item['quantity'] ?? 1));
        $lineIncl = (float)($item['total_price'] ?? 0.0);
        $lineExcl = $lineIncl / $vatFactor;
        $lineTax = $lineIncl - $lineExcl;
        $unitExcl = $quantity > 0 ? $lineExcl / $quantity : $lineExcl;
        $description = $item['product_name'] ?? 'Product';

        $invoice->appendChild(createInvoiceLine($doc, $lineIndex, $quantity, $lineExcl, $lineTax, $unitExcl, $description, $vatPercent));
        $itemsExcl += $lineExcl;
        $itemsIncl += $lineIncl;
    }

    if ($shippingIncl > 0) {
        $lineIndex++;
        $lineTax = $shippingIncl - $shippingExcl;
        $invoice->appendChild(createInvoiceLine($doc, $lineIndex, 1, $shippingExcl, $lineTax, $shippingExcl, 'Verzendkosten', $vatPercent));
        $itemsExcl += $shippingExcl;
        $itemsIncl += $shippingIncl;
    }

    $totalExcl = $itemsExcl;
    $totalIncl = $itemsIncl;
    $invoiceTax = $totalIncl - $totalExcl;

    $taxTotal = $doc->createElement('cac:TaxTotal');
    addCbcElement($doc, $taxTotal, 'TaxAmount', formatXmlNumber($invoiceTax), ['currencyID' => 'EUR']);

    $taxSubtotal = $doc->createElement('cac:TaxSubtotal');
    addCbcElement($doc, $taxSubtotal, 'TaxableAmount', formatXmlNumber($totalExcl), ['currencyID' => 'EUR']);
    addCbcElement($doc, $taxSubtotal, 'TaxAmount', formatXmlNumber($invoiceTax), ['currencyID' => 'EUR']);
    $taxCategory = $doc->createElement('cac:TaxCategory');
    addCbcElement($doc, $taxCategory, 'ID', 'S');
    addCbcElement($doc, $taxCategory, 'Percent', (string)$vatPercent);
    $taxSchemeTotal = $doc->createElement('cac:TaxScheme');
    addCbcElement($doc, $taxSchemeTotal, 'ID', 'VAT');
    $taxCategory->appendChild($taxSchemeTotal);
    $taxSubtotal->appendChild($taxCategory);
    $taxTotal->appendChild($taxSubtotal);
    $invoice->appendChild($taxTotal);

    $legalMonetary = $doc->createElement('cac:LegalMonetaryTotal');
    addCbcElement($doc, $legalMonetary, 'LineExtensionAmount', formatXmlNumber($totalExcl), ['currencyID' => 'EUR']);
    addCbcElement($doc, $legalMonetary, 'TaxExclusiveAmount', formatXmlNumber($totalExcl), ['currencyID' => 'EUR']);
    addCbcElement($doc, $legalMonetary, 'TaxInclusiveAmount', formatXmlNumber($totalIncl), ['currencyID' => 'EUR']);
    addCbcElement($doc, $legalMonetary, 'PayableAmount', formatXmlNumber($totalIncl), ['currencyID' => 'EUR']);
    $invoice->appendChild($legalMonetary);

    $paymentTerms = $doc->createElement('cac:PaymentTerms');
    addCbcElement($doc, $paymentTerms, 'Note', 'Vervaldatum: ' . $dueDate);
    addCbcElement($doc, $paymentTerms, 'PaymentDueDate', $dueDate);
    $invoice->appendChild($paymentTerms);

    return $doc->saveXML();
}

function createInvoiceLine(DOMDocument $doc, int $lineIndex, int $quantity, float $lineExcl, float $lineTax, float $unitExcl, string $description, float $vatPercent): DOMElement {
    $line = $doc->createElement('cac:InvoiceLine');
    addCbcElement($doc, $line, 'ID', (string)$lineIndex);
    addCbcElement($doc, $line, 'InvoicedQuantity', (string)$quantity, ['unitCode' => 'EA']);
    addCbcElement($doc, $line, 'LineExtensionAmount', formatXmlNumber($lineExcl), ['currencyID' => 'EUR']);

    $lineTaxTotal = $doc->createElement('cac:TaxTotal');
    addCbcElement($doc, $lineTaxTotal, 'TaxAmount', formatXmlNumber($lineTax), ['currencyID' => 'EUR']);
    $taxSubtotal = $doc->createElement('cac:TaxSubtotal');
    addCbcElement($doc, $taxSubtotal, 'TaxableAmount', formatXmlNumber($lineExcl), ['currencyID' => 'EUR']);
    addCbcElement($doc, $taxSubtotal, 'TaxAmount', formatXmlNumber($lineTax), ['currencyID' => 'EUR']);
    $taxCategory = $doc->createElement('cac:TaxCategory');
    addCbcElement($doc, $taxCategory, 'ID', 'S');
    addCbcElement($doc, $taxCategory, 'Percent', (string)$vatPercent);
    $taxScheme = $doc->createElement('cac:TaxScheme');
    addCbcElement($doc, $taxScheme, 'ID', 'VAT');
    $taxCategory->appendChild($taxScheme);
    $taxSubtotal->appendChild($taxCategory);
    $lineTaxTotal->appendChild($taxSubtotal);
    $line->appendChild($lineTaxTotal);

    $item = $doc->createElement('cac:Item');
    addCbcElement($doc, $item, 'Name', $description);
    $line->appendChild($item);

    $price = $doc->createElement('cac:Price');
    addCbcElement($doc, $price, 'PriceAmount', formatXmlNumber($unitExcl), ['currencyID' => 'EUR']);
    $line->appendChild($price);

    return $line;
}

