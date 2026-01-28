<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');

header('Content-Type: application/json');

if (!isset($_GET['category_id'])) {
    echo json_encode(['success' => false, 'message' => 'Categorie ID ontbreekt']);
    exit;
}

$category_id = intval($_GET['category_id']);

$sql = "SELECT MAX(CAST(SUBSTRING_INDEX(sku, '-', -1) AS UNSIGNED)) AS max_number FROM products WHERE sku LIKE '{$category_id}-%'";
$result = $conn->query($sql);

$nextNumber = 1;
if ($result && $row = $result->fetch_assoc()) {
    $maxNumber = (int)$row['max_number'];
    if ($maxNumber > 0) {
        $nextNumber = $maxNumber + 1;
    }
}

// Max 5 cijfers, anders foutmelding
if ($nextNumber > 99999) {
    echo json_encode(['success' => false, 'message' => 'SKU nummer limiet bereikt voor deze categorie']);
    exit;
}

$nextSkuPart = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

echo json_encode(['success' => true, 'next_sku' => "{$category_id}-{$nextSkuPart}"]);
