<?php
require_once '../../includes/header.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$productId) {
    header('Location: /admin/pages/products/index.php');
    exit;
}

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param('i', $productId);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    header('Location: /admin/pages/products/index.php');
    exit;
}

// Fetch product category
$stmt = $conn->prepare("SELECT category_id FROM product_categories WHERE product_id = ? LIMIT 1");
$stmt->bind_param('i', $productId);
$stmt->execute();
$productCategory = $stmt->get_result()->fetch_assoc();
$stmt->close();
$currentCategoryId = $productCategory['category_id'] ?? 0;

// Fetch product materials
$stmt = $conn->prepare("SELECT * FROM product_materials WHERE product_id = ?");
$stmt->bind_param('i', $productId);
$stmt->execute();
$materialsResult = $stmt->get_result();
$materials = [];
while ($mat = $materialsResult->fetch_assoc()) {
    $materials[$mat['material_type']] = $mat;
}
$stmt->close();

// Fetch product image
$stmt = $conn->prepare("SELECT image_path FROM product_images WHERE product_id = ? AND is_main = 1 LIMIT 1");
$stmt->bind_param('i', $productId);
$stmt->execute();
$imageResult = $stmt->get_result()->fetch_assoc();
$currentImage = $imageResult['image_path'] ?? '/images/products/placeholder.png';
$stmt->close();

// Get categories
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">
                <i class="bi bi-pencil accent-primary mr-3"></i>Product Bewerken
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Bewerk product: <?= htmlspecialchars($product['name']) ?></p>
        </div>
        <a href="/admin/pages/products/index.php" class="glass px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
            <i class="bi bi-arrow-left mr-2"></i>Terug
        </a>
    </div>
</div>

