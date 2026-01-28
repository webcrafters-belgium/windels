<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc'; // Zorg voor de databaseverbinding

// Definieer de producttabellen
$productTables = ['products'];

// Array om unieke categorieën bij te houden
$uniqueCategories = [];

// Loop door elke producttabel
foreach ($productTables as $table) {
    // Haal unieke categorieën op uit de huidige tabel
    $sql = "SELECT DISTINCT category FROM `$table` WHERE category IS NOT NULL";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $category = trim($row['category']); // Verwijder onnodige spaties
            if (!empty($category) && !in_array($category, $uniqueCategories)) {
                $uniqueCategories[] = $category; // Voeg toe aan de lijst als het uniek is
            }
        }
    }
}

// Maak de categorieën-tabel als deze nog niet bestaat
$createTableSQL = "
    CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE
    )
";
$conn->query($createTableSQL);

// Voeg unieke categorieën toe aan de tabel
$insertSQL = "INSERT IGNORE INTO categories (name) VALUES (?)";
$stmt = $conn->prepare($insertSQL);

foreach ($uniqueCategories as $category) {
    $stmt->bind_param('s', $category);
    $stmt->execute();
}

echo "Categorieën succesvol toegevoegd aan de tabel 'categories'.";

// Sluit de statement en verbinding
$stmt->close();
$conn->close();
?>
