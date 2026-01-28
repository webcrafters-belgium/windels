<?php
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Zoekterm ophalen
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

echo '<div class="container py-5">';
echo '<h1 class="mb-4">Zoekresultaten</h1>';

if ($q == '') {
    echo '<p>Voer een zoekterm in bovenaan om producten te zoeken.</p>';
} else {

    echo '<p class="text-muted">Resultaten voor: <strong>' . htmlspecialchars($q) . '</strong></p>';

    // SQL query voorbereiden (zoeken op productnaam)
    $stmt = $mysqli->prepare("
        SELECT p.name, p.price, p.sku, i.filename 
        FROM products p 
        LEFT JOIN product_images i 
        ON p.sku = i.product_sku 
        WHERE p.name LIKE CONCAT('%', ?, '%')
        GROUP BY p.id
        LIMIT 50
    ");

    $stmt->bind_param("s", $q);
    $stmt->execute();
    $stmt->bind_result($name, $price, $sku, $image);

    // Resultaten tonen
    $found = false;
    echo '<div class="row g-4">';

    while ($stmt->fetch()) {
        $found = true;

        // Als er geen afbeelding is, placeholder gebruiken
        $imgSrc = $image ? "/images/products/$image" : "/images/placeholder.png";

        echo '
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
                <img src="' . htmlspecialchars($imgSrc) . '" alt="' . htmlspecialchars($name) . '" class="card-img-top" style="height:200px;object-fit:cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">' . htmlspecialchars($name) . '</h5>
                    <p class="card-text fw-bold mb-2">€' . number_format($price, 2, ',', '.') . '</p>
                    <a href="/pages/shop/product.php?sku=' . htmlspecialchars($sku) . '" class="btn btn-primary mt-auto">Bekijk product</a>
                </div>
            </div>
        </div>';
    }

    echo '</div>'; // einde row

    if (!$found) {
        echo '<p>Geen producten gevonden voor deze zoekterm.</p>';
    }

    $stmt->close();
}

echo '</div>'; // einde container

include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
