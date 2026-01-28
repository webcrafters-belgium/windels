<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();

$title   = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';
$author  = $_POST['author'] ?? 'Gast';
$imagePath = null;

/* ===== AFBEELDING ===== */
if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] === UPLOAD_ERR_OK
) {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/blog/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed, true)) {
        exit('Ongeldig afbeeldingstype');
    }

    $imageName   = uniqid('blog_', true) . '.' . $ext;
    $imageTarget = $uploadDir . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $imageTarget)) {
        // DIT pad moet exact overeenkomen met de map hierboven
        $imagePath = '/uploads/blog/' . $imageName;
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
