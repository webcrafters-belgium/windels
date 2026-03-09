<?php
function fetchFilteredProducts($conn, string $searchTerm, float $minPrice, float $maxPrice, string $sort, int $limit = 24): array {
    $searchSql = '';
    $params = [];
    $types = '';
    $tagSelect = "NULL AS tag";
    $tagCheck = $conn->query("SHOW COLUMNS FROM products LIKE 'tag'");
    if ($tagCheck && $tagCheck->num_rows > 0) {
        $tagSelect = "p.tag";
    }

    // 🔍 Zoekterm
    if (!empty($searchTerm)) {
        $searchSql .= "AND p.name LIKE ? ";
        $params[] = '%' . $searchTerm . '%';
        $types .= 's';
    }

    // 💶 Prijsfilter
    $searchSql .= "AND p.price BETWEEN ? AND ? ";
    $params[] = $minPrice;
    $params[] = $maxPrice;
    $types .= 'dd';

    // 📂 Categorie (via koppeltabel product_categories)
    if (!empty($_GET['category'])) {
        $searchSql .= "AND c.slug = ? ";
        $params[] = $_GET['category'];
        $types .= 's';
    }

    // 🧩 Subcategorie (via koppeltabel product_subcategories)
    if (!empty($_GET['sub'])) {
        $searchSql .= "AND sc.slug = ? ";
        $params[] = $_GET['sub'];
        $types .= 's';
    }

    // 🔃 Sorteerlogica
    $orderBy = match ($sort) {
        'price_asc' => 'p.price ASC',
        'price_desc' => 'p.price DESC',
        'newest' => 'p.id DESC',
        default => 'RAND()',
    };

    $sql = "
        SELECT 
            p.id,
            p.sku,
            p.name,
            p.description,
            p.slug,
            p.price,
            p.stock_quantity,
            p.stock_status,
            $tagSelect,
            img.image_path,
            img.webp_path
        FROM products p
        LEFT JOIN product_images img ON img.product_id = p.id AND img.is_main = 1
        LEFT JOIN product_categories pc ON pc.product_id = p.id
        LEFT JOIN categories c ON c.id = pc.category_id
        LEFT JOIN product_subcategories psc ON psc.product_id = p.id
        LEFT JOIN subcategories sc ON sc.id = psc.subcategory_id
        WHERE 1=1
        $searchSql
        GROUP BY p.id
        ORDER BY $orderBy
        LIMIT $limit
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $imagePath = null;

        // 🖼 Voorkeur voor .webp
        if (!empty($row['webp_path']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $row['webp_path'])) {
            $imagePath = $row['webp_path'];
        } elseif (!empty($row['image_path']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $row['image_path'])) {
            $imagePath = $row['image_path'];
        } else {
            $imagePath = '/images/products/placeholder.png';
        }

        $row['product_image'] = $imagePath;
        unset($row['image_path'], $row['webp_path']); // optioneel
        $products[] = $row;
    }

    return $products;
}



