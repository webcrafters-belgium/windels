<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');
include($_SERVER['DOCUMENT_ROOT'] . '/header.php');

// SKU voor nieuw product
$getSku = "SELECT MAX(CAST(SUBSTRING_INDEX(sku, '-', -1) AS UNSIGNED)) AS sku FROM products";
$skuResult = $conn->query($getSku);

// Beginstand als er nog geen producten zijn
$nextNumber = 10001;

if ($skuResult && $skuResult->num_rows) {
    $lastSku = (int) $skuResult->fetch_assoc()['sku'];
    if ($lastSku > 0) {
        $nextNumber = $lastSku + 1;
    }
}


$skuNumericPart = $nextNumber;

?>

    <div class="container mt-5 add-products-container">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h3>Nieuw Product Invoegen</h3>
            </div>
            <div class="card-body">
                <form action="/admin/functions/shop/products/insert_product.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category">Hoofdcategorie</label>
                            <select class="form-control" id="category" name="category" required></select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sub_category">Subcategorie</label>
                            <select class="form-control" id="sub_category" name="sub_category" disabled required>
                                <option value="" disabled selected>Kies een subcategorie</option>
                                <option value="__new__">➕ Nieuwe subcategorie toevoegen...</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sku">SKU Nummer</label>
                            <input type="text" class="form-control" id="sku" name="sku" readonly placeholder="Wordt gegenereerd na keuze categorie">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name">Titel</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <br>
                            <label for="product_image">Afbeelding</label><br>

                            <input type="file" id="imageUploader" name="product_image" required>
                            <img id="selectedImage" src="/images/products/placeholder.png" alt="Geen afbeelding gekozen" class="img-thumbnail" style="max-width: 100px;">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stock">Voorraad</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="price">Prijs</label>
                            <input type="text" class="form-control" id="price" name="price" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type">Producttype</label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="simple" selected>Eenvoudig</option>
                                <option value="variable">Variabel</option>
                                <option value="custom">Op maat</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="stock_status">Voorraadstatus</label>
                            <select class="form-control" id="stock_status" name="stock_status" required>
                                <option value="instock" selected>Op voorraad</option>
                                <option value="outofstock">Niet op voorraad</option>
                                <option value="backorder">Backorder</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="regular_price">Normale prijs</label>
                            <input type="text" class="form-control" id="regular_price" name="regular_price" placeholder="bv. 24.95">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sale_price">Actieprijs (optioneel)</label>
                            <input type="text" class="form-control" id="sale_price" name="sale_price" placeholder="bv. 19.95">
                        </div>


                        <div class="col-12 mb-3">
                            <label for="description">Product Beschrijving</label>
                            <textarea class="form-control" id="description" name="description" rows="5" placeholder="Productbeschrijving"></textarea>

                            <label class="mt-5" for="short-description">Korte Beschrijving</label>
                            <textarea class="form-control" id="short-description" name="short-description" rows="5" placeholder="Korte beschrijving. Optioneel..."></textarea>
                        </div>

                        <div class="col-12 d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">✅ Product Invoegen</button>
                            <a href=".." class="btn btn-outline-secondary">Annuleren</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categorySelect = document.getElementById('category');
            const subCategorySelect = document.getElementById('sub_category');
            const skuInput = document.getElementById('sku');
            const originalSku = <?= $skuNumericPart ?>; // Reeds +1 gedaan in PHP

            // 🟢 Haal alle hoofdcategorieën op
            fetch('/admin/functions/shop/products/get_categories.php')
                .then(response => response.json())
                .then(data => {
                    categorySelect.innerHTML = '<option value="">-- Kies een categorie --</option>';
                    data.forEach(cat => {
                        const opt = document.createElement('option');
                        opt.value = cat.id;
                        opt.textContent = cat.name;
                        categorySelect.appendChild(opt);
                    });
                });

            // 🟢 Wanneer een categorie gekozen wordt
            categorySelect.addEventListener('change', function () {
                const selectedCategoryId = this.value;
                if (!selectedCategoryId) return;

                // Genereer SKU als: 4-10048
                skuInput.value = `${selectedCategoryId}-${originalSku}`;
                subCategorySelect.disabled = false;

                // Haal subcategorieën op
                fetch(`/admin/functions/shop/products/get_subcategories.php?parent_id=${selectedCategoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        subCategorySelect.innerHTML = '<option value="" disabled selected>Kies een subcategorie</option>';
                        data.forEach(sub => {
                            const opt = document.createElement('option');
                            opt.value = sub.value;
                            opt.textContent = sub.text;
                            subCategorySelect.appendChild(opt);
                        });
                        subCategorySelect.appendChild(new Option('➕ Nieuwe subcategorie toevoegen...', '__new__'));
                    });
            });

            // 🟢 Subcategorie toevoegen
            subCategorySelect.addEventListener('change', function () {
                if (this.value === '__new__') {
                    const newSub = prompt("Geef de naam van de nieuwe subcategorie op:");
                    const parentId = categorySelect.value;

                    if (!newSub || !parentId) {
                        alert("Voer een naam in én kies eerst een hoofdcategorie.");
                        this.value = '';
                        return;
                    }

                    fetch('/admin/functions/shop/products/add_subcategory.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ name: newSub, parent_id: parentId })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const option = new Option(data.name, data.slug, true, true);
                                this.add(option);
                            } else {
                                alert(data.message || "Fout bij toevoegen.");
                                this.value = '';
                            }
                        });
                }
            });

            // 🟢 Preview gekozen afbeelding
            const imageInput = document.getElementById('imageUploader');
            const imagePreview = document.getElementById('selectedImage');

            imageInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        });
    </script>

<!-- Todo: Tabeldata aanpassen naar:
         name 	slug 	sku 	type 	description 	short_description 	price 	regular_price 	sale_price 	stock_quantity 	stock_status 	created_at 	updated_at
-->

<?php include($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); ?>