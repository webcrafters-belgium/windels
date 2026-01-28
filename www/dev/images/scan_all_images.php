<?php

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Map met afbeeldingen
$image_dir = $_SERVER['DOCUMENT_ROOT'] . "/images/uploads/";
$files = scandir($image_dir);

// Voorbereiding van een query om productdetails op te halen
$product_query = $conn->prepare("SELECT sku, title FROM products WHERE sku = ?");
$product_query->bind_param("s", $sku);

// Voorbereiding van een query om gegevens in product_images in te voegen
$insert_query = $conn->prepare("INSERT INTO product_images (SKU, name, alt, image_path) VALUES (?, ?, ?, ?)");
$insert_query->bind_param("ssss", $sku, $name, $alt, $image_path);

// Loop door de bestanden in de map
foreach ($files as $file) {
    if (is_file($image_dir . $file)) {
        // Haal de bestandsnaam zonder extensie op
        $filename = pathinfo($file, PATHINFO_FILENAME);

        // Controleer of de bestandsnaam begint met een SKU in de database
        $sku = preg_replace('/-\d+$/', '', $filename); // Verwijder eventuele suffixen zoals -2
        $product_query->execute();
        $result = $product_query->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $sku = $row['sku'];
            $name = $row['title'];
            $alt = $name; // Gebruik de productnaam als alt-tekst
            $image_path = "/images/uploads/" . $file;

            // Voeg de afbeelding toe aan de product_images tabel
            $insert_query->execute();
        }
    }
}

echo "Afbeeldingen zijn gecontroleerd en toegevoegd aan de database.";
