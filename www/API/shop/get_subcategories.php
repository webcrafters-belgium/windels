<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$category_id = intval($_GET['category_id']);
$res = $conn->query("SELECT id, name FROM subcategories WHERE category_id = $category_id");

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
