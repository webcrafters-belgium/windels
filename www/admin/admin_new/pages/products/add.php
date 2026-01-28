<?php
require_once '../../includes/header.php';

// Get next SKU
$result = $conn->query("SELECT MAX(CAST(SUBSTRING_INDEX(sku, '-', -1) AS UNSIGNED)) AS max_sku FROM products");
$row = $result->fetch_assoc();
$nextNumber = ($row['max_sku'] ?? 10000) + 1;

// Get categories
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
?>

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">
        <i class="bi bi-plus-circle accent-primary mr-3"></i>Nieuw Product Toevoegen
    </h1>
    <p class="text-lg" style="color: var(--text-muted);">Vul alle velden in om een nieuw product toe te voegen</p>
</div>

<!-- Form Card -->
<form id="productForm" action="/admin_new/functions/products/save.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
    <input type="hidden" name="action" value="create">
    
    <!-- Basic Information Section -->
    <div class="card-glass p-8 mb-6">
        <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: var(--text-primary);">
            <i class="bi bi-info-circle accent-primary mr-3"></i>
            Basisinformatie
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Product Type -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Product Type <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="product_type" value="candle" class="peer hidden" required onchange="updateMaterialFields()">
                        <div class="card-glass p-6 glass-hover border-2 peer-checked:border-yellow-500 peer-checked:bg-yellow-500/10 text-center">
                            <i class="bi bi-fire text-4xl text-yellow-500 mb-2 block"></i>
                            <span class="font-bold text-lg">Kaarsen</span>
                            <p class="text-sm mt-2" style="color: var(--text-muted);">Stearine & Paraffine</p>
                        </div>
                    </label>
                    
                    <label class="cursor-pointer">
                        <input type="radio" name="product_type" value="terrazzo" class="peer hidden" onchange="updateMaterialFields()">
                        <div class="card-glass p-6 glass-hover border-2 peer-checked:border-purple-500 peer-checked:bg-purple-500/10 text-center">
                            <i class="bi bi-circle-square text-4xl text-purple-500 mb-2 block"></i>
                            <span class="font-bold text-lg">Terrazzo</span>
                            <p class="text-sm mt-2" style="color: var(--text-muted);">Terrazzo Poeder</p>
                        </div>
                    </label>
                    
                    <label class="cursor-pointer">
                        <input type="radio" name="product_type" value="epoxy" class="peer hidden" onchange="updateMaterialFields()">
                        <div class="card-glass p-6 glass-hover border-2 peer-checked:border-blue-500 peer-checked:bg-blue-500/10 text-center">
                            <i class="bi bi-droplet text-4xl text-blue-500 mb-2 block"></i>
                            <span class="font-bold text-lg">Epoxy</span>
                            <p class="text-sm mt-2" style="color: var(--text-muted);">Epoxy Hars</p>
                        </div>
                    </label>
                    
                    <label class="cursor-pointer">
                        <input type="radio" name="product_type" value="other" class="peer hidden" onchange="updateMaterialFields()">
                        <div class="card-glass p-6 glass-hover border-2 peer-checked:border-gray-500 peer-checked:bg-gray-500/10 text-center">
                            <i class="bi bi-three-dots text-4xl text-gray-500 mb-2 block"></i>
                            <span class="font-bold text-lg">Overig</span>
                            <p class="text-sm mt-2" style="color: var(--text-muted);">Geen materialen</p>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Category -->
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Categorie <span class="text-red-500">*</span>
                </label>
                <select name="category_id" id="category" required 
                        class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                    <option value="">-- Kies categorie --</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <!-- SKU -->
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    SKU <span class="text-red-500">*</span>
                </label>
                <input type="text" name="sku" id="sku" value="" readonly required
                       class="w-full px-4 py-3 rounded-lg glass border text-lg font-mono" style="border-color: var(--border-glass);">
                <input type="hidden" id="next_number" value="<?= $nextNumber ?>">
            </div>
            
            <!-- Product Name -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Product Naam <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" required
                       class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                       placeholder="Bijv. Handgemaakte Soja Kaars Lavendel">
            </div>
            
            <!-- Slug -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    URL Slug <span class="text-red-500">*</span>
                </label>
                <input type="text" name="slug" required
                       class="w-full px-4 py-3 rounded-lg glass border text-lg font-mono" style="border-color: var(--border-glass);"
                       placeholder="handgemaakte-soja-kaars-lavendel">
                <p class="text-sm mt-1" style="color: var(--text-muted);">Unieke URL-vriendelijke naam (geen spaties, kleine letters)</p>
            </div>
            
            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Beschrijving
                </label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                          placeholder="Volledige productbeschrijving..."></textarea>
            </div>
            
            <!-- Short Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Korte Beschrijving
                </label>
                <textarea name="short_description" rows="2"
                          class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                          placeholder="Korte samenvatting voor productlijsten..."></textarea>
            </div>
        </div>
    </div>
    
    <!-- Materials Section (Dynamic) -->
    <div id="materialsSection" class="card-glass p-8 mb-6 hidden">
        <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: var(--text-primary);">
            <i class="bi bi-bucket accent-primary mr-3"></i>
            <span id="materialsSectionTitle">Materialen</span>
        </h2>
        
        <!-- Candle Materials -->
        <div id="candleMaterials" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-fire text-yellow-500 mr-2"></i>
                        Stearine (gram)
                    </label>
                    <input type="number" name="stearine_grams" step="0.01" min="0" value="0"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                           placeholder="0.00">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-fire text-yellow-500 mr-2"></i>
                        Paraffine (gram)
                    </label>
                    <input type="number" name="paraffine_grams" step="0.01" min="0" value="0"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                           placeholder="0.00">
                </div>
            </div>
        </div>
        
        <!-- Terrazzo Materials -->
        <div id="terrazzoMaterials" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-circle-square text-purple-500 mr-2"></i>
                        Terrazzo Poeder (gram)
                    </label>
                    <input type="number" name="terrazzo_powder_grams" step="0.01" min="0" value="0"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                           placeholder="0.00">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-journal-text text-purple-500 mr-2"></i>
                        Toelichting / Receptuur
                    </label>
                    <input type="text" name="terrazzo_notes"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                           placeholder="Optionele notities...">
                </div>
            </div>
        </div>
        
        <!-- Epoxy Materials -->
        <div id="epoxyMaterials" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-droplet text-blue-500 mr-2"></i>
                        Epoxy (gram)
                    </label>
                    <input type="number" name="epoxy_grams" step="0.01" min="0" value="0"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                           placeholder="0.00">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pricing & Stock Section -->
    <div class="card-glass p-8 mb-6">
        <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: var(--text-primary);">
            <i class="bi bi-currency-euro accent-primary mr-3"></i>
            Prijs & Voorraad
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Price -->
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Verkoopprijs (€) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="price" step="0.01" min="0" required
                       class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                       placeholder="0.00">
            </div>
            
            <!-- Regular Price -->
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Normale Prijs (€)
                </label>
                <input type="number" name="regular_price" step="0.01" min="0"
                       class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                       placeholder="0.00">
            </div>
            
            <!-- Sale Price -->
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Actieprijs (€)
                </label>
                <input type="number" name="sale_price" step="0.01" min="0"
                       class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                       placeholder="0.00">
                <p class="text-sm mt-1" style="color: var(--text-muted);">Indien ingevuld, wordt deze prijs getoond</p>
            </div>
            
            <!-- Stock Quantity -->
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Voorraad <span class="text-red-500">*</span>
                </label>
                <input type="number" name="stock_quantity" min="0" required value="0"
                       class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                       placeholder="0">
            </div>
            
            <!-- Stock Status -->
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Voorraadstatus <span class="text-red-500">*</span>
                </label>
                <select name="stock_status" required
                        class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                    <option value="instock">Op voorraad</option>
                    <option value="outofstock">Niet op voorraad</option>
                    <option value="onbackorder">Backorder</option>
                </select>
            </div>
            
            <!-- Type -->
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Product Variant Type
                </label>
                <select name="type"
                        class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                    <option value="simple">Eenvoudig</option>
                    <option value="variable">Variabel</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Images Section -->
    <div class="card-glass p-8 mb-6">
        <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: var(--text-primary);">
            <i class="bi bi-image accent-primary mr-3"></i>
            Product Afbeelding
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Hoofdafbeelding <span class="text-red-500">*</span>
                </label>
                <input type="file" name="product_image" id="imageInput" accept="image/*" required
                       class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                <p class="text-sm mt-1" style="color: var(--text-muted);">Aanbevolen: 800x800px, JPG of PNG</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    Voorbeeld
                </label>
                <img id="imagePreview" src="/images/products/placeholder.png" 
                     class="w-48 h-48 object-cover rounded-lg border" style="border-color: var(--border-glass);">
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex items-center justify-between gap-4">
        <a href="/admin_new/pages/products/index.php" 
           class="px-8 py-4 rounded-lg glass-hover font-bold text-lg">
            <i class="bi bi-x-circle mr-2"></i>
            Annuleren
        </a>
        
        <button type="submit" id="submitBtn"
                class="accent-bg text-white px-8 py-4 rounded-lg font-bold text-lg hover:opacity-90 transition">
            <i class="bi bi-check-circle mr-2"></i>
            Product Opslaan
        </button>
    </div>
