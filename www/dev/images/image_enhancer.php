<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);


include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Instellingen
$rootDir = $_SERVER['DOCUMENT_ROOT'] . '/images/products/';
$maxWidth = 1200;

// Stap 1: Alle nog niet verwerkte afbeeldingen ophalen uit de database
$sql = "SELECT image_path FROM product_images WHERE is_edited = 0 AND image_path IS NOT NULL";
$result = $conn->query($sql);

$imagesToProcess = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $row['image_path'];
        if (file_exists($fullPath)) {
            $imagesToProcess[] = $fullPath;
        }
    }
}

$totalImages = count($imagesToProcess);
echo "<h3>Gevonden afbeeldingen om te verwerken: $totalImages</h3>";

// Ruwe schatting tijd (stel 1 seconde per afbeelding)
$estimatedTime = $totalImages;
echo "Geschatte verwerkingstijd: ongeveer $estimatedTime seconden.<br><br>";

// Resize en converteer naar webp functie
function convertToWebp($sourcePath, $maxWidth) {
    $pathinfo = pathinfo($sourcePath);
    $newPath = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '.webp';

    if (file_exists($newPath)) {
        return $newPath; // Bestaat al
    }

    $ext = strtolower($pathinfo['extension']);
    if ($ext === 'jpg' || $ext === 'jpeg') {
        $img = @imagecreatefromjpeg($sourcePath);
    } elseif ($ext === 'png') {
        $img = @imagecreatefrompng($sourcePath);
    } else {
        return false;
    }

    if (!$img) return false;

    $width = imagesx($img);
    $height = imagesy($img);
    if ($width > $maxWidth) {
        $newHeight = (int)(($maxWidth / $width) * $height);
        $resized = imagecreatetruecolor($maxWidth, $newHeight);
        imagecopyresampled($resized, $img, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
        $img = $resized;
    }

    imagewebp($img, $newPath, 80);
    imagedestroy($img);

    return $newPath;
}

// Stap 2: Verwerken met progressie
$counter = 0;

foreach ($imagesToProcess as $imgPath) {
    $counter++;
    $webpPath = convertToWebp($imgPath, $maxWidth);

    $relativeOriginal = str_replace($_SERVER['DOCUMENT_ROOT'], '', $imgPath);

    if ($webpPath) {
        $relativeWebp = str_replace($_SERVER['DOCUMENT_ROOT'], '', $webpPath);

        // Update database
        $stmt = $conn->prepare("
            UPDATE product_images 
            SET webp_path = ?, is_edited = 1 
            WHERE image_path = ?
        ");
        $stmt->bind_param("ss", $relativeWebp, $relativeOriginal);
        $stmt->execute();

        echo "✅ [$counter/$totalImages] $relativeOriginal → $relativeWebp bijgewerkt.<br>";
    } else {
        echo "❌ [$counter/$totalImages] $relativeOriginal kon niet geconverteerd worden.<br>";
    }

    // Flush output zodat het real-time zichtbaar blijft in browser
    flush();
    ob_flush();
}

echo "<br><strong>Alles klaar!</strong>";

?>
