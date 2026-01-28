<?php

// Zet op true tijdens development, false als de site live mag
$isdev = true;

// Alleen toegang als dev-mode aan staat
if (!$isdev) {
    header('Location: /comingsoon/index.php');
    exit;
}


include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc'; 
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

include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; 
?>

<main class="hero">
    <section class="hero-content">
        <h1>Welkom bij onze uitvaartzorg webshop</h1>
        <p>Een serene omgeving voor het bestellen van gepersonaliseerde decoraties met asverwerking.</p>
        <a href="#producten" class="btn">Bekijk ons assortiment</a>
    </section>
</main>

<section class="reviews-section">
    <div class="container">
        <h2>Wat zeggen nabestaanden?</h2>
        <div class="reviews-grid">
            <div class="review-card">
                <p>"Prachtige urn, met respect geleverd. Bedankt voor de zorg."</p>
                <span>- Familie De Vries</span>
            </div>
            <div class="review-card">
                <p>"Het herdenkingssieraad is subtiel en geeft troost. Aanrader."</p>
                <span>- Anoniem</span>
            </div>
            <div class="review-card">
                <p>"Zeer professionele afhandeling. Alles perfect geregeld."</p>
                <span>- Uitvaartzorg Noord</span>
            </div>
        </div>
    </div>
</section>

<section class="why-section">
    <div class="container">
        <h2>Waarom kiezen voor Windels?</h2>
        <div class="why-grid">
            <div class="why-card">
                <h3>Zorg & Respect</h3>
                <p>Elke creatie wordt met de grootste zorg en respect vervaardigd.</p>
            </div>
            <div class="why-card">
                <h3>Ambacht & Kwaliteit</h3>
                <p>Wij combineren ambacht met duurzame materialen en professionele afwerking.</p>
            </div>
            <div class="why-card">
                <h3>Persoonlijke benadering</h3>
                <p>Wij luisteren naar uw wensen en zorgen voor een waardig aandenken.</p>
            </div>
        </div>
    </div>
</section>

<section class="steps-section">
    <div class="container">
        <h2>Hoe werkt het?</h2>
        <div class="steps-grid">
            <?php 
            $stappen = [
                "Kies een herinneringsproduct uit ons assortiment.",
                "Bespreek uw keuze met de uitvaartverzorger.",
                "De uitvaartverzorger plaatst de bestelling.",
                "De uitvaartverzorger bezorgt ons de as van uw dierbare.",
                "Wij maken het gekozen product met de as van uw dierbare.",
                "Wij leveren het product aan de uitvaartdienst.",
                "De uitvaartdienst bezorgt u het voltooide herinneringsproduct."
            ];
            foreach ($stappen as $index => $tekst): ?>
                <div class="step-card">
                    <h3><?php echo $index + 1; ?></h3>
                    <p><?php echo $tekst; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="producten" class="products">
    <div class="container">
        <h2>Populaire Herinneringsproducten</h2>
        <div class="product-grid">
            <?php foreach (array_slice($items, 0, 4) as $p): ?>
                <div class="product-card">
                    <img src="https://medewerkers.windelsgreen-decoresin.com/winkel/product_img/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" loading="lazy">
                    <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                    <p><?php echo htmlspecialchars($p['description']); ?></p>
                    <p><strong>€ <?php echo number_format($p['price'], 2, ',', '.'); ?></strong></p>
                    <a href="pages/assortiment.php" class="btn">Meer info</a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="centered">
            <a href="pages/assortiment.php" class="btn-secondary">Bekijk alle producten</a>
        </div>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <h2>Contact & Over ons</h2>
        <p>Wij helpen u graag verder. Neem contact met ons op voor vragen of advies.</p>
        <a href="pages/contact/contact.php" class="btn">Neem contact op</a>
    </div>
</section>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>