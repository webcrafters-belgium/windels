<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');
include($_SERVER['DOCUMENT_ROOT'] . '/header.php');

$nu = new DateTime('now', new DateTimeZone('Europe/Brussels'));
$open = new DateTime('19:00', new DateTimeZone('Europe/Brussels'));
$sluit = new DateTime('21:00', new DateTimeZone('Europe/Brussels'));
$openStatus = ($nu >= $open && $nu <= $sluit) ? '🟢 Nu open' : '🔴 Nu gesloten, maar vandaag geopend van 19:00 tot 21:00.';

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

if(isset($_GET['success']) && $_GET['success'] == 1) {
    echo "Product succesvol toegevoegd";
}

$productQuery = "
    SELECT  p.*,
        c.name  AS category_name,
        s.name  AS subcategory_name,
        COALESCE(NULLIF(pi.webp_path,''), NULLIF(pi.image_path,''), '/images/products/placeholder.png') AS image_url
        FROM        products p
        LEFT JOIN   product_categories  pc ON pc.product_id      = p.id
        LEFT JOIN   categories          c  ON c.id               = pc.category_id
        LEFT JOIN   product_subcategories ps ON ps.product_id    = p.id
        LEFT JOIN   subcategories       s  ON s.id               = ps.subcategory_id
        LEFT JOIN   product_images      pi ON pi.product_id      = p.id
                                             AND pi.is_main = 1
        {$whereClause}
        GROUP BY    p.id
        ORDER BY    p.updated_at DESC
        LIMIT       {$limit} OFFSET {$offset}
";

$productResult = $conn->query($productQuery);

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
?>

    <div class="container mt-4">
        <div class="mb-4">
            <h2>Windels Green & Deco Resin</h2>
            <p class="text-muted"><?= $nu->format('l d F Y \o\m H:i:s') ?></p>
            <p><?= $openStatus ?></p>

        </div>

        <div class="card mb-4">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Producten Beheer</h4>
                <form class="d-flex" method="get">
                    <select name="category" class="form-select me-2" onchange="this.form.submit()">
                        <option value="">-- Filter op categorie --</option>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>" <?= $selectedCategory == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <noscript><button type="submit" class="btn btn-primary">Filter</button></noscript>
                </form>
            </div>
            <div class="card-body">
                <p>
                    <strong>Let op:</strong> Gebruik altijd de SKU die hier wordt weergegeven voor webshop en kassa. <br>
                    <em>SKU begint met:</em> 2 = epoxy/terrazzo, 1 = vers, 3 = kaarsen, 4 = inkoop. <br>
                    Opbouw: categorie(1cijfer) + volgnummer (4 cijfers). Verwijderde producten houden hun nummer tijdelijk bezet.
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Producten (pagina <?= $page ?>)</h5>
                <span class="badge bg-secondary">Toon <?= $limit ?> per pagina</span>
            </div>
            <a href="/admin/pages/winkel/producten/add" class="btn btn-primary">Toevoegen</a>
            <a href="?category=none" class="btn bg-red">
                <i class="fas fa-exclamation-circle"></i> Toon producten zonder categorie
            </a>
            <div class="card-body p-0">


                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                    <tr>
                        <th>SKU</th>
                        <th>Titel</th>
                        <th>Afbeelding</th>
                        <th>Categorie</th>
                        <th>Subcategorie</th>
                        <th>Totaalprijs</th>
                        <th>Inkoop Prijs</th>
                        <th>Acties</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($productResult && $productResult->num_rows > 0): ?>
                        <?php while ($product = $productResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['sku'] ?? '') ?></td>
                                <td><?= htmlspecialchars($product['name'] ?? '') ?></td>
                                <td>
                                    <?php
                                    $imgPath = $product['image_url'] ?? '/images/products/placeholder.png';
                                    $absPath = $_SERVER['DOCUMENT_ROOT'] . $imgPath;
                                    if (!file_exists($absPath)) {
                                        $imgPath = '/images/products/placeholder.png';
                                    }
                                    ?>
                                    <img src="<?= htmlspecialchars($imgPath) ?>" alt="afbeelding" class="img-thumbnail" style="width: 60px;">
                                </td>
                                <td>
                                    <?php if (!empty($product['category_name'])): ?>
                                        <?= htmlspecialchars($product['category_name']) ?>
                                    <?php elseif ($selectedCategory === 'none'): ?>
                                        <form action="/functions/shop/assign_category.php" method="post" class="d-flex">
                                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                            <select name="category_id" class="form-select form-select-sm me-1" required>
                                                <option value="">-- Kies --</option>
                                                <?php
                                                $cats = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
                                                while ($c = $cats->fetch_assoc()):
                                                    echo "<option value=\"{$c['id']}\">".htmlspecialchars($c['name'])."</option>";
                                                endwhile;
                                                ?>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-success">✔</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Geen</span>
                                    <?php endif; ?>
                                </td>


                                <td><?= htmlspecialchars($product['subcategory_name'] ?? 'onbekend') ?></td>
                                <td>&euro;<?= number_format($product['price'] ?? 0, 2, ',', '.') ?></td>
                                <td>
                                    <?= isset($product['purchase_price']) ? '&euro;' . number_format($product['purchase_price'], 2, ',', '.') : 'n.v.t.' ?>
                                </td>
                                <td>
                                    <a href="/admin/pages/winkel/producten/edit_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
                                    <a href="/admin/pages/winkel/producten/delete_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Verwijderen?')">🗑️</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Geen producten gevonden.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=1&category=<?= $selectedCategory ?>">Eerste</a>
                </li>
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&category=<?= $selectedCategory ?>">Vorige</a>
                </li>
                <li class="page-item active"><span class="page-link"><?= $page ?></span></li>
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&category=<?= $selectedCategory ?>">Volgende</a>
                </li>
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $totalPages ?>&category=<?= $selectedCategory ?>">Laatste</a>
                </li>
            </ul>
            <p class="text-center small">Tonen <?= $limit ?> producten per pagina | <?= ($offset + 1) ?> - <?= min($offset + $limit, $totalRows) ?> van <?= $totalRows ?> producten</p>
        </nav>
    </div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); ?>