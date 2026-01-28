<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

function updateProductImagesFromUploads($uploadsDir, $conn) {
    // Controleer of de map bestaat
    if (!is_dir($uploadsDir)) {
        return ['success' => false, 'message' => 'De map uploads bestaat niet.'];
    }

    // Scan de map voor bestanden
    $files = scandir($uploadsDir);
    if (!$files) {
        return ['success' => false, 'message' => 'Kan de bestanden in de map uploads niet lezen.'];
    }

    $updatedCount = 0;
    foreach ($files as $file) {
        // Sla systeembestanden over
        if ($file === '.' || $file === '..') {
            continue;
        }

        // Haal SKU uit de bestandsnaam
        $sku = pathinfo($file, PATHINFO_FILENAME);
        $filePath = "/images/uploads/$file";

        // Controleer of het SKU in de database staat
        $query = "SELECT id FROM products WHERE sku = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $sku);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update de product_image kolom
            $updateQuery = "UPDATE products SET product_image = ? WHERE sku = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param('ss', $filePath, $sku);

            if ($updateStmt->execute()) {
                $updatedCount++;
            }
        }
    }

    return ['success' => true, 'message' => "$updatedCount afbeeldingen succesvol bijgewerkt."];
}

// Functie gebruiken
$uploadsDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads/';
$result = updateProductImagesFromUploads($uploadsDir, $conn);
if ($result['success']) {
    echo $result['message'];
} else {
    echo "Fout: " . $result['message'];
}
?>
