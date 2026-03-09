<?php

function productsHasTagColumn($conn): bool {
    static $hasTagColumn = null;
    if ($hasTagColumn !== null) {
        return $hasTagColumn;
    }

    $result = $conn->query("SHOW COLUMNS FROM products LIKE 'tag'");
    $hasTagColumn = $result && $result->num_rows > 0;
    return $hasTagColumn;
}

function resolveProductTagBadge(?string $tagValue): ?array {
    $tag = strtolower(trim((string)$tagValue));
    if ($tag === 'new') {
        return ['label' => 'New in assortiment', 'class' => 'bg-success'];
    }
    if ($tag === 'sale') {
        return ['label' => 'Actie', 'class' => 'bg-danger'];
    }
    return null;
}

function fetchHomeProducts($conn, string $mode = 'random', int $limit = 8): array {
    $hasTagColumn = productsHasTagColumn($conn);
    $tagSelect = $hasTagColumn ? 'p.tag' : 'NULL AS tag';

    $baseQuery = "
        SELECT 
            p.id,
            p.sku,
            p.name,
            p.slug,
            p.description,
            p.price,
            p.stock_status,
            p.stock_quantity,
            {$tagSelect},
            COALESCE(pi.webp_path, pi.image_path) AS product_image
        FROM products AS p
        LEFT JOIN product_images AS pi
            ON pi.product_id = p.id AND pi.is_main = 1
        WHERE
            (pi.webp_path IS NOT NULL OR pi.image_path IS NOT NULL)
            AND p.stock_status = 'instock'
            AND p.stock_quantity > 0
    ";

    if ($mode === 'sale') {
        if (!$hasTagColumn) {
            return [];
        }
        $baseQuery .= " AND LOWER(COALESCE(p.tag, '')) = 'sale' ";
    }

    $baseQuery .= " GROUP BY p.id ";

    if ($mode === 'newest') {
        $baseQuery .= " ORDER BY p.created_at DESC ";
    } elseif ($mode === 'sale') {
        $baseQuery .= " ORDER BY p.created_at DESC ";
    } else {
        $baseQuery .= " ORDER BY RAND() ";
    }

    $baseQuery .= " LIMIT ? ";

    $stmt = $conn->prepare($baseQuery);
    $stmt->bind_param('i', $limit);
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

// shop_helpers.php
function getRandomProducts($conn, $limit = 20) {
    return fetchHomeProducts($conn, 'random', (int)$limit);
}

function getNewestProducts($conn, $limit = 8) {
    return fetchHomeProducts($conn, 'newest', (int)$limit);
}

function getSaleProducts($conn, $limit = 8) {
    return fetchHomeProducts($conn, 'sale', (int)$limit);
}
