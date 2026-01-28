<?php
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
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$limit = 12;
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

$types = '';
$params = [];

if (!empty($searchTerm)) {
    $types .= 's';
    $params[] = '%' . $searchTerm . '%';
}
if (!empty($_GET['category'])) {
    $types .= 's';
    $params[] = $_GET['category'];
}
if (!empty($_GET['sub'])) {
    $types .= 's';
    $params[] = $_GET['sub'];
}

$types .= 'dd';
$params[] = $minPrice;
$params[] = $maxPrice;

if ($types) {
    $totalStmt->bind_param($types, ...$params);
}

$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalProducts = $totalResult->fetch_row()[0];
$totalPages = ceil($totalProducts / $limit);

// ✅ Data ophalen
$products = fetchFilteredProducts($conn, $searchTerm, $minPrice, $maxPrice, $sort, $limit, $offset);$categoryResult = getCategories($conn);
$subcategories  = getSubcategories($conn);



?>

<section class="section bg-undertext">
    <div class="container">
        <div class="home-section-header">
            <div>
                <h1 class="home-section-title">Shop onze producten</h1>
                <p class="home-section-subtitle">
                    Gebruik filters of zoek naar je favoriete creatie
                </p>
            </div>
        </div>

        <form method="get" action="" class="row justify-content-center mt-4">
            <div class="col-md-8 col-lg-6">
                <div class="input-group input-group-lg">
                    <input
                            type="text"
                            name="search"
                            value="<?= htmlspecialchars($searchTerm); ?>"
                            class="form-control"
                            placeholder="Zoek een product..."
                    >
                    <button type="submit" class="btn btn-primary px-4">
                        Zoeken
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<section class="section bg-offwhite">
    <div class="container shop">
        <div class="row">

            <!-- Sidebar -->
            <aside class="col-lg-3 mb-4">
                <div class="filter-categories bg-undertext radius-1 p-3 shadow-sm">
                    <?php include $_SERVER['DOCUMENT_ROOT'] . '/partials/shop/sidebar_filters.php'; ?>
                </div>
            </aside>

            <!-- Producten -->
            <div class="col-lg-9">

                <?php if (empty($products)): ?>
                    <div class="alert">
                        Geen producten gevonden. Pas je filters of zoekopdracht aan.
                    </div>
                <?php else: ?>

                    <div class="product-grid best-selling-swiper-wrapper">

                        <?php foreach ($products as $product): ?>
                            <?php
                            // Promo
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
                            $promoStmt->bind_param("sii", $product['sku'], $product['id'], $product['subcategory_id']);
                            $promoStmt->execute();
                            $promo = $promoStmt->get_result()->fetch_assoc();

                            $discountedPrice = null;
                            $badgeClass = null;

                            if ($promo) {
                                $discount = (int)$promo['discount_percentage'];
                                $discountedPrice = round($product['price'] * (1 - ($discount / 100)), 2);

                                if ($discount >= 29) $badgeClass = 'bg-red';
                                elseif ($discount >= 19) $badgeClass = 'bg-orange';
                                else $badgeClass = 'bg-green';
                            }
                            ?>

                            <div class="product-item position-relative">

                                <?php if ($promo): ?>
                                    <span class="badge <?= $badgeClass ?> position-absolute m-2">
                                        -<?= $discount ?>%
                                    </span>
                                <?php endif; ?>

                                <a href="/pages/shop/products/product.php?id=<?= (int)$product['id']; ?>">
                                    <figure>
                                        <img
                                                src="<?= htmlspecialchars($product['product_image']); ?>"
                                                alt="<?= htmlspecialchars($product['name']); ?>"
                                                loading="lazy"
                                        >
                                    </figure>
                                </a>

                                <div class="product-body text-center d-flex flex-column h-100">
                                    <h3><?= htmlspecialchars($product['name']); ?></h3>

                                    <div class="product-description">
                                        <?= truncateHtmlPreserveTags($product['description'], 90); ?>
                                    </div>

                                    <div class="mt-auto">
                                        <?php if ($promo): ?>
                                            <div class="price mb-2">
                                                <del class="text-muted d-block">
                                                    €<?= number_format($product['price'], 2, ',', '.'); ?>
                                                </del>
                                                €<?= number_format($discountedPrice, 2, ',', '.'); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="price mb-3">
                                                €<?= number_format($product['price'], 2, ',', '.'); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($product['stock_status'] === 'onbackorder'): ?>
                                            <button
                                                    class="btn btn-warning w-100 btn-request-product"
                                                    data-product-id="<?= (int)$product['id']; ?>">
                                                📦 Product aanvragen
                                            </button>
                                        <?php elseif ((int)$product['stock_quantity'] > 0): ?>
                                            <button
                                                    class="btn btn-primary w-100 add-to-cart"
                                                    data-id="<?= $product['id']; ?>">
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

                <?php if ($totalPages > 1): ?>
                    <nav>
                        <ul class="pagination">
                            <?php
                            $queryParams = $_GET;
                            for ($i = 1; $i <= $totalPages; $i++):
                                $queryParams['page'] = $i;
                                $url = '?' . http_build_query($queryParams);
                                $active = ($i == $page) ? 'active' : '';
                                ?>
                                <li class="page-item <?= $active ?>">
                                    <a class="page-link" href="<?= $url ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>


<script src="/js/cart.js"></script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
