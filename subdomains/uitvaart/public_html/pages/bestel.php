<?php
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';
include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php';

$items = [];

// Check of een specifiek product werd opgevraagd
$productId = isset($_GET['product']) ? (int)$_GET['product'] : null;

// Dynamische WHERE-aanvulling
$filterId = $productId ? "AND id = $productId" : "";

// SQL-query
$sql = "
    (
        SELECT id, title AS name, product_description AS description, total_product_price AS price, product_image AS image, category
        FROM epoxy_products
        WHERE sub_category = 'uitvaart' $filterId
    )
    UNION ALL
    (
        SELECT id, title AS name, product_description AS description, total_product_price AS price, product_image AS image, 'kaarsen' AS category
        FROM kaarsen_products
        WHERE sub_category = 'uitvaart' $filterId
    )
    UNION ALL
    (
        SELECT id, title AS name, product_description AS description, total_product_price AS price, product_image AS image, 'inkoop' AS category
        FROM inkoop_products
        WHERE sub_category = 'uitvaart' $filterId
    )
    ORDER BY name ASC
";

// Uitvoeren
$result = $mysqli_medewerkers->query($sql);
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}
$result->free();
?>

<main class="bestel-container">
    <h1><?= $productId ? 'Productdetail' : 'Ons assortiment' ?></h1>

    <p>
        <?= $productId
            ? 'Hieronder vind je meer informatie over het gekozen product.'
            : 'Bekijk onze selectie en contacteer je plaatselijke uitvaartdienst om een bestelling door te geven.'
        ?>
    </p>

    <div class="product-list">
        <?php foreach ($items as $item): ?>
            <div class="product-card">
                <div class="product-card-inner">
                    <div class="product-image">
                        <img src="https://medewerkers.windelsgreen-decoresin.com/winkel/product_img/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    </div>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                        <p class="price">€<?= number_format($item['price'], 2, ',', '.') ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="action-buttons">
        <a href="/pages/contacteer-uitvaartdienst.php" class="btn">Contacteer je uitvaartdienst</a>
        <?php if ($productId): ?>
            <a href="/pages/assortiment.php" class="btn secondary">← Terug naar overzicht</a>
        <?php endif; ?>
    </div>
</main>

<style>
.bestel-container{
    max-width: 960px;
    margin: auto;
    padding: 2rem;
}


h1 {
    font-size: 2rem;
    color: #2e2e2e;
    margin-bottom: 1rem;
}

.bestel-container > p,
.bestel-container .product-info p{
    font-size: 1rem;
    line-height: 1.6;
    color: #444;
}

.product-list {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    margin-top: 2rem;
}

.product-card {
    border: 1px solid #ddd;
    border-radius: 12px;
    overflow: hidden;
    background-color: #fafafa;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    transition: box-shadow 0.3s;
}

.product-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.product-card-inner {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    padding: 1.5rem;
}

.product-image img {
    width: 250px;
    max-width: 100%;
    border-radius: 8px;
}

.product-info {
    flex: 1;
    min-width: 200px;
}

.product-info h3 {
    margin-top: 0;
    color: #333;
    font-size: 1.3rem;
}

.product-info .price {
    font-weight: bold;
    font-size: 1.2rem;
    margin-top: 1rem;
    color: #006c4d;
}

.action-buttons {
    margin-top: 2rem;
    text-align: center;
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn {
    background-color: #006c4d;
    color: white;
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #004d36;
}

.btn.secondary {
    background-color: #ccc;
    color: #333;
}

.btn.secondary:hover {
    background-color: #aaa;
}
</style>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
