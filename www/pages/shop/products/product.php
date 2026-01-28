<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Ongeldig product ID.");
}

$product_id = intval($_GET['id']);

// 🛒 Product ophalen
$productQuery = "
    SELECT 
        p.id, 
        p.name, 
        p.description, 
        p.price, 
        p.stock_quantity,
        p.sku,
        c.slug AS categorie_slug,
        c.id   AS categorie_id
    FROM 
        products p
    LEFT JOIN product_subcategories ps ON ps.product_id = p.id
    LEFT JOIN subcategories s         ON s.id = ps.subcategory_id
    LEFT JOIN categories c            ON c.id = s.category_id
    WHERE 
        p.id = ?
    LIMIT 1
";
$stmt = $conn->prepare($productQuery);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$productResult = $stmt->get_result();

if ($productResult->num_rows === 0) {
    die("Product niet gevonden.");
}

$product = $productResult->fetch_assoc();
$product_name = htmlspecialchars($product['name']);
$product_description = $product['description'];
$sku = $product['sku'];
$cat_id   = $product['categorie_id'] ?? null;
$product_stock = $product['stock_quantity'];
$original_price = (float)$product['price'];
$vers_product = ($cat_id == 5);

// ===== PRIJSBEREKENING =====
$today = date('Y-m-d');
$discountedPrice = $original_price;
$discount = 0;
$badgeClass = '';
$priceLabel = '';

