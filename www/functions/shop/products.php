<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Producttabellen die we willen doorlopen
$productTables = ['products'];

$products = [];

foreach ($productTables as $table) {
    // Haal alle products op uit de huidige tabel
    $query = "SELECT * FROM `$table`";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Voeg de productinformatie toe aan de array
            $products[] = $row;
        }
    }
}

// Retourneer de products in JSON-formaat
header('Content-Type: application/json');
echo json_encode($products);

$conn->close();
?>
