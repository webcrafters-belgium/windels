<?php

// Dynamische SEO titel instellen
$pagetitle = $pagetitle ?? "Blog Manager";
$description = $description ?? "Beheer eenvoudig blogs in de Windels Blog Manager.";
$keywords = $keywords ?? "Windels, Product Manager, Voorraadbeheer, E-commerce";
// /pages/blogs/add.php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
function uploadBlogImage($file): array
{
    // Controleer of er een bestand is geüpload
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Geen geldig bestand geüpload.'];
    }

    // Map voor opslag bepalen
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/images/blogs/";
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            return ['success' => false, 'message' => 'Kan de map niet aanmaken.'];
        }
    }

    // Unieke bestandsnaam genereren
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetFile = $uploadDir . uniqid('blog_') . '.' . $fileExtension;

    // Controleer het bestandstype
    $fileType = mime_content_type($file['tmp_name']);
    if (!in_array($fileType, ['image/png', 'image/jpeg', 'image/jpg'])) {
        return ['success' => false, 'message' => 'Alleen PNG- of JPG-bestanden zijn toegestaan.'];
    }

    // Upload het bestand
    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        return ['success' => false, 'message' => 'Fout bij het uploaden van het bestand.'];
    }

    return ['success' => true, 'message' => 'Afbeelding succesvol geüpload.', 'path' => $targetFile];
}

// Verwerk formulier indien ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $imagePath = '';

    if (empty($title) || empty($content)) {
        $message = 'Titel en inhoud zijn verplicht.';
        $success = false;
    } else {
        // Upload afbeelding
        if (!empty($_FILES['blog_image']['name'])) {
            $uploadResult = uploadBlogImage($_FILES['blog_image']);
            if ($uploadResult['success']) {
                $imagePath = $uploadResult['path'];
            } else {
                $message = $uploadResult['message'];
                $success = false;
            }
        }

        // Voeg blog toe aan de database
        $query = "INSERT INTO blog_posts (title, content, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $title, $content, $imagePath);

        if ($stmt->execute()) {
            $message = 'Blog succesvol toegevoegd!';
            $success = true;
        } else {
            $message = 'Fout bij het toevoegen van de blog: ' . $stmt->error;
            $success = false;
        }
    }
}
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

if (isset($message)): ?>
    <p class="<?php echo $success ? 'success' : 'error'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label for="title">Titel:</label>
    <input type="text" name="title" id="title" required>

    <label for="content">Inhoud:</label>
    <textarea name="content" id="content" rows="10" required></textarea>

    <label for="blog_image">Afbeelding Uploaden:</label>
    <input type="file" name="blog_image" id="blog_image">

    <button type="submit">Blog Toevoegen</button>
</form>
<?php

include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
