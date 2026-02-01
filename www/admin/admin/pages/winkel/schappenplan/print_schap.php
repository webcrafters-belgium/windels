<?php

require $_SERVER["DOCUMENT_ROOT"] . '/lib/fpdf/fpdf.php'; // Voeg de FPDF-bibliotheek toe
require $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';

// Foutmeldingen inschakelen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class PDF extends FPDF
{
    // Footer functie toevoegen
    function Footer()
    {
        // Positie 1.5 cm vanaf de onderkant
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        // Paginanummer toevoegen
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . ' van {nb}', 0, 0, 'C');
    }
}

$bedrijfstitel = "Windels Green & Deco Resin"; // Voeg je bedrijfstitel hier toe

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Controleer of de ID geldig is
if (!$id) {
    die("Ongeldige Schap ID.");
}

// Haal schapgegevens op
$stmt = $pdo_winkel->prepare("SELECT * FROM winkel_schappen WHERE id = ?");
if (!$stmt->execute([$id])) {
    die("Fout bij het ophalen van schapgegevens: " . implode(", ", $stmt->errorInfo()));
}
$schap = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$schap) {
    die("Geen schap gevonden met ID: " . $id);
}

// Haal producten op voor dit schap
function fetchProductsForSchap($pdo_winkel, $schap_id) {
    $stmt = $pdo_winkel->prepare("SELECT * FROM product_schap WHERE schap_id = ?");
    if (!$stmt->execute([$schap_id])) {
        die("Fout bij het ophalen van productgegevens: " . implode(", ", $stmt->errorInfo()));
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Haal productinformatie op uit de juiste tabel
function getProductInfo($pdo_winkel, $product_id, $product_type) {
    switch ($product_type) {
        case 'Epoxy':
            $stmt = $pdo_winkel->prepare("SELECT title, sku FROM epoxy_products WHERE id = ?");
            break;
        case 'Kaarsen':
            $stmt = $pdo_winkel->prepare("SELECT title, sku FROM kaarsen_products WHERE id = ?");
            break;
        case 'Vers':
            $stmt = $pdo_winkel->prepare("SELECT title, sku FROM vers_products WHERE id = ?");
            break;
            case 'inkkop':
                $stmt = $pdo_winkel->prepare("SELECT title, sku FROM inkoop_products WHERE id = ?");
                break;
        default:
            return null; // Ongeldig producttype
    }
    
    if (!$stmt->execute([$product_id])) {
        die("Fout bij het ophalen van productgegevens: " . implode(", ", $stmt->errorInfo()));
    }
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$producten = fetchProductsForSchap($pdo_winkel, $id);

// Controleer of er producten zijn
if (empty($producten)) {
    echo "Geen producten gevonden voor schap ID: " . $id;
    exit;  // Verlaat het script als er geen producten zijn
}

// Maak een nieuwe PDF-pagina aan
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages(); // Voorbereiden op het tonen van het totale aantal pagina's
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10); // Stel marges in om alles op één pagina te passen
$pdf->SetFont('Arial', 'B', 16);

// Voeg de bedrijfstitel toe
$pdf->Cell(0, 8, $bedrijfstitel, 0, 1, 'C');
$pdf->Ln(2); // Kleine ruimte na de bedrijfstitel

// Voeg de header en titel toe
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 8, 'Schapweergave', 0, 1, 'C');
$pdf->Ln(2); // Kleine ruimte na de titel

// Voeg schapnaam en locatie toe
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, 'Schapnaam: ' . $schap['naam'], 0, 1, 'L'); // Schapnaam
$pdf->Cell(0, 8, 'Locatie: ' . $schap['locatie'], 0, 1, 'L'); // Locatie
$pdf->Ln(5); // Extra ruimte na schapnaam en locatie

// Instellingen voor de schappen en producten
$pdf->SetFont('Arial', '', 10);

