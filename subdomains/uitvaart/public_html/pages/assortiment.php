<?php
include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc'; 

// Als je later MySQL wil gebruiken (met mysqli), kan dit zo:

$items = [];

$sql = "
    SELECT id, title AS name, product_description AS description, total_product_price AS price, product_image AS image
    FROM epoxy_products
    WHERE sub_category = 'uitvaart'

    UNION ALL

    SELECT id, title AS name, product_description AS description, total_product_price AS price, product_image AS image
    FROM kaarsen_products
    WHERE sub_category = 'uitvaart'
    
    UNION ALL

    SELECT id, title AS name, product_description AS description, total_product_price AS price, product_image AS image
    FROM inkoop_products
    WHERE sub_category = 'uitvaart'

    ORDER BY id DESC
";

$result = $mysqli_medewerkers->query($sql);
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}
$result->free();


?>
<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>
<main class="products">
    <div class="container">
        <h2>Ons Assortiment</h2>
        <div class="product-grid">
            <?php foreach ($items as $p): ?>
                <div class="product-card">
                    <img src="https://medewerkers.windelsgreen-decoresin.com/winkel/product_img/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" loading="lazy">
                    <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                    <p><?php echo htmlspecialchars($p['description']); ?></p>
                    <p><strong>€ <?php echo number_format($p['price'], 2, ',', '.'); ?></strong></p>
                    <a href="bestel.php?product=<?php echo $p['id']; ?>" class="btn">Bestel via uw uitvaartdienst</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
<section class="steps-section">
    <div class="container">
        <h2>Hoe werkt het?</h2>
        <div class="steps-grid">
        <div class="step-card">
                <h3>1</h3>
                <p>Kies een herinneringsproduct uit ons assortiment.</p>
            </div>
            <div class="step-card">
                <h3>2</h3>
                <p>Bespreek uw keuze met de uitvaartverzorger.</p>
            </div>
            <div class="step-card">
                <h3>3</h3>
                <p>De uitvaartverzorger plaatst de bestelling.</p>
            </div>
            <div class="step-card">
                <h3>4</h3>
                <p>De uitvaartverzorger bezorgt ons de as van uw dierbare.</p>
            </div>
            <div class="step-card">
                <h3>5</h3>
                <p>Wij maken het gekozen product met de as van uw dierbare.</p>
            </div>
            <div class="step-card">
                <h3>6</h3>
                <p>Wij leveren het product aan de uitvaartdienst.</p>
            </div>
            <div class="step-card">
                <h3>7</h3>
                <p>De uitvaartdienst bezorgt u het voltooide herinneringsproduct.</p>
            </div>
        </div>
    </div>
</section>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
