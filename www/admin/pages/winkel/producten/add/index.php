<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

$getSku = "SELECT MAX(CAST(SUBSTRING_INDEX(sku, '-', -1) AS UNSIGNED)) AS sku FROM products";
$res = $conn->query($getSku);
$nextNumber = 10001;
if ($res && $row = $res->fetch_assoc()) {
    if ((int)$row['sku'] > 0) {
        $nextNumber = ((int)$row['sku']) + 1;
    }
}

$success = isset($_GET['success']) && $_GET['success'] === '1';
$productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : null;
?>

<div class="space-y-6">
    <?php if ($success): ?>
        <section class="card-glass p-4 border border-emerald-500/30 space-y-1">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold text-emerald-200">Product succesvol toegevoegd.</p>
                    <?php if ($productId): ?>
                        <p class="text-xs text-emerald-300 flex flex-wrap gap-1">
                            ID <?= $productId ?>.
                            <a href="/admin/pages/winkel/producten/edit_product.php?id=<?= $productId ?>" class="underline text-emerald-100 hover:text-white">Direct bewerken</a>
                        </p>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-100 text-xs font-semibold">
                        <?= $productId ? "Nieuw ID: $productId" : 'Nieuwe invoer opgeslagen' ?>
                    </span>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <section class="card-glass p-6 shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-widest text-teal-400 mb-2">Winkelbeheer</p>
                <h1 class="text-3xl font-bold">Nieuw winkelproduct toevoegen</h1>
                <p class="text-sm text-gray-400">Deze invoer gaat rechtstreeks naar de winkelcatalogus. Vul extra specs in voor epoxy, terrazzo of kaarsen.</p>
            </div>
            <div class="flex gap-3">
                <a href="/admin/pages/winkel/producten/index.php" class="px-4 py-2 rounded-xl border border-white/10 hover:border-white/30 transition">Terug naar de winkel</a>
                <span class="px-4 py-2 rounded-xl bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Volgende SKU: <?= $nextNumber ?></span>
            </div>
        </div>
    </section>

    <section class="card-glass p-6 space-y-6">
        <form action="/admin/functions/shop/products/insert_product.php" method="post" enctype="multipart/form-data" class="space-y-8">
            <input type="hidden" id="sku_numeric" value="<?= $nextNumber ?>">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Hoofdcategorie</label>
                    <select id="category" name="category" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2" required></select>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Subcategorie</label>
                    <select id="sub_category" name="sub_category" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2" disabled required>
                        <option value="" disabled selected>Kies een subcategorie</option>
                        <option value="__new__">➕ Nieuwe subcategorie toevoegen…</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">SKU</label>
                    <input type="text" id="sku" name="sku" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2" readonly required>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Titel</label>
                    <input type="text" name="name" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2" required>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Slug (uniek)</label>
                    <input type="text" name="slug" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2" required>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Afbeelding uploaden (optioneel)</label>
                    <input type="file" id="imageUploader" name="product_image" class="text-gray-300">
                    <img id="selectedImage" src="/images/products/placeholder.png" alt="Product preview" class="w-32 h-32 mt-3 rounded-xl border border-gray-800 object-cover">
                    <p class="text-xs text-gray-500 mt-2">Wordt normaal opgeslagen in <span class="font-medium">/images/products/&lt;categorie&gt;/&lt;variant-sku&gt;/</span>. Laat leeg als je een bestaande pad wilt hergebruiken.</p>
                    <label class="text-sm text-gray-400 mb-1 mt-4">Handmatige pad (optioneel)</label>
                    <input type="text" name="manual_image_path" placeholder="/images/products/kaarsen/1234" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2 text-sm text-gray-200">
                    <label class="text-sm text-gray-400 mb-1 mt-3">Bestandsnaam (optioneel)</label>
                    <input type="text" name="manual_image_name" placeholder="main.png" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2 text-sm text-gray-200">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Voorraad</label>
                    <input type="number" name="stock_quantity" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2" min="0" required>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Prijs (€)</label>
                    <input type="number" name="price" step="0.01" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2" required>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Producttype</label>
                    <select name="type" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2">
                        <option value="simple">Eenvoudig</option>
                        <option value="variable">Variabel</option>
                        <option value="custom">Op maat</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Voorraadstatus</label>
                    <select name="stock_status" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2">
                        <option value="instock">Op voorraad</option>
                        <option value="outofstock">Niet op voorraad</option>
                        <option value="backorder">Backorder</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Normale prijs</label>
                    <input type="number" name="regular_price" step="0.01" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2" placeholder="bv. 24.95">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Actieprijs (optioneel)</label>
                    <input type="number" name="sale_price" step="0.01" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2" placeholder="bv. 19.95">
                </div>
            </div>

            <div class="relative">
                <label class="text-sm text-gray-400 mb-1">Varianten van (optioneel)</label>
                <input type="text" id="parent_search" name="parent_reference" autocomplete="off"
                       class="w-full bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2"
                       placeholder="Zoek op naam of SKU…">
                <input type="hidden" id="parent_id" name="parent_id">
                <div id="parent_selection" class="hidden mt-2 rounded-xl bg-white/5 px-3 py-2 text-xs text-gray-300 flex items-center justify-between gap-3 border border-gray-800">
                    <span>Geselecteerd: <span id="parent_label" class="font-semibold">—</span></span>
                    <button type="button" id="parent_clear" class="text-emerald-300 hover:text-white">Verwijderen</button>
                </div>
                <div id="parent_results"
                     class="absolute inset-x-0 mt-1 bg-[#111] border border-gray-800 rounded-xl max-h-64 overflow-auto z-50 glass hidden">
                    <div id="parent_results_list" class="divide-y divide-white/5"></div>
                    <p id="parent_results_message" class="text-xs text-gray-500 px-3 py-2 hidden">Geen resultaten gevonden.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Beschrijving</label>
                <textarea id="description" name="description" rows="4" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2"></textarea>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Korte beschrijving</label>
                <textarea id="short_description" name="short_description" rows="4" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2"></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Gram (epoxy / terrazzo)</label>
                    <input type="number" name="amount_grams" min="0" step="1" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2" placeholder="Gram">
                    <p class="text-xs text-gray-500 mt-1">Voor epoxy of terrazzo producten. Laat leeg als niet van toepassing.</p>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Paraffine (%)</label>
                    <input type="number" name="paraffin_percentage" min="0" step="0.1" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2" placeholder="bv. 60">
                    <p class="text-xs text-gray-500 mt-1">Gebruik voor kaarsen.</p>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Stearine (%)</label>
                    <input type="number" name="stearin_percentage" min="0" step="0.1" class="bg-[#0f0f0f] border border-gray-800 rounded-xl px-3 py-2" placeholder="bv. 40">
                    <p class="text-xs text-gray-500 mt-1">Optioneel voor kaarsen met gemengde mix.</p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pt-4">
                <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-500 font-semibold text-white shadow-lg hover:opacity-90 transition">✓ Product invoegen</button>
                <a href="/admin/pages/winkel/producten/" class="px-6 py-3 rounded-xl border border-white/10 hover:border-white/30 transition text-sm">Annuleren</a>
            </div>
        </form>
    </section>
