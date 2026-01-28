<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

?>

<div class="admin-container">
    <h2>✨ Deal van de Week Toevoegen</h2>

    <form action="/admin/functions/deals/submit_deal.php" method="POST" class="deal-form">

        <label for="sku-search">Zoek product op SKU:</label>
        <input type="text" id="sku-search" placeholder="Typ SKU hier...">

        <label for="product_id">Of selecteer een product:</label>
        <select name="product_id" id="product_id" required>
            <option value="">Laad producten...</option>
        </select>

        <label for="title">Titel van de deal:</label>
        <input type="text" name="title" id="title" placeholder="Bijvoorbeeld: Korting op Epoxy!" required>

        <label for="description">Beschrijving van de deal:</label>
        <textarea name="description" id="description" placeholder="Beschrijf kort de aanbieding..." required></textarea>

        <label for="new_price">Actieprijs (€):</label>
        <input type="number" name="new_price" id="new_price" step="0.01" min="0" placeholder="Bijvoorbeeld: 14.95" required>

        <div class="date-fields">
            <label for="start_date">Startdatum:</label>
            <input type="date" name="start_date" id="start_date" required>

            <label for="end_date">Einddatum:</label>
            <input type="date" name="end_date" id="end_date" required>
        </div>

        <button type="submit" class="btn-submit">Deal Toevoegen 🚀</button>
    </form>
</div>

<?php
$conn->close();
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>

<!-- JavaScript voor SKU zoeken en AJAX product ophalen -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const skuSearch = document.getElementById('sku-search');
        const productSelect = document.getElementById('product_id');

        // Laad alle producten initieel
        loadProducts();

        skuSearch.addEventListener('input', function () {
            const sku = this.value.trim();
            loadProducts(sku);
        });

        function loadProducts(sku = '') {
            fetch('/admin/functions/deals/search_products.php?sku=' + encodeURIComponent(sku))
                .then(response => response.json())
                .then(data => {
                    productSelect.innerHTML = '<option value="">Selecteer een product...</option>';

                    data.forEach(product => {
                        let option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = product.name + ' (' + product.sku + ')';
                        productSelect.appendChild(option);
                    });
                })
                .catch(err => console.error('Fout bij laden:', err));
        }
    });
</script>