$aantal_planken = (int)$schap['aantal_planken']; // Aantal planken in het schap
$plank_hoogte = 4; // Plankdikte in cm
$bovenruimte = 14; // Ruimte tussen bovenkant schap en eerste plank
$onderruimte = 16; // Ruimte tussen laatste plank en onderkant schap
$beschikbare_hoogte = $schap['hoogte'] - $bovenruimte - $onderruimte; // Hoogte beschikbaar voor planken
$ruimte_tussen_planken = ($beschikbare_hoogte - ($aantal_planken * $plank_hoogte)) / ($aantal_planken - 1); // Ruimte tussen de planken

// Schaalfactor om alles netjes op één pagina te passen, met aangepaste hoogte
$max_breedte = 180; // Max breedte in mm
$max_hoogte = 210; // Max hoogte in mm, beperkt om ruimte voor voetnoot te houden
$scale_factor = min($max_breedte / ($schap['breedte'] * 10), $max_hoogte / ($schap['hoogte'] * 10)); // Zorg dat alles past

// Bereken geschaalde schap dimensies
$schap_breedte = $schap['breedte'] * 10 * $scale_factor; // Geschaalde breedte
$schap_hoogte = $schap['hoogte'] * 10 * $scale_factor; // Geschaalde hoogte

// Positie van het schap in de PDF
$start_x = 40;
$start_y = 50; // Begin iets lager om ruimte te laten voor schapnaam en locatie

$pdf->SetDrawColor(0, 0, 0); // Zwarte kleur voor de container
$pdf->Rect($start_x, $start_y, $schap_breedte, $schap_hoogte); // Teken het schap als een rechthoek

// Teken de planken en producten binnen het schap
for ($i = 0; $i < $aantal_planken; $i++) {
    // Bereken de juiste Y-positie om planken van onder naar boven te tekenen
    $top_positie = $onderruimte + ($aantal_planken - 1 - $i) * ($ruimte_tussen_planken + $plank_hoogte); // Omgekeerde berekening
    $y = $start_y + (($top_positie / $schap['hoogte']) * $schap_hoogte); // Geschaalde Y-positie berekenen

    // Bereken de omgekeerde cm-waarde
    $cm_waarde = $bovenruimte + ($i * ($ruimte_tussen_planken + $plank_hoogte));

    // Teken een lijn voor elke plank
    $pdf->SetLineWidth(0.5); // Dikkere lijn voor planken
    $pdf->Line($start_x, $y, $start_x + $schap_breedte, $y);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Text($start_x - 15, $y - 2, round($cm_waarde, 1) . " cm"); // Omgekeerde hoogte label links van het schap

    // Controleer voor producten op deze plank
    foreach ($producten as $product) {
        if ($product['plank_nummer'] == ($i + 1)) {  // Planknummer begint bij 1
            // Identificeer het producttype en ID
            if (!empty($product['epoxy_product_id'])) {
                $product_id = $product['epoxy_product_id'];
                $product_type = 'Epoxy';
            } elseif (!empty($product['kaarsen_product_id'])) {
                $product_id = $product['kaarsen_product_id'];
                $product_type = 'Kaarsen';
            } elseif (!empty($product['vers_product_id'])) {
                $product_id = $product['vers_product_id'];
                $product_type = 'Vers';
            } elseif (!empty($product['inkoop_product_id'])) {
                $product_id = $product['inkoop_product_id'];
                $product_type = 'inkoop';
            } else {
                continue; // Geen product gekoppeld
            }

            // Haal productinformatie op
            $product_info = getProductInfo($pdo_winkel, $product_id, $product_type);
            if ($product_info) {
                $product_title = $product_info['title'];
                $product_sku = $product_info['sku'];
            } else {
                $product_title = 'Onbekend';
                $product_sku = 'Onbekend';
            }

            // Bereken de positie van het product
            $positieX = $start_x + ($product['positie_op_plank'] * 10 * $scale_factor); // Geschaalde X-positie in mm
            $product_breedte = 140 * $scale_factor; // Geschaalde breedte van product in mm
            $product_hoogte = 100 * $scale_factor; // Geschaalde hoogte van product in mm

            // Teken het product als een rechthoek
            $pdf->Rect($positieX, $y - $product_hoogte, $product_breedte, $product_hoogte, 'D'); 
            
            // Plaats het productlabel binnen de rechthoek
            $pdf->SetFont('Arial', '', 6); // Kleinere tekst voor producten
            $text_x = $positieX + 0; // Kleine marge aan de linkerkant binnen de rechthoek
            $text_y = $y - $product_hoogte + 1; // Bovenkant van de rechthoek + marge voor tekst
            $pdf->SetXY($text_x, $text_y);
            $pdf->MultiCell($product_breedte - 2, 2, $product_title . "\n(SKU: " . $product_sku . ")", 0, 'C'); // Multicell om tekst netjes af te breken
        }
    }
}

