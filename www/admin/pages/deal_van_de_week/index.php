<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-star-fill accent-primary mr-3"></i>Deal van de Week
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Voeg een speciale weekaanbieding toe</p>
        </div>
    </div>
</div>

<!-- DEAL FORM -->
<div class="card-glass p-8">
    <div class="flex items-center space-x-3 mb-8">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/30 to-orange-500/30 flex items-center justify-center">
            <i class="bi bi-lightning-fill text-xl text-amber-400"></i>
        </div>
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Nieuwe Deal Toevoegen</h2>
    </div>

    <form action="/admin/functions/deals/submit_deal.php" method="POST" class="space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- SKU Search -->
            <div class="flex flex-col">
                <label for="sku-search" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Zoek product op SKU</label>
                <input type="text" id="sku-search" placeholder="Typ SKU hier..."
                       class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
            </div>

            <!-- Product Select -->
            <div class="flex flex-col">
                <label for="product_id" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Of selecteer een product</label>
                <select name="product_id" id="product_id" required
                        class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
                    <option value="">Laad producten...</option>
                </select>
            </div>
        </div>

        <!-- Title -->
        <div class="flex flex-col">
            <label for="title" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Titel van de deal</label>
            <input type="text" name="title" id="title" placeholder="Bijvoorbeeld: Korting op Epoxy!" required
                   class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
        </div>

        <!-- Description -->
        <div class="flex flex-col">
            <label for="description" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Beschrijving van de deal</label>
            <textarea name="description" id="description" rows="4" placeholder="Beschrijf kort de aanbieding..." required
                      class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);"></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Price -->
            <div class="flex flex-col">
                <label for="new_price" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Actieprijs (€)</label>
                <input type="number" name="new_price" id="new_price" step="0.01" min="0" placeholder="14.95" required
                       class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
            </div>

            <!-- Start Date -->
            <div class="flex flex-col">
                <label for="start_date" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Startdatum</label>
                <input type="date" name="start_date" id="start_date" required
                       class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
            </div>

            <!-- End Date -->
            <div class="flex flex-col">
                <label for="end_date" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Einddatum</label>
                <input type="date" name="end_date" id="end_date" required
                       class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
            </div>
        </div>

        <button type="submit" class="accent-bg text-white px-8 py-4 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-3">
            <i class="bi bi-rocket-takeoff"></i>Deal Toevoegen
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const skuSearch = document.getElementById('sku-search');
        const productSelect = document.getElementById('product_id');

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

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
