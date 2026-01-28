<?php
// /pages/products/edit.php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

function uploadProductImage($file, $sku): array
{
    // Controleer of er een bestand is geüpload
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Geen geldig bestand geüpload.'];
    }

    // Controleer of de SKU is opgegeven
    if (empty($sku)) {
        return ['success' => false, 'message' => 'SKU is niet opgegeven.'];
    }

    // Map voor opslag bepalen
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/images/products/$sku/";

    // Controleer of de map bestaat, maak deze anders aan
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            return ['success' => false, 'message' => 'Kan de map niet aanmaken.'];
        }
    }

    // Bestandsnaam instellen
    $targetFile = $uploadDir . $sku . '.png';

    // Controleer het bestandstype
    $fileType = mime_content_type($file['tmp_name']);
    if (!in_array($fileType, ['image/png', 'image/jpeg', 'image/jpg'])) {
        return ['success' => false, 'message' => 'Alleen PNG- of JPG-bestanden zijn toegestaan.'];
    }

    // Converteer naar PNG als het geen PNG is
    if ($fileType !== 'image/png') {
        $image = ($fileType === 'image/jpeg' || $fileType === 'image/jpg')
            ? imagecreatefromjpeg($file['tmp_name'])
            : null;

        if ($image === null) {
            return ['success' => false, 'message' => 'Fout bij het verwerken van het bestand.'];
        }

        // Opslaan als PNG
        if (!imagepng($image, $targetFile)) {
            return ['success' => false, 'message' => 'Fout bij het opslaan van het bestand als PNG.'];
        }
        imagedestroy($image);
    } else {
        // Direct uploaden als het al PNG is
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            return ['success' => false, 'message' => 'Fout bij het uploaden van het bestand.'];
        }
    }

    return ['success' => true, 'message' => 'Afbeelding succesvol geüpload.', 'path' => $targetFile];
}

// Haal productgegevens op basis van ID
$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    die('Geen product ID opgegeven.');
}
$query = "SELECT sku, title FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die('Product niet gevonden.');
}

// Verwerk formulier indien ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sku = $product['sku'];
    $uploadResult = uploadProductImage($_FILES['product_image'], $sku);
    $message = $uploadResult['message'];
    $success = $uploadResult['success'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Bewerken</title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
<h1>Product Bewerken</h1>
<p>Product: <?php echo htmlspecialchars($product['title']); ?></p>

<?php if (isset($message)): ?>
    <p class="<?php echo $success ? 'success' : 'error'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="product_image">Afbeelding uploaden:</label>
    <input type="file" name="product_image" id="product_image" required>
    <button type="submit">Afbeelding Uploaden</button>
</form>
</body>
</html>