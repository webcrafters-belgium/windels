<?php
/*
error_reporting(E_ALL);

ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$sql = "SELECT wd.*,10 AS discount_percentage, p.name, p.price, p.sku, img.image_path AS product_image
        FROM weekly_deals wd
        INNER JOIN products p ON wd.product_id = p.id # weekly_deals product_id --> products id
        LEFT JOIN product_images img ON img.product_id = p.id AND img.is_main = 1
        WHERE CURDATE() BETWEEN wd.start_date AND wd.end_date
        ORDER BY wd.start_date DESC 
        LIMIT 1";



$result = $conn->query($sql);

// 1x ophalen en tonen
$deal = $result && $result->num_rows > 0 ? $result->fetch_assoc() : null;
?>

<?php if ($deal): ?>
    <section class="deal-of-the-week py-5">
        <div class="container">
            <div class="deal-card row align-items-center p-4 rounded-4 shadow bg-light">
                <div class="col-md-5 text-center mb-4 mb-md-0">
                    <img src="<?= $deal['product_image'] ? htmlspecialchars($deal['product_image']) : '/images/placeholder.png' ?>"
                         alt="<?= htmlspecialchars($deal['name']) ?>"
                         class="img-fluid rounded"
                         style="max-height: 280px;">
                </div>
                <div class="col-md-7">
                    <h2 class="text-success mb-3"><?= htmlspecialchars($deal['title']) ?></h2>
                    <h4 class="mb-3"><?= htmlspecialchars($deal['name']) ?></h4>
                    <p class="mb-4"><?= nl2br(htmlspecialchars($deal['description'])) ?></p>

                    <div class="price-info d-flex align-items-center gap-3 mb-4">
                        <div class="text-red text-decoration-line-through h5 mb-0">
                            €<?= number_format($deal['price'], 2, ',', '.') ?>
                        </div>
                        <div class="h4 mb-0 text-success">
                            Nu: €<?= number_format($deal['price'] * (1 - $deal['discount_percentage'] / 100), 2, ',', '.') ?>
                        </div>
                    </div>

                    <a href="/deal-van-de-week"
                       class="btn btn-primary btn-lg">
                        Bekijk product
                    </a>

                </div>
            </div>
        </div>
    </section>
<?php else: ?>
    <div class="container py-5">
        <div class="alert alert-warning text-center">
            <strong>⚠️ Geen weekdeal gevonden.</strong>
        </div>
    </div>
<?php endif; ?>
*/