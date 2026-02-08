<?php
error_reporting(E_ALL ^ (E_NOTICE ^ E_WARNING));
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$product_id) {
    ?>
    <div class="space-y-6 mt-6">
        <section class="card-glass p-6 space-y-4">
            <p class="text-lg font-semibold text-rose-300">Geen product geselecteerd.</p>
            <p class="text-sm text-gray-300">Kies eerst een product uit de lijst voordat je verder gaat met bewerken.</p>
            <a href="/admin/pages/products/index.php" class="accent-bg text-white px-5 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Terug naar producten
            </a>
        </section>
    </div>
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    ?>
    <div class="space-y-6 mt-6">
        <section class="card-glass p-6 space-y-4">
            <p class="text-lg font-semibold text-rose-300">Product niet gevonden.</p>
            <p class="text-sm text-gray-300">Het productbestand bestaat niet meer of werd verwijderd.</p>
            <a href="/admin/pages/products/index.php" class="glass px-5 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Terug naar producten
            </a>
        </section>
    </div>
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php';
    exit;
}

$subResult = $conn->query("SELECT s.id, s.name FROM subcategories s INNER JOIN product_subcategories ps ON ps.subcategory_id = s.id WHERE ps.product_id = $product_id LIMIT 1");
$subcat = $subResult->fetch_assoc();
$subcategory_id = $subcat['id'] ?? '';

$catResult = $conn->query("SELECT c.id, c.name FROM categories c INNER JOIN product_categories pc ON pc.category_id = c.id WHERE pc.product_id = $product_id LIMIT 1");
$cat = $catResult->fetch_assoc();
$category_id = $cat['id'] ?? '';

$categoryStmt = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
$subcategories = $conn->query("SELECT id, name FROM subcategories ORDER BY name ASC");

$imageStmt = $conn->prepare("SELECT image_path, webp_path FROM product_images WHERE product_id = ? LIMIT 1");
$imageStmt->bind_param("i", $product_id);
$imageStmt->execute();
$imageResult = $imageStmt->get_result()->fetch_assoc();
$imageStmt->close();
$currentImage = '/images/products/placeholder.png';
if ($imageResult) {
    $currentImage = $imageResult['webp_path'] ?: $imageResult['image_path'] ?: $currentImage;
}

$lastUpdated = '';
if (!empty($product['updated_at'])) {
    $lastUpdated = (new DateTime($product['updated_at']))->format('d-m-Y H:i');
}

$showSuccess = isset($_GET['updated']) && $_GET['updated'] === '1';
?>

