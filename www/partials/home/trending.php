<?php

// Verbinding met database
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$products = [];

// Query om producten op te halen met een matchende SKU in product_images en een niet-lege image_path
$query = "
    SELECT 
        p.id,
        p.sku,
        p.title,
        p.total_product_price,
        pi.image_path AS product_image
    FROM 
        products p
    INNER JOIN 
        product_images pi ON p.sku = pi.sku
    WHERE 
        pi.is_main_image = 1
        AND pi.image_path IS NOT NULL
    ORDER BY 
        p.created_on DESC
    LIMIT 10
";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        $products[] = $product;
    }
}
?>

<section class="py-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h3>Populaire Producten</h3>
                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col">
                                <div class="product-item">
                                    <figure>
                                        <a href="/pages/shop/product.php?id=<?php echo $product['id']; ?>">
                                            <img src="<?php echo htmlentities($product['product_image'], ENT_QUOTES, 'UTF-8'); ?>"
                                                 alt="<?php echo htmlentities($product['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                                 class="img-fluid">
                                        </a>
                                    </figure>
                                    <h3><?php echo htmlentities($product['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <span class="price">€<?php echo number_format($product['total_product_price'], 2); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Geen producten gevonden.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
