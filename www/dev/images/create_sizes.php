<?php

function resizeImages($sourceDir, $targetDir, $dimensions = [800, 600, 400, 200]): void
{
    // Controleer of de bron- en doelmap bestaan
    if (!is_dir($sourceDir) || !is_dir($targetDir)) {
        echo "Bron- of doelmap bestaat niet.\n";
        return;
    }

    // Ondersteunde afbeeldingsformaten
    $supportedFormats = ['jpg', 'jpeg', 'png', 'gif'];

    // Lees alle bestanden in de bronmap
    $files = scandir($sourceDir);

    foreach ($files as $file) {
        $filePath = $sourceDir . '/' . $file;
        $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        // Sla over als het geen ondersteund formaat is
        if (!in_array($fileExtension, $supportedFormats)) {
            echo "Bestand $file is geen ondersteund formaat. Overgeslagen.\n";
            continue;
        }

        // Laad de afbeelding in op basis van het formaat
        switch ($fileExtension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($filePath);
                break;
            case 'png':
                $image = imagecreatefrompng($filePath);
                break;
            case 'gif':
                $image = imagecreatefromgif($filePath);
                break;
            default:
                echo "Kon $file niet verwerken. Onbekend formaat.\n";
                continue;
        }

        if (!$image) {
            echo "Kon afbeelding $file niet laden.\n";
            continue;
        }

        // Bepaal de originele afmetingen
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);

        echo "Verwerken van bestand $file (Oorspronkelijke afmetingen: {$originalWidth}x{$originalHeight})...\n";

        // Verklein en sla op in meerdere formaten en afmetingen
        foreach ($dimensions as $newWidth) {
            // Bereken de nieuwe afmetingen met behoud van de aspect ratio
            $newHeight = ($newWidth / $originalWidth) * $originalHeight;

            // Maak een nieuwe afbeelding met de nieuwe afmetingen
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

            // Kopieer de originele afbeelding naar de nieuwe afbeelding
            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            // Opslaan in PNG en WebP
            $baseName = pathinfo($file, PATHINFO_FILENAME);

            // Sla op als PNG
            $outputFilePng = $targetDir . '/' . $baseName . '_' . $newWidth . 'px.png';
            imagepng($resizedImage, $outputFilePng);

            // Sla op als WebP
            $outputFileWebp = $targetDir . '/' . $baseName . '_' . $newWidth . 'px.webp';
            imagewebp($resizedImage, $outputFileWebp, 80); // Kwaliteit 80

            echo "- Verkleind naar {$newWidth}px en opgeslagen als PNG: {$outputFilePng}\n";
            echo "- Verkleind naar {$newWidth}px en opgeslagen als WebP: {$outputFileWebp}\n";

            // Ruim de verkleinde afbeelding op
            imagedestroy($resizedImage);
        }

        // Ruim de originele afbeelding op
        imagedestroy($image);

        echo "Verwerking van $file voltooid.\n\n";
    }
}

// Gebruik de functie
$sourceDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads/';
$targetDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads/resized/';

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true); // Maak de doelmap als deze niet bestaat
}

resizeImages($sourceDir, $targetDir, [800, 600, 400, 200]);
?>
