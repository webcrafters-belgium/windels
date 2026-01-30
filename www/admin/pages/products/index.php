<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

/* -------------------------------------------------------
   OPEN STATUS
-------------------------------------------------------- */
$nu = new DateTime('now', new DateTimeZone('Europe/Brussels'));
$open = new DateTime('19:00', new DateTimeZone('Europe/Brussels'));
$sluit = new DateTime('21:00', new DateTimeZone('Europe/Brussels'));
$openStatus = ($nu >= $open && $nu <= $sluit)
        ? '<span class="text-emerald-400"><i class="bi bi-circle-fill text-xs mr-1"></i>Nu open</span>'
        : '<span class="text-rose-400"><i class="bi bi-circle-fill text-xs mr-1"></i>Nu gesloten</span> <span style="color: var(--text-muted);">(open 19:00 - 21:00)</span>';

/* -------------------------------------------------------
   FILTERS (Categorie + SKU)
-------------------------------------------------------- */
$selectedCategory = $_GET['category'] ?? '';
$skuFilter        = trim($_GET['sku'] ?? '');

$conditions = [];

if ($selectedCategory === 'none') {
    $conditions[] = "pc.category_id IS NULL";
} elseif (is_numeric($selectedCategory) && (int)$selectedCategory > 0) {
    $conditions[] = "pc.category_id = " . (int)$selectedCategory;
}

if ($skuFilter !== '') {
    $safeSku = $conn->real_escape_string($skuFilter);
    $conditions[] = "p.sku LIKE '%$safeSku%'";
}

