<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Controleren of verbinding werkt
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Tabel leegmaken
$conn->query("TRUNCATE TABLE product_images");


// Query om alle producten met een image_path te selecteren
$query = "SELECT sku, title, product_image FROM products WHERE product_image IS NOT NULL AND product_image != ''";
$result = $conn->query($query);

// Controle of er resultaten zijn
if ($result->num_rows > 0) {
    // Voorbereiden van een query om gegevens in de nieuwe tabel in te voegen
    $insert = $conn->prepare("INSERT INTO product_images (SKU, name, alt, image_path) VALUES (?, ?, ?, ?)");
    $insert->bind_param("ssss", $sku, $name, $alt, $image_path);

    // Door de resultaten itereren
    while ($row = $result->fetch_assoc()) {
        $sku = $row['sku'];
        $name = $row['title'];
        $image_path = $row['product_image'];
        $alt = $name; // Gebruik de naam als alt-tekst

        // Invoegen in de nieuwe tabel
        $insert->execute();
    }
    echo "Gegevens succesvol overgezet naar de product_images tabel.";
} else {
    echo "Geen producten gevonden met een afbeelding.";
}

// Sluit de verbinding
$conn->close();

