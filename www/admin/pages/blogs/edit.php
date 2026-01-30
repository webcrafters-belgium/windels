<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit('Ongeldig blog-ID');
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();

if (!$blog) {
    exit('Blog niet gevonden');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $author  = $_POST['author'] ?? $blog['author'];
    $imagePath = $blog['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads/blog/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed, true)) exit('Ongeldig afbeeldingstype');

        $imageName = uniqid('blog_', true) . '.' . $ext;
        $target = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $imagePath = '/images/uploads/blog/' . $imageName;
        }
    }

    $update = $conn->prepare("UPDATE blog_posts SET title = ?, content = ?, image = ?, author = ? WHERE id = ?");
    $update->bind_param("ssssi", $title, $content, $imagePath, $author, $id);
    $update->execute();

    header("Location: /admin/pages/blogs/index.php?updated=1");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-pencil-square accent-primary mr-3"></i>Blog bewerken
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Bewerk de blogpost</p>
        </div>
        <a href="/admin/pages/blogs/index.php" class="glass px-5 py-3 rounded-xl font-semibold hover:bg-white/10 transition flex items-center gap-2" style="color: var(--text-secondary);">
            <i class="bi bi-arrow-left"></i>Terug
        </a>
    </div>
</div>

<!-- FORM -->
<div class="card-glass p-8">
    <form method="post" enctype="multipart/form-data" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col">
                <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Titel</label>
                <input type="text" name="title" value="<?= htmlspecialchars($blog['title']) ?>" required
                       class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Auteur</label>
                <input type="text" name="author" value="<?= htmlspecialchars($blog['author']) ?>" readonly
                       class="w-full px-4 py-3 rounded-xl glass border opacity-60" style="border-color: var(--border-glass);">
            </div>
        </div>

        <div class="flex flex-col">
            <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Inhoud</label>
            <textarea name="content" id="content" rows="12"
                      class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);"><?= htmlspecialchars($blog['content']) ?></textarea>
        </div>

        <?php if (!empty($blog['image'])): ?>
        <div class="flex flex-col">
            <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Huidige afbeelding</label>
            <img src="<?= $blog['image'] ?>" class="rounded-xl max-w-xs border" style="border-color: var(--border-glass);">
        </div>
        <?php endif; ?>

        <div class="flex flex-col">
            <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Nieuwe afbeelding (optioneel)</label>
            <input type="file" name="image" accept="image/*"
                   class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="accent-bg text-white px-8 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
                <i class="bi bi-check-lg"></i>Opslaan
            </button>
            <a href="/admin/pages/blogs/index.php" class="glass px-6 py-3 rounded-xl font-semibold hover:bg-white/10 transition">
                Annuleren
            </a>
        </div>
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
    entity_encoding: 'raw',
    skin: 'oxide-dark',
    content_css: 'dark'
});
</script>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
