<?php
require($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');
require($_SERVER['DOCUMENT_ROOT'] . '/lib/fpdf/fpdf.php');

// 👇 Order-ID of Session-ID ophalen
$orderId = $_GET['order_id'] ?? null;
$sessionId = $_GET['session_id'] ?? null;

if (!$orderId && !$sessionId) {
    exit('Geen order_id of session_id opgegeven');
}

$useSession = !$orderId;

// 📦 Ordergegevens ophalen indien aanwezig
$order = null;
if ($orderId) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    if (!$order) exit('Bestelling niet gevonden');
}

// 🛒 Producten ophalen op basis van order_id of session_id
if ($useSession) {
    $stmt = $conn->prepare("
        SELECT 
            oi.quantity, 
            oi.total_price, 
            p.name, 
            p.sku 
        FROM order_items oi
        LEFT JOIN products p ON p.id = oi.product_id
        WHERE oi.session_id = ?
        ORDER BY oi.id DESC
    ");
    $stmt->bind_param("s", $sessionId);
} else {
    $stmt = $conn->prepare("
        SELECT 
            oi.quantity, 
            oi.total_price, 
            p.name, 
            p.sku 
        FROM order_items oi
        LEFT JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = ?
        ORDER BY oi.id DESC
    ");
    $stmt->bind_param("i", $orderId);
}
$stmt->execute();
$items = $stmt->get_result();

// 📄 PDF
class PDF extends FPDF {
    function Header() {
        $this->Image($_SERVER['DOCUMENT_ROOT'].'/images/windels-logo.png',10,6,30);
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'PAKBON',0,1,'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Windels Green & Deco Resin • ' . date('d-m-Y'),0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

// 🧍 Klantinformatie (indien order aanwezig)
if ($order) {
    $pdf->Cell(0,10,"Ordernummer: #{$order['id']}",0,1);
    $pdf->Cell(0,10,"Naam: " . iconv('UTF-8', 'windows-1252', $order['name']),0,1);
    $pdf->Cell(0,10,"Adres: " . iconv('UTF-8', 'windows-1252', "{$order['street']} {$order['number']}"),0,1);
    $pdf->Cell(0,10,"Plaats: " . iconv('UTF-8', 'windows-1252', "{$order['zipcode']} {$order['city']}"),0,1);
    $pdf->Cell(0,10,"Land: " . iconv('UTF-8', 'windows-1252', $order['country']),0,1);
    $pdf->Cell(0,10,"Telefoon: {$order['phone']}",0,1);
    $pdf->Cell(0,10,"E-mail: {$order['email']}",0,1);
    $pdf->Cell(0,10,"Datum: " . date('d-m-Y H:i', strtotime($order['created_at'])),0,1);
    $pdf->Cell(0,10,"Verzendmethode: " . iconv('UTF-8', 'windows-1252', $order['shipping_method']),0,1);
    $pdf->Ln(5);
} else {
    $pdf->Cell(0,10,"Conceptpakbon voor sessie: {$sessionId}",0,1);
    $pdf->Ln(5);
}

// 🧾 Tabelkop
$pdf->SetFont('Arial','B',11);
$pdf->Cell(90,10,'Product',1);
$pdf->Cell(30,10,'Aantal',1);
$pdf->Cell(70,10,'SKU',1);
$pdf->Ln();

// 📄 Regels
$pdf->SetFont('Arial','',11);
while ($item = $items->fetch_assoc()) {
    $productName = $item['name'] ?? 'Onbekend';
    $quantity = $item['quantity'] ?? 0;
    $sku = $item['sku'] ?? '-';

    $pdf->Cell(90,10, iconv('UTF-8', 'windows-1252', $productName),1);
    $pdf->Cell(30,10, $quantity,1,0,'C');
    $pdf->Cell(70,10, $sku,1);
    $pdf->Ln();
}

// 🧮 Totaal (enkel bij bestelling)
if ($order) {
    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10, "Verzendkosten: " . iconv('UTF-8', 'windows-1252', '€') . number_format($order['shipping_cost'], 2, ',', '.'), 0, 1);
    $pdf->Cell(0,10, "Totaalwaarde: " . iconv('UTF-8', 'windows-1252', '€') . number_format($order['total_price'], 2, ',', '.'), 0, 1);

}

// 📤 PDF tonen
$filename = $order ? "Pakbon_Order_{$order['id']}.pdf" : "Pakbon_Sessie_{$sessionId}.pdf";
$pdf->Output('I', $filename);