// Weekdeal check
$weekDealStmt = $conn->prepare("
    SELECT 1
    FROM weekly_deals
    WHERE product_id = ?
      AND start_date <= ?
      AND end_date >= ?
    LIMIT 1
");
$weekDealStmt->bind_param('iss', $product_id, $today, $today);
$weekDealStmt->execute();
$isWeekDeal = $weekDealStmt->get_result()->num_rows > 0;
$weekDealStmt->close();

if ($isWeekDeal) {
    // Altijd 10% korting toepassen
    $discount = 10;
    $discountedPrice = round($original_price * 0.9, 2);
    $badgeClass = 'bg-green';
    $priceLabel = "🔥 Deal van de Week - {$discount}% KORTING";
} else {
    // Promo check
    $promoStmt = $conn->prepare("
        SELECT p.*
        FROM promos p
        LEFT JOIN product_categories pc ON pc.category_id = p.category_id
        WHERE (
            (p.promo_type = 'product' AND p.product_sku = ?)
            OR (p.promo_type = 'category' AND pc.product_id = ?)
            OR (p.promo_type = 'subcategory' AND p.subcategory_id IN (
                SELECT subcategory_id FROM product_subcategories WHERE product_id = ?
            ))
        )
        AND p.start_date <= NOW()
        AND p.end_date >= NOW()
        ORDER BY p.promo_type = 'product' DESC, p.promo_type = 'subcategory' DESC
        LIMIT 1
    ");
    $promoStmt->bind_param("sii", $product['sku'], $product['id'], $product['id']);
    $promoStmt->execute();
    $promo = $promoStmt->get_result()->fetch_assoc();
    $promoStmt->close();

    if ($promo) {
        $discount = (int)$promo['discount_percentage'];
        $discountedPrice = round($original_price * (1 - ($discount / 100)), 2);
        $badgeClass = $discount >= 30 ? 'bg-red' : ($discount >= 20 ? 'bg-orange' : ($discount >= 10 ? 'bg-green' : 'bg-secondary'));
        $priceLabel = "- {$discount}% KORTING";
    }
}

// ===== AFBEELDINGEN =====
$mainImageQuery = "SELECT image_path, webp_path FROM product_images WHERE product_id = ? AND is_main = 1 LIMIT 1";
$mainStmt = $conn->prepare($mainImageQuery);
$mainStmt->bind_param('i', $product_id);
$mainStmt->execute();
$mainResult = $mainStmt->get_result()->fetch_assoc();
$mainImagePath = $mainResult ? (!empty($mainResult['webp_path']) ? $mainResult['webp_path'] : $mainResult['image_path']) : null;

if (empty($mainImagePath) || !file_exists($_SERVER['DOCUMENT_ROOT'] . $mainImagePath)) {
    $mainImagePath = "/images/products/placeholder.png";
}

$imageQuery = "SELECT image_path, webp_path FROM product_images WHERE product_id = ?";
$imgStmt = $conn->prepare($imageQuery);
$imgStmt->bind_param('i', $product_id);
$imgStmt->execute();
$imgResult = $imgStmt->get_result();
$extraImages = [];
while ($image = $imgResult->fetch_assoc()) {
    $imgPath = !empty($image['webp_path']) ? $image['webp_path'] : $image['image_path'];
    if ($imgPath !== $mainImagePath) {
        $extraImages[] = $imgPath;
    }
}
$stmt->close();
$imgStmt->close();

?>

<style>
    .fade-img {
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .fade-img.show {
        opacity: 1;
    }
    .thumbnail-clickable {
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    .thumbnail-clickable:hover {
        transform: scale(1.05);
    }
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <img id="main-product-image" src="<?= htmlspecialchars($mainImagePath) ?>" class="img-fluid rounded fade-img show" alt="<?= $product_name ?>">

            <!-- Extra afbeeldingen -->
            <div class="d-flex mt-3">
                <?php foreach ($extraImages as $extraImage): ?>
                    <img src="<?= htmlspecialchars($extraImage); ?>"
                         class="img-thumbnail me-2 thumbnail-clickable"
                         style="width: 80px; height: 80px;"
                         alt="Extra afbeelding">
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-md-6">
            <h1><?= $product_name ?></h1>
            <div class="text-muted"><?= $product_description ?></div>

            <?php if ($vers_product): ?>
                <div class="my-4">
                    <a href="https://vers.windelsgreen-decoresin.com/pages/shop/products/product.php?id=<?= $product_id ?>" class="btn btn-success btn-lg">
                        Bekijk dit product op Vers
                    </a>
                </div>
            <?php else: ?>
                <?php if ($discount > 0): ?>
                    <p class="mb-2">
                        <span class="badge <?= $badgeClass ?> fs-6 px-3 py-2"><?= $priceLabel ?></span>
                    </p>
                    <h3 class="text-success">
                        <del class="text-muted">€<?= number_format($original_price, 2, ',', '.') ?></del><br>
                        <strong>€<?= number_format($discountedPrice, 2, ',', '.') ?></strong>
                    </h3>
                <?php else: ?>
                    <h3 class="text-primary">€<?= number_format($original_price, 2, ',', '.') ?></h3>
                <?php endif; ?>

                <form id="add-to-cart-form">
                    <input type="hidden" id="product_id" value="<?= $product_id ?>">
                    <div class="d-flex align-items-center">
                        <input type="number" id="quantity" value="1" min="1" max="<?= $product_stock ?>" class="form-control w-25 me-2">
                        <?php if ($product_stock > 0): ?>
                            <button type="button" id="add-to-cart-btn" class="add-to-cart btn btn-primary"
                                    data-id="<?= $product_id ?>"
                                    data-name="<?= htmlspecialchars($product_name) ?>"
                                    data-price="<?= $discountedPrice ?>">
                                Toevoegen aan winkelwagen
                            </button>
                        <?php else: ?>
                            <div class="alert bg-light border text-start text-muted py-4 px-4 mt-3 w-100">
                                <h5 class="mb-2">❌ Momenteel uitverkocht</h5>
                                <p class="mb-3">Dit product is tijdelijk niet beschikbaar.<br>
                                    Klik op onderstaande knop om op de hoogte gehouden te worden.
                                </p>
                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#notifyModal"
                                        onclick="setProductId(<?= $product_id ?>)">
                                    <i class="bi bi-bell text-orange"></i> Houd me op de hoogte
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
                <?php if ($product_stock > 0): ?>
                    <p class="text-success">Op voorraad (<?= $product_stock ?> beschikbaar)</p>
                <?php endif; ?>
                <p id="cart-feedback" class="mt-2"></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.thumbnail-clickable').forEach(thumb => {
        thumb.addEventListener('click', function () {
            const mainImg = document.getElementById('main-product-image');
            mainImg.classList.remove('show');
            setTimeout(() => {
                const currentSrc = mainImg.src;
                mainImg.src = this.src;
                this.src = currentSrc;
                mainImg.classList.add('show');
            }, 200);
        });
    });
</script>
<script src="/js/shop/cart.js"></script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
