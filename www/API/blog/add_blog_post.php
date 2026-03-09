<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /admin/pages/blogs/add.php");
    exit;
}

$title   = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$author  = $_POST['author'] ?? 'Gast';
$imagePath = null;

/* ===== AFBEELDING ===== */
if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] === UPLOAD_ERR_OK
) {
    // Zelfde pad als edit-flow zodat add/edit consistent blijven.
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads/blog/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            exit('Uploadmap kon niet worden aangemaakt.');
        }
    }

    if (!is_writable($uploadDir)) {
        exit('Uploadmap is niet schrijfbaar.');
    }

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed, true)) {
        exit('Ongeldig afbeeldingstype');
    }

    $imageName   = uniqid('blog_', true) . '.' . $ext;
    $imageTarget = $uploadDir . $imageName;

    if (is_uploaded_file($_FILES['image']['tmp_name']) && move_uploaded_file($_FILES['image']['tmp_name'], $imageTarget)) {
        $imagePath = '/images/uploads/blog/' . $imageName;
    } else {
        exit('Afbeelding uploaden is mislukt.');
    }
}

/* ===== DATABASE ===== */
$stmt = $conn->prepare("
    INSERT INTO blog_posts (title, content, image, author, created_at)
    VALUES (?, ?, ?, ?, NOW())
");
$stmt->bind_param("ssss", $title, $content, $imagePath, $author);

if ($stmt->execute()) {
    header("Location: /admin/pages/blogs/index.php?success=1");
    exit;
}

echo "DB fout: " . $stmt->error;
