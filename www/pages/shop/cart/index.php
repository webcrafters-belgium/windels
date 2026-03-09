<?php
// FILE: /pages/shop/cart/index.php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/mollie/vendor/autoload.php';

$session_id = session_id();
$sql = "
  SELECT ci.product_id, p.name,
         ci.price, p.price AS regular_price,
         p.sku, p.weight_grams, ci.quantity,
         (SELECT image_path FROM product_images WHERE product_id = p.id AND is_main = 1 LIMIT 1) AS main_image
  FROM cart_items ci
  JOIN products p ON p.id = ci.product_id
  WHERE ci.session_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $session_id);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$baseTotal = 0;
$totalWeightG = 0;
while ($row = $result->fetch_assoc()) {
    $qty       = (int)$row['quantity'];
    $cartPrice = (float)$row['price'];
    $regular   = (float)$row['regular_price'];
    $weightG   = max(0, (int)($row['weight_grams'] ?? 0));

    if (empty($row['main_image']) || !file_exists($_SERVER['DOCUMENT_ROOT'] . $row['main_image'])) {
        $row['main_image'] = '/images/products/placeholder.png';
    }

    $row['original_price'] = $regular;
    $row['discount'] = ($regular > $cartPrice) ? round(100 - ($cartPrice / $regular * 100)) : 0;
    $row['price'] = $cartPrice;

    $cartItems[] = $row;
    $baseTotal += $cartPrice * $qty;
    $totalWeightG += $weightG * $qty;
}
if ($totalWeightG <= 0) {
    $totalWeightG = 1000;
}

$currentCouponValue = (float)($_SESSION['applied_coupon']['discount'] ?? 0);
$currentCouponTypeRaw = strtolower((string)($_SESSION['applied_coupon']['discount_type'] ?? 'percent'));
$currentCouponType = in_array($currentCouponTypeRaw, ['percent', 'percentage'], true) ? 'percent' : 'amount';
$currentCouponDiscountAmount = $currentCouponType === 'amount'
    ? min($currentCouponValue, $baseTotal)
    : ($baseTotal * ($currentCouponValue / 100));
$currentCartTotal = max(0, $baseTotal - $currentCouponDiscountAmount);
$cartEmpty = empty($cartItems);

include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>
<main id="shopping-cart-page" class="container-fluid w-100 py-5">
    <h1 class="mb-4 text-center">Winkelmandje</h1>

    <?php if ($cartEmpty): ?>
        <p class="alert alert-warning text-center">Je winkelmandje is leeg.</p>
        <div class="text-center"><a href="/pages/shop" class="btn btn-primary">Verder winkelen</a></div>
    <?php else: ?>
        <div class="row g-4 flex-column flex-md-row">
            <div class="col-12 col-md-6">
                <h4>Verzendadres</h4>
                <div id="shipping_error" class="alert alert-warning" style="display:none;"></div>

                <select id="country" class="form-select border-primary mb-2">
                    <option value="">-- Kies je land --</option>
                    <option value="BE">België</option>
                    <option value="NL">Nederland</option>
                </select>
                <input type="text" id="name" class="form-control mb-2" placeholder="Naam">
                <input type="email" id="email" class="form-control mb-2" placeholder="E-mail">
                <input type="text" id="address" class="form-control mb-2" placeholder="Straat">
                <input type="text" id="number" class="form-control mb-2" placeholder="Nummer">
                <input type="text" id="zipcode" class="form-control mb-2" placeholder="Postcode">
                <input type="text" id="city" class="form-control mb-2" placeholder="Stad">
                <input type="tel" id="phone" class="form-control mb-2" placeholder="Telefoonnummer">
                <input type="hidden" id="cart_weight_g" value="<?= (int)$totalWeightG ?>">

                <div class="mb-4" id="shipping_methods_container" style="display:none;">
                    <h4>Verzendmethode</h4>
                    <div id="shipping_methods" class="ps-2"></div>
                </div>

                <form id="couponForm" method="post" action="/functions/shop/cart/apply_coupon.php" class="mb-3 d-flex align-items-center gap-2">
                    <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Kortingscode" required>
                    <button type="submit" class="btn btn-outline-primary">Toepassen</button>
                </form>
                <div id="coupon_feedback">
                    <?php if (isset($_SESSION['applied_coupon'])): ?>
                        <div class="alert alert-success m-0 d-flex justify-content-between align-items-center">
                            <span>
                                Coupon <strong><?= htmlspecialchars($_SESSION['applied_coupon']['code']) ?></strong>
                                toegepast
                                (<?= $currentCouponType === 'percent'
                                    ? ((float)$currentCouponValue . '%')
                                    : ('€' . number_format((float)$currentCouponValue, 2, ',', '.')) ?>)
                            </span>
                            <button id="remove_coupon_btn" class="btn btn-sm btn-outline-danger ms-2">X</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="mb-4 text-center">
                    <h4 class="mb-1">Totaalprijs</h4>
                    <p id="coupon_line" class="text-danger" style="display: <?= $currentCouponValue ? 'block' : 'none' ?>;">
                        Korting:
                        -<?= $currentCouponType === 'percent'
                            ? ((float)$currentCouponValue . '%')
                            : ('€' . number_format($currentCouponDiscountAmount, 2, ',', '.')) ?>
                    </p>
                    <p
                        id="total_price"
                        class="fs-4 fw-semibold text-success"
                        data-base-subtotal="<?= number_format($baseTotal, 2, '.', '') ?>"
                        data-current-coupon-value="<?= number_format($currentCouponValue, 2, '.', '') ?>"
                        data-current-coupon-type="<?= htmlspecialchars($currentCouponType) ?>"
                    >
                        €<?= number_format($currentCartTotal, 2, ',', '.') ?>
                    </p>
                </div>

                <ul class="list-group mb-4 shadow-sm rounded">
                    <?php foreach ($cartItems as $item): ?>
                        <li class="list-group-item d-flex align-items-center flex-wrap">
                            <img src="<?= htmlspecialchars($item['main_image']); ?>" alt="<?= htmlspecialchars($item['name']); ?>" class="me-3 mb-2" style="width:80px;height:auto;border-radius:5px;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-1 fw-bold"><?= htmlspecialchars($item['name']); ?></h5>
                                    <?php if ($item['discount']): ?>
                                        <span class="badge bg-green align-self-start">-<?= $item['discount']; ?>%</span>
                                    <?php endif; ?>
                                </div>
                                <small>
                                    Aantal: <?= (int)$item['quantity']; ?> |
                                    <?php if ($item['discount']): ?>
                                        <span class="text-muted"><del>€<?= number_format($item['original_price'], 2, ',', '.'); ?></del></span>
                                        <span class="fw-semibold text-success ms-1">€<?= number_format($item['price'], 2, ',', '.'); ?></span>
                                    <?php else: ?>
                                        €<?= number_format($item['price'], 2, ',', '.'); ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="text-center">
                    <button id="checkoutButton" class="btn btn-success btn-lg px-5">Doorgaan naar afrekenen</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<script src="/js/cart.js"></script>
<script src="/js/checkout.js"></script>
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