<div class="space-y-6">
    <section class="card-glass p-6 space-y-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-widest text-teal-400 mb-1">Productbeheer</p>
                <h1 class="text-3xl font-bold glow-text">Product bewerken</h1>
                <p class="text-sm text-gray-300 mt-1">SKU <?= htmlspecialchars($product['sku']) ?> — <?= htmlspecialchars($product['name']) ?></p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="/admin/pages/products/index.php" class="glass px-5 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Terug naar producten
                </a>
                <?php if ($lastUpdated): ?>
                    <span class="px-4 py-3 rounded-xl bg-emerald-500/20 text-emerald-200 text-sm font-medium border border-emerald-500/30">
                        Laatst bijgewerkt: <?= $lastUpdated ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if ($showSuccess): ?>
        <div id="updateSuccessToast" class="glass fixed top-24 right-6 z-50 px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3">
            <i class="bi bi-check-circle text-emerald-400 text-2xl"></i>
            <div>
                <p class="font-semibold text-emerald-100">Product succesvol bijgewerkt</p>
                <p class="text-xs text-emerald-200">De gegevens werden opgeslagen in de database.</p>
            </div>
            <button id="closeUpdateToast" class="ml-auto text-sm text-emerald-300 hover:text-emerald-100 transition">Sluiten</button>
        </div>
    <?php endif; ?>

    <section class="card-glass p-6 space-y-6">
        <form action="/admin/functions/shop/products/update_product.php" method="post" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Categorie</label>
                    <select name="category" class="w-full px-4 py-3 rounded-xl glass border text-lg transition-theme" style="border-color: var(--border-glass);">
                        <option value="">-- Kies een categorie --</option>
                        <?php while ($row = $categoryStmt->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>" <?= ($category_id == $row['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Subcategorie</label>
                    <select name="sub_category" class="w-full px-4 py-3 rounded-xl glass border text-lg transition-theme" style="border-color: var(--border-glass);">
                        <option value="">-- Kies een subcategorie --</option>
                        <?php while ($row = $subcategories->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>" <?= ($subcategory_id == $row['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">SKU</label>
                    <input type="text" name="sku" value="<?= htmlspecialchars($product['sku']) ?>" readonly
                           class="w-full px-4 py-3 rounded-xl glass border text-lg font-mono" style="border-color: var(--border-glass);">
                </div>

                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Naam</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required
                           class="w-full px-4 py-3 rounded-xl glass border text-lg" style="border-color: var(--border-glass);">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Prijs (&#8364;)</label>
                    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>"
                           class="w-full px-4 py-3 rounded-xl glass border text-lg" style="border-color: var(--border-glass);">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Voorraad</label>
                    <input type="number" name="stock" value="<?= $product['stock_quantity'] ?>"
                           class="w-full px-4 py-3 rounded-xl glass border text-lg" style="border-color: var(--border-glass);">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Toon titel</label>
                    <span class="px-4 py-3 rounded-xl glass border text-lg text-gray-300 font-semibold" style="border-color: var(--border-glass);">
                        <?= htmlspecialchars($product['name']) ?>
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Omschrijving</label>
                    <textarea id="description" name="description" rows="5" class="w-full px-4 py-3 rounded-xl glass border text-lg transition-theme" style="border-color: var(--border-glass);"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Nieuwe afbeelding (optioneel)</label>
                    <input type="file" name="image_file" id="image_file" accept="image/*"
                           class="w-full text-sm text-gray-300">
                    <p class="text-xs text-gray-400 mt-2">Laat leeg om de bestaande afbeelding te behouden.</p>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Huidige afbeelding</label>
                    <div class="rounded-xl border border-white/10 overflow-hidden">
                        <img id="currentImagePreview" src="<?= htmlspecialchars($currentImage) ?>" alt="Huidige afbeelding"
                             class="w-full h-52 object-cover transition-theme" style="border-color: var(--border-glass);">
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-end gap-3 pt-3">
                <a href="/admin/pages/products/index.php" class="px-6 py-3 rounded-xl border border-white/20 glass-hover font-semibold text-sm">
                    <i class="bi bi-x-circle mr-2"></i> Annuleren
                </a>
                <button type="submit" class="accent-bg text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2 shadow-lg">
                    <i class="bi bi-check-circle"></i> Wijzigingen opslaan
                </button>
            </div>
        </form>
    </section>
</div>

<script src="https://cdn.tiny.cloud/1/zje5a800hqt96bgn4ex8jhddjx4tpnoht9c98reetr69igg6/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    if (window.tinymce) {
        tinymce.init({
            selector: '#description',
            height: 320,
            menubar: false,
            branding: false,
            plugins: 'lists link',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | link | removeformat',
            content_style: 'body { font-family: \"Outfit\", system-ui, sans-serif; font-size:14px; color:#0f172a; }'
        });
    }

    const imageInput = document.getElementById('image_file');
    const imagePreview = document.getElementById('currentImagePreview');

    imageInput?.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (!file || !imagePreview) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    <?php if ($showSuccess): ?>
    const successToast = document.getElementById('updateSuccessToast');
    const closeToast = document.getElementById('closeUpdateToast');
    if (successToast) {
        const hideToast = () => successToast.classList.add('opacity-0', 'pointer-events-none');
        setTimeout(hideToast, 3200);
        setTimeout(() => successToast.remove(), 3800);
        closeToast?.addEventListener('click', () => successToast.remove());
    }
    <?php endif; ?>
</script>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