$whereClause = count($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

/* -------------------------------------------------------
   PAGINATIE
-------------------------------------------------------- */
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$totalQuery = $conn->query("
    SELECT COUNT(DISTINCT p.id) AS total
    FROM products p
    LEFT JOIN product_categories pc ON pc.product_id = p.id
    $whereClause
");
$totalRows  = $totalQuery->fetch_assoc()['total'];
$totalPages = max(1, ceil($totalRows / $limit));

/* -------------------------------------------------------
   PRODUCT QUERY
-------------------------------------------------------- */
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
    $whereClause
    GROUP BY p.id
    ORDER BY p.updated_at DESC
    LIMIT $limit OFFSET $offset
";
$productResult = $conn->query($productQuery);

/* -------------------------------------------------------
   CATEGORIES OPHALEN
-------------------------------------------------------- */
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-box-seam accent-primary mr-3"></i>Productbeheer
            </h1>
            <p class="text-lg flex items-center gap-4" style="color: var(--text-muted);">
                <span><?= $nu->format('l d F Y - H:i') ?></span>
                <span class="text-sm"><?= $openStatus ?></span>
            </p>
        </div>

        <div class="flex gap-3">
            <a class="accent-bg text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2"
               href="/admin/pages/products/add">
                <i class="bi bi-plus-circle"></i>
                Nieuw product
            </a>

            <a class="glass px-5 py-3 rounded-xl font-semibold hover:bg-rose-500/20 transition flex items-center gap-2 text-rose-400 border border-rose-500/30"
               href="?category=none">
                <i class="bi bi-exclamation-triangle"></i>
                Zonder categorie
            </a>
        </div>
    </div>
</div>

<!-- FILTER CARD -->
<div class="card-glass p-6 mb-8">
    <form method="get" class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <!-- CATEGORIE -->
        <div class="flex flex-col">
            <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Categorie</label>
            <select name="category"
                    onchange="this.form.submit()"
                    class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
                <option value="">Alle categorieën</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= $selectedCategory == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
                <option value="none" <?= $selectedCategory === 'none' ? 'selected' : '' ?>>Zonder categorie</option>
            </select>
        </div>

        <!-- SKU -->
        <div class="flex flex-col">
            <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Zoek op SKU</label>
            <input type="text"
                   name="sku"
                   value="<?= htmlspecialchars($skuFilter) ?>"
                   placeholder="Bijv. 20196"
                   class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
        </div>

        <!-- FILTER BUTTONS -->
        <div class="flex flex-col justify-end md:col-span-2">
            <div class="flex gap-3">
                <button type="submit" class="accent-bg text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
                    <i class="bi bi-search"></i>Filteren
                </button>
                <a href="/pages/products/index.php" class="glass px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
                    <i class="bi bi-x-circle"></i>Reset
                </a>
            </div>
        </div>

    </form>
</div>

<!-- PRODUCT TABLE -->
<div class="card-glass p-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500/30 to-cyan-500/30 flex items-center justify-center">
                <i class="bi bi-box-seam text-xl text-teal-400"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Producten — Pagina <?= $page ?></h2>
        </div>
        <span class="px-4 py-2 rounded-xl glass text-sm font-medium" style="color: var(--text-muted);">
            <?= $totalRows ?> producten
        </span>
    </div>

    <div class="overflow-x-auto rounded-xl">
        <table class="w-full">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass); background: var(--bg-glass);">
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">SKU</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Product</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Afbeelding</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Categorie</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Subcategorie</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Prijs</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Inkoop</th>
                    <th class="text-right py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Acties</th>
                </tr>
            </thead>

            <tbody class="divide-y" style="--tw-divide-opacity: 0.1;">
            <?php if ($productResult && $productResult->num_rows > 0): ?>
                <?php while ($p = $productResult->fetch_assoc()): ?>
                    <?php
                    $img = $p['image_url'];
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $img)) {
                        $img = '/images/products/placeholder.png';
                    }
                    ?>
                    <tr class="group hover:bg-white/5 transition-colors">
                        <td class="py-4 px-4 font-mono text-sm"><?= htmlspecialchars($p['sku']) ?></td>
                        <td class="py-4 px-4">
                            <span class="font-semibold"><?= htmlspecialchars($p['name']) ?></span>
                        </td>
                        <td class="py-4 px-4">
                            <img src="<?= $img ?>" class="w-14 h-14 rounded-xl object-cover border" style="border-color: var(--border-glass);">
                        </td>
                        <td class="py-4 px-4">
                            <?php if ($p['category_name']): ?>
                                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                    <?= htmlspecialchars($p['category_name']) ?>
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-500/20 text-rose-400 border border-rose-500/30">Geen</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4" style="color: var(--text-muted);">
                            <?= htmlspecialchars($p['subcategory_name'] ?? '—') ?>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-emerald-400">€<?= number_format($p['price'] ?? 0, 2, ',', '.') ?></span>
                        </td>
                        <td class="py-4 px-4" style="color: var(--text-muted);">
                            <?= isset($p['purchase_price']) ? '€'.number_format($p['purchase_price'], 2, ',', '.') : 'n.v.t.' ?>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="/admin/pages/winkel/producten/edit_product.php?id=<?= $p['id'] ?>"
                                   class="p-2 rounded-lg glass-hover text-amber-400 hover:bg-amber-500/20" title="Bewerken">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="/admin/pages/winkel/producten/delete_product.php?id=<?= $p['id'] ?>"
                                   onclick="return confirm('Verwijderen?')"
                                   class="p-2 rounded-lg glass-hover text-rose-400 hover:bg-rose-500/20" title="Verwijderen">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="py-16 text-center">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-500/20 to-slate-600/20 flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-inbox text-4xl" style="color: var(--text-muted);"></i>
                        </div>
                        <p class="text-lg font-semibold mb-1" style="color: var(--text-secondary);">Geen producten gevonden</p>
                        <p class="text-sm" style="color: var(--text-muted);">Probeer je filters aan te passen</p>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <?php if ($totalPages > 1): ?>
    <div class="mt-8 flex items-center justify-between">
        <div class="flex gap-2">
            <?php if ($page > 1): ?>
                <a href="?page=1&category=<?= $selectedCategory ?>&sku=<?= urlencode($skuFilter) ?>"
                   class="px-4 py-2 rounded-xl glass-hover font-semibold">« Eerste</a>
                <a href="?page=<?= max(1, $page - 1) ?>&category=<?= $selectedCategory ?>&sku=<?= urlencode($skuFilter) ?>"
                   class="px-4 py-2 rounded-xl glass-hover font-semibold">‹ Vorige</a>
            <?php endif; ?>
            
            <span class="px-4 py-2 rounded-xl accent-bg text-white font-bold">Pagina <?= $page ?></span>
            
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= min($totalPages, $page + 1) ?>&category=<?= $selectedCategory ?>&sku=<?= urlencode($skuFilter) ?>"
                   class="px-4 py-2 rounded-xl glass-hover font-semibold">Volgende ›</a>
                <a href="?page=<?= $totalPages ?>&category=<?= $selectedCategory ?>&sku=<?= urlencode($skuFilter) ?>"
                   class="px-4 py-2 rounded-xl glass-hover font-semibold">Laatste »</a>
            <?php endif; ?>
        </div>
        
        <span style="color: var(--text-muted);">
            Toon <?= $offset + 1 ?> - <?= min($offset + $limit, $totalRows) ?> van <?= $totalRows ?>
        </span>
    </div>
    <?php endif; ?>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
