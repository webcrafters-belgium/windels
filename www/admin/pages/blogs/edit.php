<?php
// FILE: /admin/pages/blog/edit.php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit('Ongeldig blog-ID');
}

$id = (int)$_GET['id'];

/* ===== HUIDIGE BLOG OPHALEN ===== */
$stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();

if (!$blog) {
    exit('Blog niet gevonden');
}

/* ===== OPSLAAN ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $author  = $_POST['author'] ?? $blog['author'];
    $imagePath = null;

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === UPLOAD_ERR_OK
    ) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads/blog/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed, true)) {
            exit('Ongeldig afbeeldingstype');
        }

        $imageName = uniqid('blog_', true) . '.' . $ext;
        $target = $uploadDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            exit('move_uploaded_file faalde');
        }

        // DIT is wat in de DB moet
        $imagePath = '/images/uploads/blog/' . $imageName;
    }

    $update = $conn->prepare("
        UPDATE blog_posts
        SET title = ?, content = ?, image = ?, author = ?
        WHERE id = ?
    ");
    $update->bind_param("ssssi", $title, $content, $imagePath, $author, $id);
    $update->execute();

    header("Location: /admin/pages/blogs/index.php?updated=1");
    exit;
}
?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php'; ?>

<div class="p-8 text-gray-200 max-w-3xl">
    <h1 class="text-2xl font-bold mb-6">Blog bewerken</h1>

    <form method="post" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label>Titel</label>
            <input type="text" name="title" value="<?= htmlspecialchars($blog['title']) ?>" required>
        </div>

        <div>
            <label>Auteur</label>
            <input type="text" name="author" value="<?= htmlspecialchars($blog['author']) ?>" readonly>
        </div>

        <div>
            <label>Inhoud</label>
            <textarea name="content" id="content" rows="10"><?= htmlspecialchars($blog['content']) ?></textarea>
        </div>

        <?php if (!empty($blog['image'])): ?>
            <div>
                <label>Huidige afbeelding</label><br>
                <img src="<?= $blog['image'] ?>" style="max-width:300px;border-radius:8px">
            </div>
        <?php endif; ?>

        <div>
            <label>Nieuwe afbeelding (optioneel)</label>
            <input type="file" name="image">
        </div>

        <button type="submit" class="btn btn-primary">Opslaan</button>
    </form>
</div>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/xdjt6vqjx1h0oh9f9f084xr4z88g2dppg86b1c9atd4dvhfn/tinymce/6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#content',
    plugins: 'link image code lists table',
    toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | code',
    height: 400,
    language: 'nl',
    entity_encoding: 'raw'
});
</script>
