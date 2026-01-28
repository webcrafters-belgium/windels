<?php
// FILE: /functions/shop/cart/get_shipping_methods.php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
header('Content-Type: application/json');

$country = strtoupper(trim($_POST['country'] ?? ''));

if (!$country) {
    echo json_encode(["success" => false, "methods" => []]);
    exit;
}

$sql = "SELECT id, shipper_name, shipping_country, shipping_cost, shipping_duration 
        FROM shipping_methods 
        WHERE shipping_country = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $country);
$stmt->execute();
$res = $stmt->get_result();

$methods = [];
while ($row = $res->fetch_assoc()) {
    $methods[] = [
        "id"       => (int)$row['id'],
        "name"     => $row['shipper_name'],
        "country"  => $row['shipping_country'],
        "cost"     => (float)$row['shipping_cost'],
        "duration" => $row['shipping_duration']
    ];
}

echo json_encode(["success" => true, "methods" => $methods]);

$stmt->close();
$conn->close();
