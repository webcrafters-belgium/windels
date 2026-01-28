<?php

include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';
session_start();
// Alleen toegankelijk voor ingelogde uitvaartdiensten
if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/account/login.php");
    exit;
}

// Haal producten op uit de database
$products = [];

if (!empty($use_db)) {
    $query = "
        SELECT e.id, e.sku, e.title, e.product_image, e.total_product_price, 'epoxy' AS category, pa.gram AS gram_per_stuk
        FROM epoxy_products e
        LEFT JOIN product_as pa ON pa.product_id = e.id
        WHERE e.sub_category = 'uitvaart'

        UNION ALL

        SELECT k.id, k.sku, k.title, k.product_image, k.total_product_price, 'kaarsen' AS category, NULL AS gram_per_stuk
        FROM kaarsen_products k
        WHERE k.sub_category = 'uitvaart'

        UNION ALL

        SELECT i.id, i.sku, i.title, i.product_image, i.total_product_price, 'inkoop' AS category, NULL AS gram_per_stuk
        FROM inkoop_products i
        WHERE i.sub_category = 'uitvaart'

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
                                        <option
                                            value="<?= htmlspecialchars($product['id']) ?>"
                                            data-img="https://medewerkers.windelsgreen-decoresin.com/winkel/product_img/<?= htmlspecialchars($product['product_image']) ?>"
                                            data-gram="<?= isset($product['gram_per_stuk']) && $product['gram_per_stuk'] !== null ? (float)$product['gram_per_stuk'] : '' ?>"
                                            data-cat="<?= htmlspecialchars($categorie) ?>"
                                        >
                                            <?= htmlspecialchars($product['title']) ?>
                                            <?php if ($categorie === 'epoxy' && $product['gram_per_stuk'] !== null): ?>
                                                (<?= number_format((float)$product['gram_per_stuk'], 2, ',', '') ?> g/stuk)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="producten[0][qty]" value="1" min="1">
                        <div class="preview-container" style="margin-top:1rem;">
                            <img src="" class="preview-img" style="max-width:100px; display:none;">
                        </div>
                        <div class="ash-info" style="margin-top:.5rem;display:none;font-size:.95rem;"></div>
                        <button type="button" class="remove-product btn btn-danger" style="margin-top: 0.5rem;">🗑 Verwijder</button>
                    </div>
                    
                </div>
                <button type="button" id="add-product" class="btn">+ Nog een product</button>
                <div id="ash-total" style="margin-top:1rem;font-weight:bold;display:none;">Totaal as: 0 g</div>
                
            </fieldset>


            <button type="submit" class="btn">Bestelling plaatsen</button>
        </form>
    </div>
</main>
<script>
let productIndex=1;

function formatGram(v){return (Math.round((v+Number.EPSILON)*100)/100).toLocaleString('nl-BE',{minimumFractionDigits:2,maximumFractionDigits:2});}

function updatePreview(selectEl){
    const opt=selectEl.selectedOptions[0];
    const imgSrc=opt?.dataset.img||'';
    const previewImg=selectEl.closest('.product-select').querySelector('.preview-img');
    if(imgSrc){previewImg.src=imgSrc;previewImg.style.display='block';}else{previewImg.style.display='none';}
    updateAshInfo(selectEl.closest('.product-select'));
}

function updateAshInfo(block){
    const selectEl=block.querySelector('.product-dropdown');
    const qtyEl=block.querySelector('input[type="number"]');
    const infoEl=block.querySelector('.ash-info');
    const opt=selectEl.selectedOptions[0];
    const gramPerStuk=parseFloat(opt?.dataset.gram||'');
    const cat=(opt?.dataset.cat||'').toLowerCase();
    const qty=Math.max(1,parseInt(qtyEl.value||'1',10));

    if(!isNaN(gramPerStuk) && cat==='epoxy'){
        const totaal=gramPerStuk*qty;
        infoEl.style.display='block';
        infoEl.textContent='As: '+formatGram(gramPerStuk)+' g/stuk × '+qty+' = '+formatGram(totaal)+' g';
    }else{
        infoEl.style.display='none';
        infoEl.textContent='';
    }
    updateAshTotal();
}

function updateAshTotal(){
    let sum=0;
    document.querySelectorAll('.product-select').forEach(block=>{
        const selectEl=block.querySelector('.product-dropdown');
        const qtyEl=block.querySelector('input[type="number"]');
        const opt=selectEl?.selectedOptions[0];
        if(!opt) return;
        const cat=(opt.dataset.cat||'').toLowerCase();
        const gramPerStuk=parseFloat(opt.dataset.gram||'');
        const qty=Math.max(1,parseInt(qtyEl.value||'1',10));
        if(cat==='epoxy' && !isNaN(gramPerStuk)) sum+=gramPerStuk*qty;
    });
    const totalEl=document.getElementById('ash-total');
    if(sum>0){ totalEl.style.display='block'; totalEl.textContent='Totaal as: '+formatGram(sum)+' g'; }
    else{ totalEl.style.display='none'; totalEl.textContent=''; }
}

// init voor bestaand blok
document.querySelectorAll('.product-dropdown').forEach(select=>{
    select.addEventListener('change',function(){updatePreview(this);});
    // init bij laden als default al geselecteerd is
    updatePreview(select);
});
document.querySelectorAll('.product-select input[type="number"]').forEach(inp=>{
    inp.addEventListener('input',function(){updateAshInfo(this.closest('.product-select'));});
});
document.querySelectorAll('.remove-product').forEach(btn=>{
    btn.addEventListener('click',function(){ this.closest('.product-select').remove(); updateAshTotal();});
});

document.getElementById('add-product').addEventListener('click',function(){
    const container=document.getElementById('producten-container');
    const div=document.createElement('div');
    div.className='product-select';
    div.innerHTML=`
        <select name="producten[${productIndex}][id]" class="product-dropdown">
            <option value="">Kies een product</option>
            <?php foreach ($products as $product): ?>
                <option
                    value="<?= htmlspecialchars($product['id']) ?>"
                    data-img="https://medewerkers.windelsgreen-decoresin.com/winkel/product_img/<?= htmlspecialchars($product['product_image']) ?>"
                    data-gram="<?= isset($product['gram_per_stuk']) && $product['gram_per_stuk'] !== null ? (float)$product['gram_per_stuk'] : '' ?>"
                    data-cat="<?= htmlspecialchars($product['category']) ?>"
                >
                    <?= htmlspecialchars($product['title']) ?>
                    <?php if ($product['category'] === 'epoxy' && $product['gram_per_stuk'] !== null): ?>
                        (<?= number_format((float)$product['gram_per_stuk'], 2, ',', '') ?> g/stuk)
                    <?php endif; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="producten[${productIndex}][qty]" value="1" min="1">
        <div class="preview-container" style="margin-top:1rem;">
            <img src="" class="preview-img" style="max-width:100px;display:none;">
        </div>
        <div class="ash-info" style="margin-top:.5rem;display:none;font-size:.95rem;"></div>
        <button type="button" class="remove-product btn btn-danger" style="margin-top:.5rem;">🗑 Verwijder</button>
    `;
    container.appendChild(div);

    const newSelect=div.querySelector('.product-dropdown');
    newSelect.addEventListener('change',function(){updatePreview(this);});
    const qtyEl=div.querySelector('input[type="number"]');
    qtyEl.addEventListener('input',function(){updateAshInfo(div);});
    div.querySelector('.remove-product').addEventListener('click',function(){div.remove();updateAshTotal();});
    updatePreview(newSelect);
    productIndex++;
});
</script>



<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
