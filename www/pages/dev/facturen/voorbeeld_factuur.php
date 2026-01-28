<?php
ob_start();
require $_SERVER['DOCUMENT_ROOT'].'/ini.inc';
require $_SERVER['DOCUMENT_ROOT'].'/lib/fpdf/fpdf.php';

$logoPng = $_SERVER['DOCUMENT_ROOT'] . '/images/windels-logo.png';

$orderId = 9999;
$totalPrice = 123.45;
$shippingCost = 6.95;
$products = [
    ['name' => 'Epoxy Onderzetters - Set van 4', 'quantity' => 2, 'price' => 14.95],
    ['name' => 'Siliconen Mal - Hexagon',        'quantity' => 1, 'price' => 9.99],
    ['name' => 'Pigmentpoeder Goud',             'quantity' => 3, 'price' => 4.50],
];

// Klantgegevens (testdata)
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
$bedrijfstekst = "$bedrijfsnaam\n$bedrijfsadres\nBTW: $bedrijfsBTWnr\nTel: $bedrijfstelefoon\nE-mail: $bedrijfsemail";
$pdf->MultiCell(70, 5, iconv("UTF-8", "Windows-1252//TRANSLIT", $bedrijfstekst), 0, 'R');

$pdf->Ln(30);

// ───────────────────────────────
// Factuurgegevens
// ───────────────────────────────
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Factuur #$orderId",0,1);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,8,'Datum: '.date('d-m-Y'),0,1);
$pdf->Cell(0,8,'Totaal bedrag: '.chr(128).number_format($totalPrice,2,',','.'),0,1);
$pdf->Cell(0,8,'Verzendkosten: '.chr(128).number_format($shippingCost,2,',','.'),0,1);

$pdf->Ln(10);

// ───────────────────────────────
// Klantgegevens
// ───────────────────────────────
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,'Klantgegevens:',0,1);
$pdf->SetFont('Arial','',11);
$klantblok = $klant['naam']."\n"
    . $klant['straat']."\n"
    . $klant['postcode']." ".$klant['stad']."\n"
    . $klant['land']."\n"
    . $klant['email'];

$pdf->MultiCell(0, 6, iconv("UTF-8", "Windows-1252//TRANSLIT", $klantblok));
$pdf->Ln(8);

// ───────────────────────────────
// Productentabel
// ───────────────────────────────
$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,8,'Product',1);
$pdf->Cell(30,8,'Aantal',1,0,'C');
$pdf->Cell(40,8,'Prijs per stuk',1,1,'R');

$pdf->SetFont('Arial','',12);
foreach ($products as $p) {
    $pdf->Cell(100,8,iconv("UTF-8", "Windows-1252//TRANSLIT", $p['name']),1);
    $pdf->Cell(30,8,$p['quantity'],1,0,'C');
    $prijs = chr(128) . number_format($p['price'],2,',','.');
    $pdf->Cell(40,8,$prijs,1,1,'R');
}

$pdf->Ln(5);
$pdf->Cell(130);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,8,'Totaal:',0);
$pdf->Cell(30,8,chr(128).number_format($totalPrice,2,',','.'),0,1,'R');

// ───────────────────────────────
// Footer
// ───────────────────────────────
$pdf->SetY(-30);
$pdf->SetFont('Arial','I',8);
$footerText = "Factuur gegenereerd via windelsgreen-decoresin.com op ".date('d-m-Y');
$pdf->Cell(0,10,iconv("UTF-8", "Windows-1252//TRANSLIT", $footerText),0,1,'C');

ob_end_clean();
$pdf->Output('I', 'Voorbeeld_Factuur.pdf');
