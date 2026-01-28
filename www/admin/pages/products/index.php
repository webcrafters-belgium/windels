<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');
include($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php');

/* -------------------------------------------------------
   OPEN STATUS
-------------------------------------------------------- */
$nu = new DateTime('now', new DateTimeZone('Europe/Brussels'));
$open = new DateTime('19:00', new DateTimeZone('Europe/Brussels'));
$sluit = new DateTime('21:00', new DateTimeZone('Europe/Brussels'));
$openStatus = ($nu >= $open && $nu <= $sluit)
        ? '<span class="text-green-400">🟢 Nu open</span>'
        : '<span class="text-red-400">🔴 Nu gesloten</span> (open 19:00 - 21:00)';

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

<!-- PAGE WRAPPER (zelfde layout als admin index) -->
<div class="min-h-screen bg-[#0d0d0d] text-gray-200 py-10 px-8 space-y-10">

    <!-- PAGE HEADER -->
    <div class="bg-[#141414] border border-gray-800 rounded-2xl p-8 flex justify-between items-start shadow-xl">
        <div>
            <h1 class="text-3xl font-bold">Productbeheer</h1>
            <p class="text-gray-400 text-sm mt-1"><?= $nu->format('l d F Y - H:i:s') ?></p>
            <p class="mt-1"><?= $openStatus ?></p>
        </div>

        <div class="flex gap-3">
            <a class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg font-semibold"
               href="/admin/pages/products/add">
                + Nieuw product
            </a>

            <a class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg font-semibold"
               href="?category=none">
                Zonder categorie
            </a>
        </div>
    </div>

    <!-- FILTER BLOCK (Categorie + SKU) -->
    <div class="bg-[#141414] border border-gray-800 rounded-xl p-6 shadow">
        <form method="get" class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- CATEGORIE -->
            <div class="flex flex-col">
                <label class="text-sm mb-1 text-gray-400">Categorie</label>
                <select name="category"
                        onchange="this.form.submit()"
                        class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600">
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
                <label class="text-sm mb-1 text-gray-400">Zoek op SKU</label>
                <input type="text"
                       name="sku"
                       value="<?= htmlspecialchars($skuFilter) ?>"
                       placeholder="Bijv. 20196"
                       class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-600"
                       onkeyup="if(event.key==='Enter') this.form.submit();">
            </div>

            <!-- FILTERKNOP OP MOBIEL -->
            <div class="md:hidden flex flex-col">
                <label class="text-sm text-transparent">.</label>
                <button class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg font-semibold">
                    Filteren
                </button>
            </div>

        </form>
    </div>

    <div class="flex">
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/admin/partials/sidebar.php';
        ?>



        <!-- PRODUCT TABEL -->
        <div class="bg-[#141414] border border-gray-800 rounded-2xl p-8 shadow-xl" style="width: 100%">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Producten — Pagina <?= $page ?></h2>
                <span class="text-gray-400 bg-[#0f0f0f] px-3 py-1 rounded-md border border-gray-700">
                    Toon <?= $limit ?>/pagina
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="text-gray-400 text-sm border-b border-gray-800">
                        <th class="pb-3">SKU</th>
                        <th class="pb-3">Titel</th>
                        <th class="pb-3">Afbeelding</th>
                        <th class="pb-3">Categorie</th>
                        <th class="pb-3">Subcategorie</th>
                        <th class="pb-3">Prijs</th>
                        <th class="pb-3">Inkoop</th>
                        <th class="pb-3">Acties</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-800">

                    <?php if ($productResult && $productResult->num_rows > 0): ?>
                        <?php while ($p = $productResult->fetch_assoc()): ?>

                            <?php
                            $img = $p['image_url'];
                            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $img)) {
                                $img = '/images/products/placeholder.png';
                            }
                            ?>

                            <tr class="hover:bg-[#1a1a1a] transition">

                                <td class="py-4"><?= htmlspecialchars($p['sku']) ?></td>

                                <td class="py-4 font-medium text-gray-200">
                                    <?= htmlspecialchars($p['name']) ?>
                                </td>

                                <td class="py-4">
                                    <img src="<?= $img ?>"
                                         class="w-12 h-12 rounded-lg object-cover border border-gray-700">
                                </td>

                                <td class="py-4">
                                    <?php if ($p['category_name']): ?>
                                        <span class="bg-green-900/30 border border-green-700 text-green-300 text-xs px-2 py-1 rounded-lg">
                                            <?= htmlspecialchars($p['category_name']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-red-900/30 border border-red-700 text-red-300 text-xs px-2 py-1 rounded-lg">Geen</span>
                                    <?php endif; ?>
                                </td>

                                <td class="py-4 text-gray-300">
                                    <?= htmlspecialchars($p['subcategory_name'] ?? '—') ?>
                                </td>

                                <td class="py-4 text-gray-300">
                                    €<?= number_format($p['price'] ?? 0, 2, ',', '.') ?>
                                </td>

                                <td class="py-4 text-gray-400">
                                    <?= isset($p['purchase_price'])
                                            ? '€'.number_format($p['purchase_price'], 2, ',', '.')
                                            : 'n.v.t.' ?>
                                </td>

                                <td class="py-4 flex gap-3">
                                    <a href="/admin/pages/winkel/producten/edit_product.php?id=<?= $p['id'] ?>"
                                       class="text-yellow-400 hover:text-yellow-300 text-xl">✏️</a>

                                    <a href="/admin/pages/winkel/producten/delete_product.php?id=<?= $p['id'] ?>"
                                       onclick="return confirm('Verwijderen?')"
                                       class="text-red-500 hover:text-red-400 text-xl">🗑️</a>
                                </td>

                            </tr>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="py-6 text-center text-gray-400">Geen producten gevonden</td></tr>
                    <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- PAGINATIE -->
    <div class="flex justify-between items-center mt-8 text-gray-400">

        <div class="flex gap-3">
            <a href="?page=1&category=<?= $selectedCategory ?>&sku=<?= urlencode($skuFilter) ?>"
               class="px-3 py-1 rounded-lg border border-gray-700 hover:border-gray-500">« Eerste</a>

            <a href="?page=<?= max(1, $page - 1) ?>&category=<?= $selectedCategory ?>&sku=<?= urlencode($skuFilter) ?>"
               class="px-3 py-1 rounded-lg border border-gray-700 hover:border-gray-500">‹ Vorige</a>

            <span class="px-3 py-1 rounded-lg bg-green-800/40 border border-green-700 text-green-300 font-bold">
                    <?= $page ?>
                </span>

            <a href="?page=<?= min($totalPages, $page + 1) ?>&category=<?= $selectedCategory ?>&sku=<?= urlencode($skuFilter) ?>"
               class="px-3 py-1 rounded-lg border border-gray-700 hover:border-gray-500">Volgende ›</a>

            <a href="?page=<?= $totalPages ?>&category=<?= $selectedCategory ?>&sku=<?= urlencode($skuFilter) ?>"
               class="px-3 py-1 rounded-lg border border-gray-700 hover:border-gray-500">Laatste »</a>
        </div>

        <p>
            Resultaten
            <?= $offset + 1 ?> – <?= min($offset + $limit, $totalRows) ?>
            van <strong><?= $totalRows ?></strong>
        </p>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'); ?>
