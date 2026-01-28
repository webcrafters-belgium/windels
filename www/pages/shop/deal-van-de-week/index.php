<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$today = date('Y-m-d');

$sql = "
    SELECT 
        wd.*,
        p.name AS product_name,
        p.price AS originele_prijs,
        p.id AS product_id,
        img.image_path
    FROM weekly_deals wd
    JOIN products p ON wd.product_id = p.id
    LEFT JOIN product_images img 
        ON img.product_id = p.id AND img.is_main = 1
    WHERE 
        wd.start_date <= ? 
        AND wd.end_date >= ?
    ORDER BY wd.start_date DESC
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $today, $today);
$stmt->execute();
$result = $stmt->get_result();
$deal = $result->fetch_assoc();

if (!$deal) {
    echo "<div class='container my-5'><div class='alert alert-info'>Er is momenteel geen Deal van de Week.</div></div>";
    include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
    exit;
}

// Afbeelding bepalen
$image = $deal['image_path'];
if (empty($image) || !file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($image, '/'))) {
    $image = '/images/products/placeholder.png';
}

// Actieprijs berekenen (altijd 10% korting)
$origineel  = (float)$deal['originele_prijs'];
$actieprijs = round($origineel * 0.9, 2);
?>
<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($image); ?>" alt="<?= htmlspecialchars($deal['product_name']); ?>" class="img-fluid rounded shadow">
        </div>
        <div class="col-md-6">
            <h1 class="mb-3">🔥 Deal van de Week</h1>
            <h2><?= htmlspecialchars($deal['title']); ?></h2>
            <p><?= nl2br(htmlspecialchars($deal['description'])); ?></p>
            <p>
                <del>Normaal: €<?= number_format($origineel, 2, ',', '.'); ?></del><br>
                <span class="h4 text-success">Actieprijs: €<?= number_format($actieprijs, 2, ',', '.'); ?></span>
            </p>
            <a href="/pages/shop/products/product.php?id=<?= (int)$deal['product_id']; ?>" class="btn btn-primary btn-lg mt-3">
                Bekijk product
            </a>
        </div>
    </div>
</div>

<div class="voorwaarden container my-5">
    <h3>Voorwaarden Deal van de Week</h3>
    <ol>
        <li><strong>Geldigheidsduur:</strong> De Week Deal is telkens geldig voor één week, zoals aangegeven in de promotie. De actie loopt van donderdag t/m woensdag, tenzij anders vermeld.</li>
        <li><strong>Beschikbaarheid:</strong> De Week Deal geldt zolang de voorraad strekt. Op = op. Windels Green & Deco Resin behoudt zich het recht voor om de actie vroegtijdig te beëindigen indien het product is uitverkocht.</li>
        <li><strong>Prijzen & Kortingen:</strong> De actieprijs geldt uitsluitend voor de specifieke producten die in de Week Deal zijn opgenomen. Kortingen zijn niet combineerbaar met andere acties, promoties of kortingscodes.</li>
        <li><strong>Bestellen & Betalen:</strong> Aankopen kunnen worden gedaan via de webshop, sociale media, in de winkel of op de markten. Betaling dient te gebeuren volgens de standaard betaalmethoden. Reserveren is niet toegestaan tenzij afgerekend wordt binnen de actieperiode. Producten op aanvraag vallen onder dezelfde voorwaarden.</li>
        <li><strong>Retourneren & Ruilen:</strong> Producten uit de Week Deal kunnen niet worden geretourneerd of geruild, tenzij er sprake is van een fabricagefout of beschadiging bij ontvangst. Klachten moeten binnen 48 uur na ontvangst gemeld worden.</li>
        <li><strong>Klantenkaart punten:</strong> Spaarpunten worden toegekend volgens het standaard spaarsysteem, tenzij anders vermeld.</li>
        <li><strong>Wijzigingen & Annulering:</strong> Windels Green & Deco Resin behoudt zich het recht voor om de Week Deal en de voorwaarden op elk moment aan te passen of te annuleren.</li>
    </ol>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
