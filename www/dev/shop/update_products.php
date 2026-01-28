<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc'; // Zorg voor de juiste databaseverbinding

// Bron-tabellen
$sourceTables = [
    'epoxy_products',
    'kaarsen_products',
    'vers_products'
];

// Tabelnamen en standaardcategorieën (update als nodig)
$defaultCategories = [
    'epoxy' => 1,
    'kaars' => 3,
    'vers' => 4,
];

// Controleer of de categorie bestaat; voeg deze toe als dat niet het geval is
function getOrCreateCategory($conn, $categoryName) {
    // Controleer of de categorie bestaat
    $query = "SELECT id FROM categories WHERE name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $categoryName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Categorie bestaat al
        $row = $result->fetch_assoc();
        return $row['id'];
    } else {
        // Voeg nieuwe categorie toe
        $insertQuery = "INSERT INTO categories (name) VALUES (?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param('s', $categoryName);
        $insertStmt->execute();

        return $conn->insert_id; // Retourneer de ID van de nieuw toegevoegde categorie
    }
}

// Producten toevoegen aan de `products`-tabel
function addProduct($conn, $sourceTable, $row, $categoryId) {
    $insertQuery = "
        INSERT INTO products (source_table, original_id, title, product_image, total_product_price, categorie_id)
        VALUES (?, ?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param(
        'sisssi',
        $sourceTable,
        $row['id'],
        $row['title'],
        $row['product_image'],
        $row['total_product_price'],
        $categoryId
    );

    if (!$stmt->execute()) {
        echo "Fout bij invoegen van product '{$row['title']}' (ID: {$row['id']}): " . $stmt->error . "\n";
    }
}

// Maak de `products`-tabel aan als deze nog niet bestaat
$createTableQuery = "
    CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        source_table VARCHAR(255) NOT NULL,
        original_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        product_image VARCHAR(255),
        total_product_price DECIMAL(10, 2),
        categorie_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE SET NULL
    );
";
$conn->query($createTableQuery);

// Verwerk elk product uit de bron-tabellen
foreach ($sourceTables as $sourceTable) {
    echo "Verwerken van producten uit tabel: $sourceTable\n";

    $query = "SELECT * FROM `$sourceTable`";
    $result = $conn->query($query);

    if ($result === false) {
        echo "Fout bij ophalen van producten uit $sourceTable: " . $conn->error . "\n";
        continue;
    }

    while ($row = $result->fetch_assoc()) {
        // Controleer of de categorie bestaat in het bronrecord
        $categoryName = $row['category'] ?? 'onbekend'; // Gebruik 'onbekend' als er geen categorie is
        $categoryName = strtolower($categoryName); // Normaliseer de naam (kleine letters)

        // Haal de categorie-ID op of maak deze aan
        if (isset($defaultCategories[$categoryName])) {
            $categoryId = $defaultCategories[$categoryName];
        } else {
            $categoryId = getOrCreateCategory($conn, $categoryName);
            $defaultCategories[$categoryName] = $categoryId; // Voeg toe aan de lijst van bekende categorieën
        }

        // Voeg het product toe aan de `products`-tabel
        addProduct($conn, $sourceTable, $row, $categoryId);
    }
}

echo "Alle producten succesvol verwerkt.\n";

// Sluit de databaseverbinding
$conn->close();
?>
