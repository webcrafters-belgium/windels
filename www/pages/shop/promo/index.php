<?php

include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$stmt = $conn->prepare("
    SELECT 
        p.id,
        p.name,
        p.sku,
        p.price,
        p.description,
        pi.image_path,
        c.id AS category_id,
        c.name AS category_name
    FROM products p
    LEFT JOIN product_images pi 
        ON pi.sku = p.sku AND pi.is_main = 1
    LEFT JOIN product_categories pc 
        ON pc.product_id = p.id
    LEFT JOIN categories c 
        ON c.id = pc.category_id
    ORDER BY p.name ASC
");

$stmt->execute();
$result = $stmt->get_result();

echo "
<div class='main promo container-fluid'>
    <div class='promo-container'>
        <div class='page-title blog-featured-title featured-title no-overflow'>
            <div class='page-title-bg fill'>
                <div class='title-overlay fill' style='background-color: rgba(0,0,0,.5)'></div>
            </div>
            <div class='page-title-inner container flex-row dark is-large' style='min-height: 300px'>
                <div class='flex-col flex-grow medium-text-center'>
                    <h1 class='uppercase'>Actieve Promoties</h1>
                </div>
            </div>
        </div>

        <div class='container promo-content'>
            <div class='row'>
";

while ($row = $result->fetch_assoc()) {
    $img = $row['image_path'] ?: '/images/placeholder.png';
    $productLink = "/pages/shop/products/product.php?id=" . $row['id'];

    $originalPrice = (float)$row['price'];
    $categoryId = (int)$row['category_id'];

    // Korting berekenen
    $discountPercentage = 0;
    if ($categoryId === 1) {
        $discountPercentage = 30;
    } elseif ($categoryId === 2) {
        $discountPercentage = 20;
    } elseif ($categoryId === 4) {
        $discountPercentage = 10;
    }

    // Enkel producten met korting tonen
    if ($discountPercentage === 0) {
        continue;
    }

    $discountedPrice = $originalPrice * (1 - $discountPercentage / 100);
    $priceFormatted = number_format($discountedPrice, 2, ',', '.');
    $originalFormatted = number_format($originalPrice, 2, ',', '.');

    echo "
        <div class='col-md-4'>
            <div class='promo-card'>
                <img src='{$img}' alt='{$row['name']}' class='sm-w-50 w-25'>
                <h3>{$row['name']}</h3>
                <p class='desc'>{$row['description']}</p>
                <p><del>€ {$originalFormatted}</del> <strong>€ {$priceFormatted}</strong></p>
                <a href='{$productLink}' class='btn btn-primary'>Bekijk product</a>
            </div>
        </div>
    ";
}


echo "
            </div>
        </div>
    </div>
</div>
";

include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
