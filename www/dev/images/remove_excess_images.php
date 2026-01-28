<?php

// Verbinding maken met de database
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Map waar afbeeldingen worden opgeslagen
$image_dir = $_SERVER['DOCUMENT_ROOT'] . "/images/uploads/";

// Query om alle product_images op te halen
$query = "SELECT id, image_path FROM product_images";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $image_id = $row['id'];
        $image_path = $row['image_path'];
        $full_image_path = $image_dir . basename($image_path); // Maak volledig bestandspad

        // Controleer of de afbeelding fysiek bestaat
        if (!file_exists($full_image_path)) {
            echo "Afbeelding ontbreekt: $image_path\n";

            // Leeg het veld image_path in de database
            $update_query = $conn->prepare("UPDATE product_images SET image_path = NULL WHERE id = ?");
            $update_query->bind_param("i", $image_id);

            if ($update_query->execute()) {
                echo "Image_path leeggemaakt voor ID $image_id.\n";
            } else {
                echo "Fout bij het updaten van ID $image_id: " . $conn->error . "\n";
            }
        } else {
            echo "Afbeelding gevonden: $image_path\n";
        }
    }
} else {
    echo "Geen records gevonden in product_images.\n";
}

// Sluit de databaseverbinding
$conn->close();

?>