</div>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/zje5a800hqt96bgn4ex8jhddjx4tpnoht9c98reetr69igg6/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<!-- SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.tinymce) {
            tinymce.init({
                selector: '#description, #short_description',
                height: 280,
                menubar: false,
                branding: false,
                toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | removeformat',
                plugins: 'lists',
                content_style: 'body { font-family: \"Outfit\", system-ui, sans-serif; font-size:14px; color:#0f172a; }',
            });
        }
        const cat = document.getElementById('category');
        const sub = document.getElementById('sub_category');
        const sku = document.getElementById('sku');
        const next = parseInt(document.getElementById('sku_numeric').value, 10);
        const parentSearch = document.getElementById('parent_search');
        const parentId = document.getElementById('parent_id');
        const resultsBox = document.getElementById('parent_results');
        const resultsList = document.getElementById('parent_results_list');
        const resultsMessage = document.getElementById('parent_results_message');
        const parentSelection = document.getElementById('parent_selection');
        const parentLabel = document.getElementById('parent_label');
        const parentClear = document.getElementById('parent_clear');
        const uploader = document.getElementById('imageUploader');
        const preview = document.getElementById('selectedImage');

        fetch('/admin/functions/shop/products/get_categories.php')
            .then(r => r.json())
            .then(list => {
                cat.innerHTML = '<option value="">-- Kies categorie --</option>';
                list.forEach(c => {
                    const o = document.createElement('option');
                    o.value = c.id;
                    o.textContent = c.name;
                    cat.appendChild(o);
                });
            });

        cat.addEventListener('change', () => {
            if (!cat.value) return;
            sku.value = `${cat.value}-${next}`;
            sub.disabled = false;
            fetch(`/admin/functions/shop/products/get_subcategories.php?parent_id=${cat.value}`)
                .then(r => r.json())
                .then(list => {
                    sub.innerHTML = '<option value="" disabled selected>Kies een subcategorie</option>';
                    list.forEach(s => {
                        sub.add(new Option(s.text, s.value));
                    });
                    sub.add(new Option('➕ Nieuwe subcategorie toevoegen…', '__new__'));
                });
        });

        sub.addEventListener('change', () => {
            if (sub.value === '__new__') {
                const name = prompt('Naam nieuwe subcategorie:');
                if (!name || !cat.value) {
                    sub.value = '';
                    alert('Geef een naam op en kies eerst een hoofdcategorie.');
                    return;
                }
                fetch('/admin/functions/shop/products/add_subcategory.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({name, parent_id: cat.value})
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const option = new Option(data.name, data.slug, true, true);
                            sub.add(option);
                        } else {
                            alert(data.message || 'Fout bij toevoegen.');
                            sub.value = '';
                        }
                    });
            }
        });

        parentSearch.addEventListener('input', () => {
            const q = parentSearch.value.trim();
            resultsList.innerHTML = '';
            resultsMessage.classList.add('hidden');
            resultsBox.classList.add('hidden');
            parentId.value = '';
            hideSelection();
            if (q.length < 2) return;
            fetch(`/admin/functions/shop/products/search_parent_products.php?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(items => {
                    if (!items.length) {
                        resultsMessage.classList.remove('hidden');
                        resultsBox.classList.remove('hidden');
                        return;
                    }
                    items.forEach(item => {
                        const el = document.createElement('div');
                        el.className = 'px-3 py-2 hover:bg-white/10 cursor-pointer';
                        el.textContent = `${item.sku} – ${item.name}`;
                        el.addEventListener('click', () => {
                            parentSearch.value = `${item.sku} – ${item.name}`;
                            parentId.value = item.id;
                            parentLabel.textContent = `${item.sku} – ${item.name}`;
                            resultsBox.classList.add('hidden');
                            showSelection();
                        });
                        resultsList.appendChild(el);
                    });
                    resultsBox.classList.remove('hidden');
                });
        });

        parentSearch.addEventListener('focus', () => {
            if (resultsList.children.length) {
                resultsBox.classList.remove('hidden');
            }
        });

        document.addEventListener('click', (event) => {
            if (!resultsBox.contains(event.target) && event.target !== parentSearch) {
                resultsBox.classList.add('hidden');
            }
        });

        parentClear.addEventListener('click', () => {
            parentId.value = '';
            parentSearch.value = '';
            hideSelection();
        });

        function showSelection() {
            parentSelection.classList.remove('hidden');
        }

        function hideSelection() {
            parentSelection.classList.add('hidden');
            parentLabel.textContent = '—';
        }

        hideSelection();

        uploader.addEventListener('change', () => {
            const f = uploader.files[0];
            if (!f) return;
            const reader = new FileReader();
            reader.onload = e => preview.src = e.target.result;
            reader.readAsDataURL(f);
        });
    });
</script>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
