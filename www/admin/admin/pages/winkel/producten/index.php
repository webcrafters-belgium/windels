<?php
require_once __DIR__ . '/../../../includes/header.php';

$nu = new DateTime('now', new DateTimeZone('Europe/Brussels'));
$open = new DateTime('19:00', new DateTimeZone('Europe/Brussels'));
$sluit = new DateTime('21:00', new DateTimeZone('Europe/Brussels'));
$openStatus = ($nu >= $open && $nu <= $sluit) 
    ? '<span class="text-emerald-400"><i class="bi bi-circle-fill text-xs mr-1"></i>Nu open</span>' 
    : '<span class="text-rose-400"><i class="bi bi-circle-fill text-xs mr-1"></i>Gesloten</span> <span class="text-sm" style="color: var(--text-muted);">(open 19:00 - 21:00)</span>';

// Filter
$selectedCategory = $_GET['category'] ?? '';
$whereClause = '';

if ($selectedCategory === 'none') {
    $whereClause = "WHERE pc.category_id IS NULL";
} elseif (is_numeric($selectedCategory) && (int)$selectedCategory > 0) {
    $selectedCategory = (int)$selectedCategory;
    $whereClause = "WHERE pc.category_id = $selectedCategory";
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$totalQuery = $conn->query("SELECT COUNT(DISTINCT p.id) AS total FROM products p LEFT JOIN product_categories pc ON pc.product_id = p.id $whereClause");
$totalRows = $totalQuery->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$productQuery = "
    SELECT p.*,
        c.name AS category_name,
        s.name AS subcategory_name,
        COALESCE(NULLIF(pi.webp_path,''), NULLIF(pi.image_path,''), '/images/products/placeholder.png') AS image_url
    FROM products p
    LEFT JOIN product_categories pc ON pc.product_id = p.id
    LEFT JOIN categories c ON c.id = pc.category_id
    LEFT JOIN product_subcategories ps ON ps.product_id = p.id
    LEFT JOIN subcategories s ON s.id = ps.subcategory_id
    LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_main = 1
    {$whereClause}
    GROUP BY p.id
    ORDER BY p.updated_at DESC
    LIMIT {$limit} OFFSET {$offset}
";

$productResult = $conn->query($productQuery);
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
?>

<!-- Main Content -->
<div class="max-w-7xl mx-auto animate-fadeInUp">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold glow-text">Winkel Producten</h1>
            <p class="text-sm mt-1" style="color: var(--text-muted);">
                <?= $nu->format('l d F Y') ?> • <?= $openStatus ?>
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="/admin/pages/winkel/" 
               class="glass px-4 py-2 rounded-xl flex items-center space-x-2 hover:bg-white/10 transition">
                <i class="bi bi-arrow-left"></i>
                <span>Terug</span>
            </a>
            <a href="/admin/pages/winkel/producten/add" 
               class="accent-bg px-5 py-2 rounded-xl font-semibold text-white flex items-center space-x-2"
               data-testid="add-product-btn">
                <i class="bi bi-plus-circle"></i>
                <span>Toevoegen</span>
            </a>
        </div>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400">
            <i class="bi bi-check-circle mr-2"></i>Product succesvol toegevoegd!
        </div>
    <?php endif; ?>

    <!-- Filter & Info -->
    <div class="card-glass p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <p class="text-sm" style="color: var(--text-muted);">
                    <strong>SKU opbouw:</strong> 2 = epoxy/terrazzo, 1 = vers, 3 = kaarsen, 4 = inkoop
                </p>
            </div>
            <form class="flex items-center space-x-3" method="get">
                <select name="category" 
                        onchange="this.form.submit()"
                        class="px-4 py-2 rounded-xl glass border border-transparent focus:border-teal-500 transition"
                        style="background: var(--bg-glass); color: var(--text-primary);">
                    <option value="">Alle categorieën</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>" <?= $selectedCategory == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endwhile; ?>
                    <option value="none" <?= $selectedCategory === 'none' ? 'selected' : '' ?>>⚠️ Zonder categorie</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card-glass overflow-hidden">
        <div class="p-4 border-b" style="border-color: var(--border-glass);">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold">Producten (pagina <?= $page ?>)</h2>
                <span class="text-sm px-3 py-1 rounded-full glass" style="color: var(--text-muted);">
                    <?= $totalRows ?> totaal
                </span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm" style="color: var(--text-muted); background: var(--bg-glass);">
                        <th class="px-4 py-3 font-medium">SKU</th>
                        <th class="px-4 py-3 font-medium">Product</th>
                        <th class="px-4 py-3 font-medium">Categorie</th>
                        <th class="px-4 py-3 font-medium">Prijs</th>
                        <th class="px-4 py-3 font-medium">Inkoop</th>
                        <th class="px-4 py-3 font-medium text-right">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($productResult && $productResult->num_rows > 0): ?>
                        <?php while ($product = $productResult->fetch_assoc()): ?>
                            <tr class="border-t transition-colors hover:bg-white/5" style="border-color: var(--border-glass);">
                                <td class="px-4 py-3">
                                    <span class="font-mono text-sm px-2 py-1 rounded bg-white/5">
                                        <?= htmlspecialchars($product['sku'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        <?php
                                        $imgPath = $product['image_url'] ?? '/images/products/placeholder.png';
                                        $absPath = $_SERVER['DOCUMENT_ROOT'] . $imgPath;
                                        if (!file_exists($absPath)) {
                                            $imgPath = '/images/products/placeholder.png';
                                        }
                                        ?>
                                        <img src="<?= htmlspecialchars($imgPath) ?>" 
                                             alt="" 
                                             class="w-10 h-10 rounded-lg object-cover">
                                        <span class="font-medium"><?= htmlspecialchars($product['name'] ?? '') ?></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if (!empty($product['category_name'])): ?>
                                        <span class="px-2 py-1 rounded-lg text-sm bg-teal-500/20 text-teal-400">
                                            <?= htmlspecialchars($product['category_name']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 rounded-lg text-sm bg-amber-500/20 text-amber-400">
                                            Geen
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 font-medium">
                                    €<?= number_format($product['price'] ?? 0, 2, ',', '.') ?>
                                </td>
                                <td class="px-4 py-3" style="color: var(--text-muted);">
                                    <?= isset($product['purchase_price']) ? '€' . number_format($product['purchase_price'], 2, ',', '.') : '-' ?>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="/admin/pages/winkel/producten/edit_product.php?id=<?= $product['id'] ?>" 
                                           class="p-2 rounded-lg bg-amber-500/20 text-amber-400 hover:bg-amber-500/30 transition"
                                           title="Bewerken">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/admin/pages/winkel/producten/delete_product.php?id=<?= $product['id'] ?>" 
                                           class="p-2 rounded-lg bg-rose-500/20 text-rose-400 hover:bg-rose-500/30 transition"
                                           title="Verwijderen"
                                           onclick="return confirm('Weet je zeker dat je dit product wilt verwijderen?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center" style="color: var(--text-muted);">
                                <i class="bi bi-inbox text-4xl mb-3 block opacity-50"></i>
                                <p>Geen producten gevonden</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="p-4 border-t flex flex-col md:flex-row justify-between items-center gap-4" style="border-color: var(--border-glass);">
                <p class="text-sm" style="color: var(--text-muted);">
                    Toon <?= ($offset + 1) ?> - <?= min($offset + $limit, $totalRows) ?> van <?= $totalRows ?>
                </p>
                <div class="flex items-center space-x-2">
                    <a href="?page=1&category=<?= $selectedCategory ?>" 
                       class="px-3 py-1 rounded-lg glass <?= ($page <= 1) ? 'opacity-50 pointer-events-none' : 'hover:bg-white/10' ?> transition">
                        <i class="bi bi-chevron-double-left"></i>
                    </a>
                    <a href="?page=<?= $page - 1 ?>&category=<?= $selectedCategory ?>" 
                       class="px-3 py-1 rounded-lg glass <?= ($page <= 1) ? 'opacity-50 pointer-events-none' : 'hover:bg-white/10' ?> transition">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <span class="px-4 py-1 rounded-lg accent-bg text-white font-medium"><?= $page ?></span>
                    <a href="?page=<?= $page + 1 ?>&category=<?= $selectedCategory ?>" 
                       class="px-3 py-1 rounded-lg glass <?= ($page >= $totalPages) ? 'opacity-50 pointer-events-none' : 'hover:bg-white/10' ?> transition">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="?page=<?= $totalPages ?>&category=<?= $selectedCategory ?>" 
                       class="px-3 py-1 rounded-lg glass <?= ($page >= $totalPages) ? 'opacity-50 pointer-events-none' : 'hover:bg-white/10' ?> transition">
                        <i class="bi bi-chevron-double-right"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