// Voeg een voetnoot toe over plankhoogte
$pdf->Ln(30); // Extra ruimte
$pdf->SetFont('Arial', 'I', 8);
$pdf->MultiCell(0, 2, 'Plankhoogte kan verschillen per product. Controleer de plaatsing zorgvuldig voor een correcte uitlijning. Bekijk of een plank recht of schuin moet zijn.', 0, 'C');

// Voeg een nieuwe pagina toe voor de productlijst
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10); // Stel marges in om alles op één pagina te passen
$pdf->SetFont('Arial', 'B', 16);

// Voeg de bedrijfstitel toe
$pdf->Cell(0, 10, $bedrijfstitel, 0, 1, 'C');
$pdf->Ln(5); // Kleine ruimte na de bedrijfstitel

// Voeg de titel van de productlijst toe
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Productlijst voor Schap: ' . $schap['naam'], 0, 1, 'C');
$pdf->Ln(10); // Kleine ruimte na de titel

// Sorteer producten van links naar rechts en van onder naar boven
usort($producten, function ($a, $b) {
    if ($a['plank_nummer'] == $b['plank_nummer']) {
        return $a['positie_op_plank'] - $b['positie_op_plank'];
    }
    return $a['plank_nummer'] - $b['plank_nummer'];
});

// Tabelkop toevoegen
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 10, 'Product Title', 1);
$pdf->Cell(30, 10, 'SKU', 1);
$pdf->Cell(30, 10, 'Plank Nummer', 1);
$pdf->Cell(30, 10, 'Positie', 1);
$pdf->Ln();

// Productlijst weergeven
$pdf->SetFont('Arial', '', 10);
foreach ($producten as $product) {
    // Haal productinformatie op
    if (!empty($product['epoxy_product_id'])) {
        $product_info = getProductInfo($pdo_winkel, $product['epoxy_product_id'], 'Epoxy');
    } elseif (!empty($product['kaarsen_product_id'])) {
        $product_info = getProductInfo($pdo_winkel, $product['kaarsen_product_id'], 'Kaarsen');
    } elseif (!empty($product['vers_product_id'])) {
        $product_info = getProductInfo($pdo_winkel, $product['vers_product_id'], 'Vers');
    } elseif (!empty($product['inkoop_product_id'])) {
        $product_info = getProductInfo($pdo_winkel, $product['inkoop_product_id'], 'inkoop');
    } else {
        continue; // Geen product gekoppeld
    }

    if ($product_info) {
        $product_title = $product_info['title'];
        $product_sku = $product_info['sku'];
    } else {
        $product_title = 'Onbekend';
        $product_sku = 'Onbekend';
    }

    // Tabelrij toevoegen
    $pdf->Cell(40, 10, $product_title, 1);
    $pdf->Cell(30, 10, $product_sku, 1);
    $pdf->Cell(30, 10, $product['plank_nummer'], 1);
    $pdf->Cell(30, 10, $product['positie_op_plank'], 1);
    $pdf->Ln();
}

// Output de PDF
$pdf->Output('Schapweergave.pdf', 'I'); // Direct tonen in de browser
?>
