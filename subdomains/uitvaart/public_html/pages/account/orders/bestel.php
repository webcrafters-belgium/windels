<?php
session_start();
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

// Alleen toegankelijk voor ingelogde uitvaartdiensten
if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/login.php");
    exit;
}

// Haal producten op uit de database
$products = [];

if (!empty($use_db)) {
    $query = "
        SELECT id, sku, title, product_image, total_product_price, category
        FROM epoxy_products
        WHERE sub_category = 'uitvaart'

        UNION ALL

        SELECT id, sku, title, product_image, total_product_price, 'kaarsen' AS category
        FROM kaarsen_products
        WHERE sub_category = 'uitvaart'

        UNION ALL

        SELECT id, sku, title, product_image, total_product_price, 'inkoop' AS category
        FROM inkoop_products
        WHERE sub_category = 'uitvaart'

        ORDER BY title ASC
    ";

    if ($res = $mysqli_medewerkers->query($query)) {
        while ($row = $res->fetch_assoc()) {
            $products[] = $row;
        }
    }
}

?>
<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>
<style>
body {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
}

.order-form-page {
    background-color: rgba(255, 255, 255, 0.92);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 3rem auto 2rem auto;
    max-width: 720px;
    width: 90%;
    box-sizing: border-box;
}

/* ✅ Mobiele optimalisatie */
@media (max-width: 768px) {

    .order-form-page {
        padding: 2rem 1rem;
        margin: 2rem 1rem;
        border-radius: 8px;
    }
}

    .product-select {
    border: 1px solid #ddd;
    padding: 1rem;
    margin-bottom: 1rem;
    background: #f9f9f9;
    border-radius: 6px;
}

@media (min-width: 769px) {
    .product-select:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
}

/* 📱 Mobiele weergave */
@media (max-width: 768px) {
    .product-select {
        padding: 0.75rem;
        margin-bottom: 1rem;
        font-size: 1rem;
        line-height: 1.5;
    }
}
</style>
<main class="order-form-page">
    <div class="container">
        <h2>Nieuwe bestelling plaatsen</h2>
        <p style="text-align: center; margin: 2rem;">Alle velden zij verplicht in te vullen om te kunnen bestellen.</p>
        <form action="bestel_verwerk.php" method="post" id="order-form">
            <fieldset>
                <legend>Klantgegevens</legend>
                <label for="klant_naam">Naam klant:</label>
                <input type="text" name="klant_naam" id="klant_naam" required>

                <label for="klant_email">E-mail klant:</label>
                <input type="email" name="klant_email" id="klant_email" required>

                <label for="klant_telefoon">Telefoonnummer:</label>
                <input type="text" name="klant_telefoon" id="klant_telefoon">

                <label for="klant_adres">Adres klant:</label>
                <textarea name="klant_adres" id="klant_adres" rows="3"></textarea>
                
                <label for="klantnummer_partner">Klantnummer (uitvaartdienst):</label>
                <input type="text" name="klantnummer_partner" id="klantnummer_partner" placeholder="Bijv. intern nummer">

            </fieldset>

            <fieldset>
                <legend>Producten selecteren</legend>
                <div id="producten-container">
                    <div class="product-select">
                        <?php
                            $gegroepeerd = [];
                            foreach ($products as $product) {
                                $gegroepeerd[$product['category']][] = $product;
                            }
                        ?>

                        <select name="producten[0][id]" class="product-dropdown">
                            <option value="">Kies een product</option>
                            <?php foreach ($gegroepeerd as $categorie => $producten): ?>
                                <optgroup label="<?= ucfirst($categorie) ?>">
                                    <?php foreach ($producten as $product): ?>
                                        <option value="<?= htmlspecialchars($product['id']) ?>"
                                                data-img="https://medewerkers.windelsgreen-decoresin.com/winkel/product_img/<?= htmlspecialchars($product['product_image']) ?>">
                                            <?= htmlspecialchars($product['title']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="producten[0][qty]" value="1" min="1">
                        <div class="preview-container" style="margin-top:1rem;">
                            <img src="" class="preview-img" style="max-width:100px; display:none;">
                        </div>
                        <button type="button" class="remove-product btn btn-danger" style="margin-top: 0.5rem;">🗑 Verwijder</button>
                    </div>
                </div>
                <button type="button" id="add-product" class="btn">+ Nog een product</button>
            </fieldset>


            <button type="submit" class="btn">Bestelling plaatsen</button>
        </form>
    </div>
</main>
<script>
let productIndex = 1;

function updatePreview(selectEl) {
    const imgSrc = selectEl.selectedOptions[0].dataset.img;
    const previewImg = selectEl.closest('.product-select').querySelector('.preview-img');
    if (imgSrc) {
        previewImg.src = imgSrc;
        previewImg.style.display = 'block';
    } else {
        previewImg.style.display = 'none';
    }
}

// Voor bestaand eerste blok
document.querySelectorAll('.product-dropdown').forEach(select => {
    select.addEventListener('change', function () {
        updatePreview(this);
    });
});
document.querySelectorAll('.remove-product').forEach(btn => {
    btn.addEventListener('click', function () {
        this.closest('.product-select').remove();
    });
});

document.getElementById('add-product').addEventListener('click', function () {
    const container = document.getElementById('producten-container');
    const div = document.createElement('div');
    div.className = 'product-select';
    div.innerHTML = `
        <select name="producten[${productIndex}][id]" class="product-dropdown">
            <option value="">Kies een product</option>
            <?php foreach ($products as $product): ?>
                <option value="<?= $product['id'] ?>" data-img="https://medewerkers.windelsgreen-decoresin.com/winkel/product_img/<?= htmlspecialchars($product['product_image']) ?>">
                    <?= htmlspecialchars($product['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="producten[${productIndex}][qty]" value="1" min="1">
        <div class="preview-container" style="margin-top:1rem;">
            <img src="" class="preview-img" style="max-width:200px; display:none;">
        </div>
        <button type="button" class="remove-product btn btn-danger" style="margin-top: 0.5rem;">🗑 Verwijder</button>
    `;
    container.appendChild(div);

    const newSelect = div.querySelector('.product-dropdown');
    newSelect.addEventListener('change', function () {
        updatePreview(this);
    });

    const removeBtn = div.querySelector('.remove-product');
    removeBtn.addEventListener('click', function () {
        div.remove();
    });

    productIndex++;
});
</script>


<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
