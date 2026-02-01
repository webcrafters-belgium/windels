<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 0);

include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$maxWidth = 1200;
$message = '';

function convertToWebp($sourcePath, $maxWidth)
{
    $pathinfo = pathinfo($sourcePath);
    $newPath = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '.webp';

    // Bestaat al?
    if (file_exists($newPath)) {
        return $newPath;
    }

    $ext = strtolower($pathinfo['extension']);
    if ($ext === 'jpg' || $ext === 'jpeg') {
        $img = @imagecreatefromjpeg($sourcePath);
    } elseif ($ext === 'png') {
        $img = @imagecreatefrompng($sourcePath);
        imagealphablending($img, false);
        imagesavealpha($img, true);
    } else {
        return false; // Unsupported format
    }
    if (!$img) return false;

    $width = imagesx($img);
    $height = imagesy($img);

    // Resize
    if ($width > $maxWidth) {
        $newHeight = (int)(($maxWidth / $width) * $height);
        $resized = imagecreatetruecolor($maxWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $img, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
        $img = $resized;
    }

    imagewebp($img, $newPath, 80);
    imagedestroy($img);

    return $newPath;
}

// --- Als een product is gekozen ---
if (isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);

    $stmt = $conn->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    $counter = 0;
    $processed = 0;
    while ($row = $result->fetch_assoc()) {
        $counter++;
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $row['image_path'];
        if (!file_exists($fullPath)) {
            $message .= "❌ [$counter] Bestand niet gevonden: {$row['image_path']}<br>";
            continue;
        }

        $webpPath = convertToWebp($fullPath, $maxWidth);
        if ($webpPath) {
            $relativeOriginal = $row['image_path'];
            $relativeWebp = str_replace($_SERVER['DOCUMENT_ROOT'], '', $webpPath);

            $update = $conn->prepare("UPDATE product_images SET webp_path = ?, is_edited = 1 WHERE product_id = ? AND image_path = ?");
            $update->bind_param("sis", $relativeWebp, $productId, $relativeOriginal);
            $update->execute();

            $message .= "✅ [$counter] {$row['image_path']} → $relativeWebp<br>";
            $processed++;
        } else {
            $message .= "❌ [$counter] Kon niet converteren: {$row['image_path']}<br>";
        }
    } 
    $message .= "<br><strong>Klaar: $processed afbeeldingen verwerkt.</strong><br>";
}

// --- Dropdown met alle producten ---
$products = $conn->query("SELECT id, name FROM products ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Product afbeeldingen converteren</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
<h1>Afbeeldingen converteren naar WebP</h1>
<form method="post">
    <label for="product_id">Selecteer product:</label>
    <select name="product_id" id="product_id" required>
        <option value="">-- Kies een product --</option>
        <?php while ($p = $products->fetch_assoc()): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Converteren</button>
</form>

<?php if (!empty($message)): ?>
    <div style="margin-top: 30px;">
        <h3>Resultaat</h3>
        <?= $message ?>
    </div>
<?php endif; ?>
</body>  
</html> 
 