<!-- Form Card -->
<form id="productForm" action="/admin/functions/products/save.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
    <input type="hidden" name="action" value="update">
    <input type="hidden" name="product_id" value="<?= $productId ?>">
    
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
                        <input type="radio" name="product_type" value="candle" class="peer hidden" 
                               <?= $product['product_type'] === 'candle' ? 'checked' : '' ?> required onchange="updateMaterialFields()">
                        <div class="card-glass p-6 glass-hover border-2 peer-checked:border-yellow-500 peer-checked:bg-yellow-500/10 text-center">
                            <i class="bi bi-fire text-4xl text-yellow-500 mb-2 block"></i>
                            <span class="font-bold text-lg">Kaarsen</span>
                        </div>
                    </label>
                    
                    <label class="cursor-pointer">
                        <input type="radio" name="product_type" value="terrazzo" class="peer hidden" 
                               <?= $product['product_type'] === 'terrazzo' ? 'checked' : '' ?> onchange="updateMaterialFields()">
                        <div class="card-glass p-6 glass-hover border-2 peer-checked:border-purple-500 peer-checked:bg-purple-500/10 text-center">
                            <i class="bi bi-circle-square text-4xl text-purple-500 mb-2 block"></i>
                            <span class="font-bold text-lg">Terrazzo</span>
                        </div>
                    </label>
                    
                    <label class="cursor-pointer">
                        <input type="radio" name="product_type" value="epoxy" class="peer hidden" 
                               <?= $product['product_type'] === 'epoxy' ? 'checked' : '' ?> onchange="updateMaterialFields()">
                        <div class="card-glass p-6 glass-hover border-2 peer-checked:border-blue-500 peer-checked:bg-blue-500/10 text-center">
                            <i class="bi bi-droplet text-4xl text-blue-500 mb-2 block"></i>
                            <span class="font-bold text-lg">Epoxy</span>
                        </div>
                    </label>
                    
                    <label class="cursor-pointer">
                        <input type="radio" name="product_type" value="other" class="peer hidden" 
                               <?= $product['product_type'] === 'other' ? 'checked' : '' ?> onchange="updateMaterialFields()">
                        <div class="card-glass p-6 glass-hover border-2 peer-checked:border-gray-500 peer-checked:bg-gray-500/10 text-center">
                            <i class="bi bi-three-dots text-4xl text-gray-500 mb-2 block"></i>
                            <span class="font-bold text-lg">Overig</span>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Category -->
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Categorie</label>
                <select name="category_id" class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                    <option value="">-- Kies categorie --</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>" <?= $currentCategoryId == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <!-- SKU -->
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">SKU</label>
                <input type="text" name="sku" value="<?= htmlspecialchars($product['sku']) ?>" required
                       class="w-full px-4 py-3 rounded-lg glass border text-lg font-mono" style="border-color: var(--border-glass);">
            </div>
            
            <!-- Product Name -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Product Naam <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required
                       class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
            </div>
            
            <!-- Slug -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">URL Slug</label>
                <input type="text" name="slug" value="<?= htmlspecialchars($product['slug']) ?>"
                       class="w-full px-4 py-3 rounded-lg glass border text-lg font-mono" style="border-color: var(--border-glass);">
            </div>
            
            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Beschrijving</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            
            <!-- Short Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Korte Beschrijving</label>
                <textarea name="short_description" rows="2"
                          class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"><?= htmlspecialchars($product['short_description'] ?? '') ?></textarea>
            </div>
        </div>
    </div>
    
    <!-- Materials Section (Dynamic) -->
    <div id="materialsSection" class="card-glass p-8 mb-6 <?= in_array($product['product_type'], ['candle', 'terrazzo', 'epoxy']) ? '' : 'hidden' ?>">
        <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: var(--text-primary);">
            <i class="bi bi-bucket accent-primary mr-3"></i>
            <span id="materialsSectionTitle">Materialen</span>
        </h2>
        
        <!-- Candle Materials -->
        <div id="candleMaterials" class="<?= $product['product_type'] === 'candle' ? '' : 'hidden' ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-fire text-yellow-500 mr-2"></i>Stearine (gram)
                    </label>
                    <input type="number" name="stearine_grams" step="0.01" min="0" 
                           value="<?= $materials['stearine']['grams'] ?? 0 ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-fire text-yellow-500 mr-2"></i>Paraffine (gram)
                    </label>
                    <input type="number" name="paraffine_grams" step="0.01" min="0" 
                           value="<?= $materials['paraffine']['grams'] ?? 0 ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                </div>
            </div>
        </div>
        
        <!-- Terrazzo Materials -->
        <div id="terrazzoMaterials" class="<?= $product['product_type'] === 'terrazzo' ? '' : 'hidden' ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-circle-square text-purple-500 mr-2"></i>Terrazzo Poeder (gram)
                    </label>
                    <input type="number" name="terrazzo_powder_grams" step="0.01" min="0" 
                           value="<?= $materials['terrazzo_powder']['grams'] ?? 0 ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-journal-text text-purple-500 mr-2"></i>Toelichting / Receptuur
                    </label>
                    <input type="text" name="terrazzo_notes" 
                           value="<?= htmlspecialchars($materials['terrazzo_powder']['notes'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                </div>
            </div>
        </div>
        
        <!-- Epoxy Materials -->
        <div id="epoxyMaterials" class="<?= $product['product_type'] === 'epoxy' ? '' : 'hidden' ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-droplet text-blue-500 mr-2"></i>Epoxy (gram)
                    </label>
                    <input type="number" name="epoxy_grams" step="0.01" min="0" 
                           value="<?= $materials['epoxy']['grams'] ?? 0 ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
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
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Verkoopprijs (€) <span class="text-red-500">*</span></label>
                <input type="number" name="price" step="0.01" min="0" required
                       value="<?= $product['price'] ?>"
                       class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Normale Prijs (€)</label>
                <input type="number" name="regular_price" step="0.01" min="0"
                       value="<?= $product['regular_price'] ?? '' ?>"
                       class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Actieprijs (€)</label>
                <input type="number" name="sale_price" step="0.01" min="0"
                       value="<?= $product['sale_price'] ?? '' ?>"
                       class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Voorraad <span class="text-red-500">*</span></label>
                <input type="number" name="stock_quantity" min="0" required 
                       value="<?= $product['stock_quantity'] ?>"
                       class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Voorraadstatus</label>
                <select name="stock_status" class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                    <option value="instock" <?= $product['stock_status'] === 'instock' ? 'selected' : '' ?>>Op voorraad</option>
                    <option value="outofstock" <?= $product['stock_status'] === 'outofstock' ? 'selected' : '' ?>>Niet op voorraad</option>
                    <option value="onbackorder" <?= $product['stock_status'] === 'onbackorder' ? 'selected' : '' ?>>Backorder</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Product Type</label>
                <select name="type" class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                    <option value="simple" <?= $product['type'] === 'simple' ? 'selected' : '' ?>>Eenvoudig</option>
                    <option value="variable" <?= $product['type'] === 'variable' ? 'selected' : '' ?>>Variabel</option>
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
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Nieuwe Afbeelding (optioneel)</label>
                <input type="file" name="product_image" id="imageInput" accept="image/*"
                       class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                <p class="text-sm mt-1" style="color: var(--text-muted);">Laat leeg om huidige afbeelding te behouden</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Huidige Afbeelding</label>
                <img id="imagePreview" src="<?= htmlspecialchars($currentImage) ?>" 
                     class="w-48 h-48 object-cover rounded-lg border" style="border-color: var(--border-glass);">
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex items-center justify-between gap-4">
        <a href="/admin/pages/products/index.php" class="px-8 py-4 rounded-lg glass-hover font-bold text-lg">
            <i class="bi bi-x-circle mr-2"></i>Annuleren
        </a>
        
        <button type="submit" id="submitBtn" class="accent-bg text-white px-8 py-4 rounded-lg font-bold text-lg hover:opacity-90 transition">
            <i class="bi bi-check-circle mr-2"></i>Wijzigingen Opslaan
        </button>
    </div>
</form>

<script>
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
    
    candleMaterials.classList.add('hidden');
    terrazzoMaterials.classList.add('hidden');
    epoxyMaterials.classList.add('hidden');
    
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
            alert('✅ Product succesvol bijgewerkt!');
            window.location.href = '/admin/pages/products/index.php';
        } else {
            alert('❌ Fout: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-circle mr-2"></i>Wijzigingen Opslaan';
        }
    })
    .catch(error => {
        alert('❌ Er is een fout opgetreden: ' + error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-check-circle mr-2"></i>Wijzigingen Opslaan';
    });
});

// Initialize on page load
updateMaterialFields();
</script>

<?php require_once '../../includes/footer.php'; ?>
