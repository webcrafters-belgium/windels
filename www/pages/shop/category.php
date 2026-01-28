<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

// --- GET VARIABELEN ---
$categorySlug = $_GET['category'] ?? '';

if ($categorySlug === 'geen-categorie') {
    $categorySlug = '';
}

$searchTerm = $_GET['search'] ?? '';

// --- CATEGORIE ophalen of "alles" ---
$category = null;
$categoryId = null;

if (!empty($categorySlug)) {
    $sql = "SELECT id, name FROM categories WHERE slug = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $categorySlug);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    if ($category) {
        $categoryId = $category['id'];
    }
}

// --- Zoekopdracht loggen ---
if (!empty($searchTerm)) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $searchDate = date('Y-m-d');
    $logStmt = $conn->prepare("INSERT INTO search_queries (query, user_ip, search_date) VALUES (?, ?, ?)");
    $logStmt->bind_param("sss", $searchTerm, $ip, $searchDate);
    $logStmt->execute();
    $logStmt->close();
}

// --- SUBCATEGORIEËN ophalen voor deze categorie (of alle subcats) ---
$subcategoryIds = [];
if ($categoryId) {
    $subcatStmt = $conn->prepare("SELECT id FROM subcategories WHERE category_id = ?");
    $subcatStmt->bind_param("i", $categoryId);
    $subcatStmt->execute();
    $subcatResult = $subcatStmt->get_result();
    while ($row = $subcatResult->fetch_assoc()) {
        $subcategoryIds[] = $row['id'];
    }
    $subcatStmt->close();
}

// --- ALLE categorieën ophalen voor filters ---
$allCategories = [];
$catResult = $conn->query("SELECT id, name, slug FROM categories ORDER BY name ASC");
while ($row = $catResult->fetch_assoc()) {
    $allCategories[] = $row;
}

// --- PRODUCTEN ophalen ---
$sql = "
    SELECT 
        p.id, 
        p.name, 
        p.price, 
        p.stock_quantity, 
        p.sku, 
        img.image_path 
    FROM products p 
    LEFT JOIN product_images img 
        ON img.product_id = p.id AND img.is_main = 1
    WHERE 
        p.stock_quantity > 0 
";

// --- FILTEREN op categorie of subcategorie ---
$stmtParams = [];
$paramTypes = '';

if ($categoryId) {
    $sql .= " AND ( 
        p.id IN (SELECT product_id FROM product_categories WHERE category_id = ?) 
    ";
    $stmtParams[] = $categoryId;
    $paramTypes .= 'i';

    if (!empty($subcategoryIds)) {
        $placeholders = implode(',', array_fill(0, count($subcategoryIds), '?'));
        $sql .= " OR p.id IN (SELECT product_id FROM product_subcategories WHERE subcategory_id IN ($placeholders)) ";
        foreach ($subcategoryIds as $subId) {
            $stmtParams[] = $subId;
            $paramTypes .= 'i';
        }
    }
    $sql .= ") ";
}

// --- FILTEREN op zoekterm ---
if (!empty($searchTerm)) {
    $sql .= "AND p.name LIKE ? ";
    $stmtParams[] = '%' . $searchTerm . '%';
    $paramTypes .= 's';
}

$sql .= "ORDER BY p.name ASC";

// --- QUERY uitvoeren ---
$stmt = $conn->prepare($sql);

if (!empty($stmtParams)) {
    $stmt->bind_param($paramTypes, ...$stmtParams);
}

$stmt->execute();
$productResult = $stmt->get_result();

$products = [];

while ($row = $productResult->fetch_assoc()) {
    if (empty($row['image_path']) || !file_exists($_SERVER['DOCUMENT_ROOT'] . $row['image_path'])) {
        $row['image_path'] = "/images/products/placeholder.png";
    }
    $products[] = $row;
}

?>

<section class="py-5">
    <div class="container">

        <h1 class="mb-4">
            <?php
            if ($category) {
                echo htmlspecialchars($category['name']);
            } else {
                echo "Alle producten";
            }
            ?>
        </h1>

        <!-- FILTERS -->
        <div class="mb-4">
            <strong>Categorieën:</strong>
            <a href="/pages/shop/category.php" class="btn btn-outline-secondary btn-sm <?= (!$category) ? 'active' : '' ?>">Alle</a>
            <?php foreach ($allCategories as $cat): ?>
                <a href="/pages/shop/category.php?category=<?= htmlspecialchars($cat['slug']); ?><?= (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '') ?>"
                   class="btn btn-outline-secondary btn-sm <?= ($categorySlug === $cat['slug']) ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Zoekterm -->
        <?php if (!empty($searchTerm)): ?>
            <p>Zoekresultaten voor: <strong><?= htmlspecialchars($searchTerm); ?></strong></p>
        <?php endif; ?>

        <!-- PRODUCTEN -->
        <?php if (count($products) > 0): ?>
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <a href="/pages/shop/products/product.php?id=<?= $product['id']; ?>">
                                <img src="<?= htmlspecialchars($product['image_path']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="card-img-top img-fluid" style="height:250px;object-fit:cover;">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text"><strong>Prijs:</strong> €<?= number_format($product['price'], 2, ',', '.'); ?></p>
                                <p class="<?= ($product['stock_quantity'] > 0) ? 'text-success' : 'text-danger' ?>">
                                    <?= ($product['stock_quantity'] > 0) ? 'Op voorraad' : 'Uitverkocht' ?>
                                </p>
                                <a href="/pages/shop/products/product.php?id=<?= $product['id']; ?>" class="btn btn-primary mt-auto">Bekijk product</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Geen producten gevonden.</p>
        <?php endif; ?>

    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