</form>

<script>
// Update SKU when category changes
document.getElementById('category').addEventListener('change', function() {
    const categoryId = this.value;
    const nextNumber = document.getElementById('next_number').value;
    if (categoryId) {
        document.getElementById('sku').value = `${categoryId}-${nextNumber}`;
    } else {
        document.getElementById('sku').value = '';
    }
});

// Image preview
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Update material fields based on product type
function updateMaterialFields() {
    const productType = document.querySelector('input[name="product_type"]:checked')?.value;
    const materialsSection = document.getElementById('materialsSection');
    const candleMaterials = document.getElementById('candleMaterials');
    const terrazzoMaterials = document.getElementById('terrazzoMaterials');
    const epoxyMaterials = document.getElementById('epoxyMaterials');
    const title = document.getElementById('materialsSectionTitle');
    
    // Hide all
    candleMaterials.classList.add('hidden');
    terrazzoMaterials.classList.add('hidden');
    epoxyMaterials.classList.add('hidden');
    
    // Show relevant section
    if (productType === 'candle') {
        materialsSection.classList.remove('hidden');
        candleMaterials.classList.remove('hidden');
        title.textContent = 'Kaarsen Materialen';
    } else if (productType === 'terrazzo') {
        materialsSection.classList.remove('hidden');
        terrazzoMaterials.classList.remove('hidden');
        title.textContent = 'Terrazzo Materialen';
    } else if (productType === 'epoxy') {
        materialsSection.classList.remove('hidden');
        epoxyMaterials.classList.remove('hidden');
        title.textContent = 'Epoxy Materialen';
    } else {
        materialsSection.classList.add('hidden');
    }
}

// Form submission
document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split mr-2"></i>Bezig met opslaan...';
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Product succesvol toegevoegd!');
            window.location.href = '/admin_new/pages/products/index.php';
        } else {
            alert('❌ Fout: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-circle mr-2"></i>Product Opslaan';
        }
    })
    .catch(error => {
        alert('❌ Er is een fout opgetreden: ' + error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-check-circle mr-2"></i>Product Opslaan';
    });
});
</script>

<?php require_once '../../includes/footer.php'; ?>
