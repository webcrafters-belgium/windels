<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');
include($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php');

/* Volgende SKU bepalen */
$getSku = "SELECT MAX(CAST(SUBSTRING_INDEX(sku, '-', -1) AS UNSIGNED)) AS sku FROM products";
$res = $conn->query($getSku);
$nextNumber = 10001;
if ($res && $row = $res->fetch_assoc()) {
    if ((int)$row['sku'] > 0) $nextNumber = ((int)$row['sku']) + 1;
}
?>

<div class="min-h-screen bg-[#0d0d0d] text-gray-200 py-10 px-10 space-y-10">

    <!-- PAGINA HEADER -->
    <div class="bg-[#141414] border border-gray-800 rounded-2xl p-8 shadow-xl flex justify-between">
        <div>
            <h1 class="text-3xl font-bold">Nieuw product toevoegen</h1>
            <p class="text-gray-400 text-sm mt-1">
                Vul alle informatie in en voeg een nieuw artikel toe aan de webshop.
            </p>
        </div>

        <a href="/admin/pages/products/"
           class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg font-semibold">
            Terug
        </a>
    </div>

    <div class="flex">
        <!-- SIDEBAR -->
        <aside class="hidden md:block w-64 bg-[#141414] border-r border-gray-800 min-h-screen p-6">
            <h2 class="text-sm uppercase tracking-wider text-gray-500">Navigatie</h2>
            <ul class="mt-4 space-y-2">
                <li><a href="/admin/pages/products" class="block p-2 rounded hover:bg-gray-800"><i class="bi bi-box-seam mr-2"></i> Producten</a></li>
                <li><a href="/admin/orders" class="block p-2 rounded hover:bg-gray-800"><i class="bi bi-receipt mr-2"></i> Bestellingen</a></li>
                <li><a href="/admin/customers" class="block p-2 rounded hover:bg-gray-800"><i class="bi bi-people mr-2"></i> Klanten</a></li>
                <li><a href="/admin/settings" class="block p-2 rounded hover:bg-gray-800"><i class="bi bi-gear mr-2"></i> Instellingen</a></li>
                <li><a href="/admin/" class="block p-2 rounded hover:bg-gray-800"><i class="bi bi-house mr-2"></i>Home</a></li>
            </ul>
        </aside>


        <!-- FORM CARD -->
        <div class="bg-[#141414] border border-gray-800 rounded-2xl p-10 shadow-xl" style="width: 100%">

            <?php if (isset($_GET['success'])): ?>
                <div class="mb-6 p-4 bg-green-700/20 border border-green-700 rounded-lg text-green-300">
                    ✔ Product succesvol toegevoegd!
                </div>
            <?php endif; ?>

            <form action="/admin/functions/shop/products/insert_product.php"
                  method="post" enctype="multipart/form-data"
                  class="space-y-10">

                <input type="hidden" id="sku_numeric" value="<?= $nextNumber ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                    <!-- Categorie -->
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-400 mb-1">Hoofdcategorie</label>
                        <select id="category" name="category"
                                class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2"
                                required></select>
                    </div>

                    <!-- Subcategorie -->
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-400 mb-1">Subcategorie</label>
                        <select id="sub_category" name="sub_category"
                                class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2"
                                disabled required>
                            <option value="" disabled selected>Kies een subcategorie</option>
                            <option value="__new__">➕ Nieuwe subcategorie toevoegen…</option>
                        </select>
                    </div>

                    <!-- SKU -->
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-400 mb-1">SKU</label>
                        <input type="text" id="sku" name="sku"
                               class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2"
                               readonly required>
                    </div>

                    <!-- Titel -->
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-400 mb-1">Titel</label>
                        <input type="text" name="name"
                               class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2"
                               required>
                    </div>

                    <!-- Slug -->
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-400 mb-1">Slug (uniek)</label>
                        <input type="text" name="slug"
                               class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2"
                               required>
                    </div>

                    <!-- Foto -->
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-400 mb-1">Afbeelding</label>
                        <input type="file" id="imageUploader" name="product_image"
                               class="text-gray-300" required>
                        <img id="selectedImage"
                             src="/images/products/placeholder.png"
                             class="w-24 h-24 mt-3 rounded-lg object-cover border border-gray-800">
                    </div>

                    <!-- Stock -->
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-400 mb-1">Voorraad</label>
                        <input type="number" name="stock_quantity"
                               class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2" required>
                    </div>

                    <!-- Prijs -->
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-400 mb-1">Prijs (€)</label>
                        <input type="number" name="price" step="0.01"
                               class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2" required>
                    </div>

                    <!-- Type -->
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-400 mb-1">Producttype</label>
                        <select name="type"
                                class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2">
                            <option value="simple">Eenvoudig</option>
                            <option value="variable">Variabel</option>
                            <option value="custom">Op maat</option>
                        </select>
                    </div>

                    <!-- Voorraadstatus -->
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-400 mb-1">Voorraadstatus</label>
                        <select name="stock_status"
                                class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2">
                            <option value="instock">Op voorraad</option>
                            <option value="outofstock">Niet op voorraad</option>
                            <option value="backorder">Backorder</option>
                        </select>
                    </div>

                    <!-- Regular price -->
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-400 mb-1">Normale prijs</label>
                        <input type="number" name="regular_price" step="0.01"
                               class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2">
                    </div>

                    <!-- Sale price -->
                    <div class="flex flex-col">
                        <label class="text-sm text-gray-400 mb-1">Actieprijs (optioneel)</label>
                        <input type="number" name="sale_price" step="0.01"
                               class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2">
                    </div>

                </div>

                <!-- Parent search (varianten) -->
                <div class="relative">
                    <label class="text-sm text-gray-400 mb-1">Varianten van (optioneel)</label>
                    <input type="text" id="parent_search"
                           class="w-full bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2"
                           placeholder="Zoek op naam of SKU…">
                    <input type="hidden" id="parent_id" name="parent_id">

                    <div id="parent_results"
                         class="absolute mt-1 w-full hidden bg-[#111] border border-gray-800 rounded-lg max-h-64 overflow-auto z-50">
                    </div>
                </div>

                <!-- Beschrijving -->
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Beschrijving</label>
                    <textarea name="description" rows="5"
                              class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2"></textarea>
                </div>

                <!-- Korte beschrijving -->
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Korte beschrijving</label>
                    <textarea name="short_description" rows="3"
                              class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2"></textarea>
                </div>

                <!-- KNOPPEN -->
                <div class="flex justify-between pt-4">
                    <button class="px-6 py-3 bg-green-600 hover:bg-green-700 rounded-lg font-semibold">
                        ✔ Product invoegen
                    </button>
                    <a href="/admin/pages/winkel/producten/"
                       class="px-6 py-3 border border-gray-700 rounded-lg hover:bg-gray-800">
                        Annuleren
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>



<!-- SCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const cat = document.getElementById('category');
        const sub = document.getElementById('sub_category');
        const sku = document.getElementById('sku');
        const next = document.getElementById('sku_numeric').value;

        fetch('/admin/functions/shop/products/get_categories.php')
            .then(r => r.json())
            .then(list => {
                cat.innerHTML = '<option value="">-- Kies categorie --</option>';
                list.forEach(c => {
                    let o = document.createElement('option');
                    o.value = c.id; o.textContent = c.name;
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
                const name = prompt("Naam nieuwe subcategorie:");
                if (!name) return;

                fetch('/admin/functions/shop/products/add_subcategory.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({name, parent_id: cat.value})
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            let o = new Option(data.name, data.slug, true, true);
                            sub.add(o);
                        }
                    });
            }
        });

        const uploader = document.getElementById('imageUploader');
        const preview = document.getElementById('selectedImage');
        uploader.addEventListener('change', () => {
            const f = uploader.files[0];
            if (!f) return;
            const r = new FileReader();
            r.onload = e => preview.src = e.target.result;
            r.readAsDataURL(f);
        });

        const parentSearch = document.getElementById('parent_search');
        const parentId     = document.getElementById('parent_id');
        const resultsBox   = document.getElementById('parent_results');

        let timer = null;

        parentSearch.addEventListener('input', () => {
            const q = parentSearch.value.trim();
            resultsBox.innerHTML = '';
            resultsBox.style.display = 'none';
            parentId.value = '';

            if (q.length < 2) return;

            clearTimeout(timer);
            timer = setTimeout(() => {
                fetch(`/admin/functions/shop/products/search_parent_products.php?q=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(items => {
                        resultsBox.innerHTML = '';
                        if (!items.length) return;

                        items.forEach(item => {
                            const el = document.createElement('div');
                            el.className = 'px-3 py-2 hover:bg-gray-800 cursor-pointer';
                            el.textContent = `${item.sku} — ${item.name}`;
                            el.onclick = () => {
                                parentSearch.value = el.textContent;
                                parentId.value = item.id;
                                resultsBox.style.display = 'none';
                            };
                            resultsBox.appendChild(el);
                        });

                        resultsBox.style.display = 'block';
                    });
            }, 200);
        });

    });
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'); ?>
