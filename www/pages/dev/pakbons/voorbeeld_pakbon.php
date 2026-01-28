<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'].'/ini.inc';
require $_SERVER['DOCUMENT_ROOT'].'/lib/fpdf/fpdf.php';

$logoPng = $_SERVER['DOCUMENT_ROOT'] . '/images/windels-logo.png';

// Voorbeelddata
$orderId = 9999;
$products = [
    ['name' => 'Epoxy Onderzetters - Set van 4', 'quantity' => 2],
    ['name' => 'Siliconen Mal - Hexagon',        'quantity' => 1],
    ['name' => 'Pigmentpoeder Goud',             'quantity' => 3],
];

$klant = [
    'naam'    => 'Jan Jansen',
    'straat'  => 'Kerkstraat 12',
    'postcode'=> '3930',
    'stad'    => 'Hamont-Achel',
    'land'    => 'België',
    'email'   => 'jan.jansen@example.com',
];

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',11);

// ───────────────────────────────
// Logo & bedrijfsinfo
// ───────────────────────────────
if (file_exists($logoPng)) {
    $pdf->Image($logoPng, 10, 10, 50);
}
$pdf->SetXY(130, 10);
$pdf->SetFont('Arial', '', 10);
$bedrijfstekst = "$bedrijfsnaam\n$bedrijfsadres\nTel: $bedrijfstelefoon\nE-mail: $bedrijfsemail";
$pdf->MultiCell(70, 5, iconv("UTF-8", "Windows-1252//TRANSLIT", $bedrijfstekst), 0, 'R');

$pdf->Ln(30);

// ───────────────────────────────
// Titel: Pakbon
// ───────────────────────────────
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Pakbon Order #$orderId",0,1);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,'Datum: '.date('d-m-Y'),0,1);

$pdf->Ln(8);

// ───────────────────────────────
// Klantgegevens
// ───────────────────────────────
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,'Verzendgegevens:',0,1);
$pdf->SetFont('Arial','',11);
$klantblok = $klant['naam']."\n"
    . $klant['straat']."\n"
    . $klant['postcode']." ".$klant['stad']."\n"
    . $klant['land']."\n"
    . $klant['email'];
$pdf->MultiCell(0, 6, iconv("UTF-8", "Windows-1252//TRANSLIT", $klantblok));
$pdf->Ln(10);

// ───────────────────────────────
// Productentabel zonder prijzen
// ───────────────────────────────
$pdf->SetFont('Arial','B',12);
$pdf->Cell(160,8,'Product',1);
$pdf->Cell(30,8,'Aantal',1,1,'C');

$pdf->SetFont('Arial','',12);
foreach ($products as $p) {
    $pdf->Cell(160,8,iconv("UTF-8", "Windows-1252//TRANSLIT", $p['name']),1);
    $pdf->Cell(30,8,$p['quantity'],1,1,'C');
}

// ───────────────────────────────
// Footer
// ───────────────────────────────
$pdf->SetY(-30);
$pdf->SetFont('Arial','I',8);
$footerText = "Pakbon gegenereerd via windelsgreen-decoresin.com op ".date('d-m-Y');
$pdf->Cell(0,10,iconv("UTF-8", "Windows-1252//TRANSLIT", $footerText),0,1,'C');

ob_end_clean();
$pdf->Output('I', 'Voorbeeld_Pakbon.pdf');
