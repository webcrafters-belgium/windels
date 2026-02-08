<?php
// FILE: /pages/shop/index.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

// ✅ Helpers
include $_SERVER['DOCUMENT_ROOT'] . '/functions/helpers/product_filters.php';
include $_SERVER['DOCUMENT_ROOT'] . '/functions/helpers/category_helpers.php';
include $_SERVER['DOCUMENT_ROOT'] . '/functions/helpers/text_helpers.php';

// ✅ Filters ophalen
$searchTerm = $_GET['search'] ?? '';
$minPrice   = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$maxPrice   = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 9999;
$sort       = $_GET['sort'] ?? 'default';

// Paginering
$page   = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$limit  = 12;
$offset = ($page - 1) * $limit;

// ✅ Aantal producten tellen voor paginering
$totalStmt = $conn->prepare("
    SELECT COUNT(DISTINCT p.id)
    FROM products p
    LEFT JOIN product_categories pc ON pc.product_id = p.id
    LEFT JOIN categories c ON c.id = pc.category_id
    LEFT JOIN product_subcategories psc ON psc.product_id = p.id
    LEFT JOIN subcategories sc ON sc.id = psc.subcategory_id
    WHERE 1=1
    " . (!empty($searchTerm) ? "AND p.name LIKE ?" : "") . "
    " . (!empty($_GET['category']) ? "AND c.slug = ?" : "") . "
    " . (!empty($_GET['sub']) ? "AND sc.slug = ?" : "") . "
    AND p.price BETWEEN ? AND ?
");

$types  = '';
$params = [];

if (!empty($searchTerm)) {
    $types   .= 's';
    $params[] = '%' . $searchTerm . '%';
}
if (!empty($_GET['category'])) {
    $types   .= 's';
    $params[] = $_GET['category'];
}
if (!empty($_GET['sub'])) {
    $types   .= 's';
    $params[] = $_GET['sub'];
}

$types   .= 'dd';
$params[] = $minPrice;
$params[] = $maxPrice;

if ($types) {
    $totalStmt->bind_param($types, ...$params);
}

$totalStmt->execute();
$totalResult    = $totalStmt->get_result();
$totalProducts  = (int)$totalResult->fetch_row()[0];
$totalPages     = (int)ceil($totalProducts / $limit);

// ✅ Data ophalen
$products        = fetchFilteredProducts($conn, $searchTerm, $minPrice, $maxPrice, $sort, $limit, $offset);
$categoryResult  = getCategories($conn);
$subcategories   = getSubcategories($conn);

// ✅ Promo statement (1x voorbereiden; zelfde logica, sneller)
$promoStmt = $conn->prepare("
    SELECT p.*
    FROM promos p
    LEFT JOIN product_categories pc ON pc.category_id = p.category_id
    WHERE (
        (p.promo_type = 'product' AND p.product_sku = ?)
        OR (p.promo_type = 'category' AND pc.product_id = ?)
        OR (p.promo_type = 'subcategory' AND p.subcategory_id = ?)
    )
    AND p.start_date <= NOW()
    AND p.end_date >= NOW()
    ORDER BY p.promo_type = 'product' DESC, p.promo_type = 'subcategory' DESC
    LIMIT 1
");
?>

<style>
    /* Alleen shop styling; geen globale breekijzers */
    .shop-hero { padding: 3.25rem 0; }
    .shop-hero .searchbar { max-width: 760px; }

    .shop-toolbar {
        display:flex; gap:12px; align-items:center; justify-content:space-between;
        padding: 14px 16px; border-radius: 16px;
        background: var(--bs-body-bg);
        border: 1px solid rgba(0,0,0,.06);
        box-shadow: 0 8px 24px rgba(0,0,0,.04);
        margin-bottom: 18px;
    }
    .shop-toolbar small { opacity: .75; }

    .filters-card {
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,.06);
        box-shadow: 0 8px 24px rgba(0,0,0,.04);
        background: #fff;
    }
    .filters-sticky { position: sticky; top: 90px; }

    .shop-grid {
        display:grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 18px;
    }
    @media (min-width: 576px){ .shop-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 992px){ .shop-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }

    .product-card {
        height: 100%;
        border-radius: 18px;
        border: 1px solid rgba(0,0,0,.06);
        overflow: hidden;
        background: #fff;
        box-shadow: 0 10px 30px rgba(0,0,0,.05);
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .product-card:hover { transform: translateY(-2px); box-shadow: 0 16px 40px rgba(0,0,0,.08); }

    .product-media {
        position: relative;
        aspect-ratio: 1 / 1;
        background: #f7f7f7;
        overflow: hidden;
    }
    .product-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display:block;
    }
    .promo-badge {
        position:absolute; top: 12px; left: 12px;
        padding: 8px 10px;
        border-radius: 999px;
        font-weight: 700;
        font-size: .85rem;
        color: #fff;
        box-shadow: 0 10px 22px rgba(0,0,0,.18);
    }
    .bg-red { background: #dc3545; }
    .bg-orange { background: #fd7e14; }
    .bg-green { background: #198754; }

    .product-body { padding: 14px 14px 16px; display:flex; flex-direction:column; height:100%; }
    .product-title { font-size: 1.02rem; font-weight: 800; margin-bottom: 6px; line-height: 1.25; }
    .product-description { color: rgba(0,0,0,.65); font-size: .92rem; margin-bottom: 12px; }
    .price { font-weight: 800; font-size: 1.05rem; }
    .price del { font-weight: 600; opacity: .7; }

    .pagination { gap: 6px; }
    .page-link { border-radius: 12px !important; }

    .shop-empty {
        border-radius: 18px;
        border: 1px dashed rgba(0,0,0,.18);
        padding: 26px;
        background: #fff;
    }
</style>

<section class="shop-hero bg-undertext">
    <div class="container">
        <div class="home-section-header">
            <div>
                <h1 class="home-section-title">Shop onze producten</h1>
                <p class="home-section-subtitle">Gebruik filters of zoek naar je favoriete creatie.</p>
            </div>
        </div>

        <form method="get" action="" class="row justify-content-center mt-4">
            <div class="col-12 searchbar">
                <!-- Behoud andere filters wanneer je zoekt -->
                <?php if (!empty($_GET['category'])): ?><input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']); ?>"><?php endif; ?>
                <?php if (!empty($_GET['sub'])): ?><input type="hidden" name="sub" value="<?= htmlspecialchars($_GET['sub']); ?>"><?php endif; ?>
                <input type="hidden" name="min_price" value="<?= htmlspecialchars((string)$minPrice); ?>">
                <input type="hidden" name="max_price" value="<?= htmlspecialchars((string)$maxPrice); ?>">
                <input type="hidden" name="sort" value="<?= htmlspecialchars($sort); ?>">
                <input type="hidden" name="page" value="1">

                <div class="input-group input-group-lg">
                    <input
                            type="text"
                            name="search"
                            value="<?= htmlspecialchars($searchTerm); ?>"
                            class="form-control"
                            placeholder="Zoek een product..."
                    >
                    <button type="submit" class="btn btn-primary px-4">Zoeken</button>
                </div>
            </div>
        </form>
    </div>
</section>

<section class="section bg-offwhite">
    <div class="container shop">
        <div class="row g-4">

            <!-- Sidebar -->
            <aside class="col-lg-3">
                <div class="filters-card p-3 filters-sticky">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/partials/shop/sidebar_filters.php'; ?>
                </div>
            </aside>

            <!-- Producten -->
            <div class="col-lg-9">

                <div class="shop-toolbar">
                    <div>
                        <div class="fw-bold">Resultaten</div>
                        <small><?= $totalProducts ?> producten gevonden</small>
                    </div>

                    <form method="get" class="d-flex align-items-center gap-2">
                        <!-- behoud filters -->
                        <?php foreach ($_GET as $k => $v): ?>
                            <?php if ($k === 'sort' || $k === 'page') continue; ?>
                            <?php if (is_array($v)) continue; ?>
                            <input type="hidden" name="<?= htmlspecialchars($k); ?>" value="<?= htmlspecialchars((string)$v); ?>">
                        <?php endforeach; ?>
                        <input type="hidden" name="page" value="1">

                        <label class="small text-muted d-none d-md-inline mb-0">Sorteren</label>
                        <select name="sort" class="form-select" onchange="this.form.submit()">
                            <option value="default" <?= $sort === 'default' ? 'selected' : '' ?>>Standaard</option>
                            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Prijs: laag → hoog</option>
                            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Prijs: hoog → laag</option>
                            <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Naam: A → Z</option>
                            <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Naam: Z → A</option>
                        </select>
                    </form>
                </div>

                <?php if (empty($products)): ?>
                    <div class="shop-empty">
                        <div class="fw-bold mb-1">Geen producten gevonden</div>
                        <div class="text-muted mb-3">Pas je filters of zoekopdracht aan.</div>
                        <a class="btn btn-outline-primary" href="/pages/shop/">Filters resetten</a>
                    </div>
                <?php else: ?>

                    <div class="shop-grid">

                        <?php foreach ($products as $product): ?>
                            <?php
                            // Promo (zelfde logica)
                            $sku  = (string)($product['sku'] ?? '');
                            $pid  = (int)$product['id'];
                            $scid = (int)($product['subcategory_id'] ?? 0);

                            $promoStmt->bind_param("sii", $sku, $pid, $scid);
                            $promoStmt->execute();
                            $promo = $promoStmt->get_result()->fetch_assoc();

                            $discountedPrice = null;
                            $badgeClass = null;

                            if ($promo) {
                                $discount = (int)$promo['discount_percentage'];
                                $discountedPrice = round(((float)$product['price']) * (1 - ($discount / 100)), 2);

                                if ($discount >= 29) $badgeClass = 'bg-red';
                                elseif ($discount >= 19) $badgeClass = 'bg-orange';
                                else $badgeClass = 'bg-green';
                            }

                            $img = $product['product_image'] ?? '/images/placeholder.png';
                            $name = $product['name'] ?? 'Product';
                            $price = (float)($product['price'] ?? 0);
                            $priceCents = (int)round($price * 100);
                            ?>

                            <div class="product-card position-relative">

                                <div class="product-media">
                                    <?php if ($promo): ?>
                                        <span class="promo-badge <?= $badgeClass ?>">
                                            -<?= (int)$discount ?>%
                                        </span>
                                    <?php endif; ?>

                                    <a href="/pages/shop/products/product.php?id=<?= $pid; ?>" class="d-block">
                                        <img
                                                src="<?= htmlspecialchars($img); ?>"
                                                alt="<?= htmlspecialchars($name); ?>"
                                                loading="lazy"
                                        >
                                    </a>
                                </div>

                                <div class="product-body">
                                    <div class="product-title"><?= htmlspecialchars($name); ?></div>

                                    <div class="product-description">
                                        <?= truncateHtmlPreserveTags((string)($product['description'] ?? ''), 90); ?>
                                    </div>

                                    <div class="mt-auto">
                                        <?php if ($promo): ?>
                                            <div class="price mb-2">
                                                <del class="d-block">€<?= number_format($price, 2, ',', '.'); ?></del>
                                                €<?= number_format((float)$discountedPrice, 2, ',', '.'); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="price mb-3">
                                                €<?= number_format($price, 2, ',', '.'); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (($product['stock_status'] ?? '') === 'onbackorder'): ?>
                                            <button
                                                    class="btn btn-warning w-100 btn-request-product"
                                                    data-product-id="<?= $pid; ?>">
                                                📦 Product aanvragen
                                            </button>
                                        <?php elseif ((int)($product['stock_quantity'] ?? 0) > 0): ?>
                                            <button
                                                    class="btn btn-primary w-100 add-to-cart"
                                                    data-id="<?= $pid; ?>"
                                                    data-qty="1"
                                                    data-price="<?= $priceCents; ?>"
                                                    data-name="<?= htmlspecialchars($name); ?>">
                                                🛒 Toevoegen
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-secondary w-100" disabled>
                                                Uitverkocht
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>

                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>

                <?php
                // Sluit promo statement netjes
                if ($promoStmt) { $promoStmt->close(); }
                ?>

                <?php if ($totalPages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination flex-wrap">

                            <?php
                            $queryParams = $_GET;

                            // Prev
                            $queryParams['page'] = max(1, $page - 1);
                            $prevUrl = '?' . http_build_query($queryParams);
                            ?>
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $prevUrl ?>" tabindex="-1">« Vorige</a>
                            </li>

                            <?php
                            // Compacte paginering
                            $start = max(1, $page - 2);
                            $end   = min($totalPages, $page + 2);

                            if ($start > 1) {
                                $queryParams['page'] = 1;
                                echo '<li class="page-item"><a class="page-link" href="?' . http_build_query($queryParams) . '">1</a></li>';
                                if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                            }

                            for ($i = $start; $i <= $end; $i++):
                                $queryParams['page'] = $i;
                                $url = '?' . http_build_query($queryParams);
                                $active = ($i == $page) ? 'active' : '';
                                ?>
                                <li class="page-item <?= $active ?>">
                                    <a class="page-link" href="<?= $url ?>"><?= $i ?></a>
                                </li>
                            <?php endfor;

                            if ($end < $totalPages) {
                                if ($end < $totalPages - 1) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                                $queryParams['page'] = $totalPages;
                                echo '<li class="page-item"><a class="page-link" href="?' . http_build_query($queryParams) . '">' . $totalPages . '</a></li>';
                            }

                            // Next
                            $queryParams['page'] = min($totalPages, $page + 1);
                            $nextUrl = '?' . http_build_query($queryParams);
                            ?>
                            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $nextUrl ?>">Volgende »</a>
                            </li>

                        </ul>
                    </nav>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<script src="/js/cart.js"></script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?> 
