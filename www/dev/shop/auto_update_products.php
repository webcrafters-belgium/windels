<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Bron-tabellen
$sourceTables = [
    'epoxy_products',
    'kaarsen_products',
    'vers_products'
];

// Verwerk elk product uit de bron-tabellen
foreach ($sourceTables as $sourceTable) {
    echo "Verwerken van products uit tabel: $sourceTable\n";

    $query = "SELECT * FROM `$sourceTable`";
    $result = $conn->query($query);

    if ($result === false) {
        echo "Fout bij ophalen van products uit $sourceTable: " . $conn->error . "\n";
        continue;
    }

    while ($row = $result->fetch_assoc()) {
        // Controleer of het product al bestaat in de `products`-tabel
        $checkQuery = "SELECT id FROM products WHERE sku = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param('s', $row['sku']);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo "Product met SKU {$row['sku']} bestaat al, overslaan...\n";
            continue; // Ga verder met de volgende iteratie
        }

        // Insert query voor de `products`-tabel
        $insertQuery = "
            INSERT INTO products (
                sku, title, product_image, product_description, 
                amount_grams, price_per_gram, extra_parts_price, margin, 
                hours_worked, created_by_user, company_cost_per_product, 
                sold_in_branches, vat_percentage, total_product_price, 
                created_on, shipping_method, category, stock, hourly_rate
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param(
            'ssssdddddidsddsssid',
            $row['sku'],
            $row['title'],
            $row['product_image'],
            $row['product_description'],
            $row['amount_grams'],
            $row['price_per_gram'],
            $row['extra_parts_price'],
            $row['margin'],
            $row['hours_worked'],
            $row['created_by_user'],
            $row['company_cost_per_product'],
            $row['sold_in_branches'],
            $row['vat_percentage'],
            $row['total_product_price'],
            $row['created_on'],
            $row['shipping_method'],
            $row['category'],
            $row['stock'],
            $row['hourly_rate']
        );

        if (!$stmt->execute()) {
            echo "Fout bij invoegen van product '{$row['title']}' (SKU: {$row['sku']}): " . $stmt->error . "\n";
        } else {
            echo "Product '{$row['title']}' toegevoegd aan de products-tabel.\n";
        }
    }
}

echo "Alle products succesvol verwerkt.\n";

// Sluit de databaseverbinding
$conn->close();

