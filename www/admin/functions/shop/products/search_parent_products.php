# /admin/functions/shop/products/search_parent_products.php
<?php
declare(strict_types=1);

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
header('Content-Type: application/json; charset=utf-8');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($q === '' || mb_strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$like = '%' . $q . '%';

// Alleen top-level (parent_id IS NULL) tonen als potentiële parent
$sql = "
    SELECT id, sku, name
    FROM products
    WHERE parent_id IS NULL
      AND (name LIKE ? OR sku LIKE ?)
    ORDER BY name ASC
    LIMIT 25
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $like, $like);
$stmt->execute();
$res = $stmt->get_result();

$out = [];
while ($row = $res->fetch_assoc()) {
    $out[] = [
        'id'   => (int)$row['id'],
        'sku'  => $row['sku'],
        'name' => $row['name'],
    ];
}

echo json_encode($out);
