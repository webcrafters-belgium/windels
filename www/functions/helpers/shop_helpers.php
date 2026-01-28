<?php

// shop_helpers.php
function getRandomProducts($conn, $limit = 20) {
    $query = "
        SELECT 
            p.id,
            p.sku,
            p.name,
            p.slug,
            p.description,
            p.price,
            p.stock_status,
            p.stock_quantity,
            COALESCE(pi.webp_path, pi.image_path) AS product_image
        FROM 
            products AS p
        LEFT JOIN product_images AS pi 
            ON pi.product_id = p.id AND pi.is_main = 1
        WHERE 
            (pi.webp_path IS NOT NULL OR pi.image_path IS NOT NULL)
            AND p.stock_status = 'instock'
            AND p.stock_quantity > 0
        GROUP BY 
            p.id 
        ORDER BY RAND()
        LIMIT ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($product = $result->fetch_assoc()) {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $product['product_image'];
        if (!file_exists($fullPath)) {
            $product['product_image'] = '/images/products/placeholder.png';
        }
        $products[] = $product;
    }

    return $products;
}
