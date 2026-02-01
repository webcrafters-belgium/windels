<?php
require_once __DIR__ . '/../../includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /admin/pages/blogs/index.php");
    exit;
}

$id = (int)$_GET['id'];

// Get current blog
$stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();

if (!$blog) {
    header("Location: /admin/pages/blogs/index.php");
    exit;
}

$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $author  = $_POST['author'] ?? $blog['author'];
    $imagePath = $blog['image']; // Keep existing image by default

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads/blog/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed, true)) {
            $imageName = uniqid('blog_', true) . '.' . $ext;
            $target = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $imagePath = '/images/uploads/blog/' . $imageName;
            }
        }
    }

    $update = $conn->prepare("
        UPDATE blog_posts
        SET title = ?, content = ?, image = ?, author = ?
        WHERE id = ?
    ");
    $update->bind_param("ssssi", $title, $content, $imagePath, $author, $id);
    
    if ($update->execute()) {
        $message = 'Blog succesvol bijgewerkt!';
        $messageType = 'success';
        // Refresh blog data
        $stmt->execute();
        $blog = $stmt->get_result()->fetch_assoc();
    } else {
        $message = 'Er is een fout opgetreden bij het opslaan.';
        $messageType = 'error';
    }
}
?>

<!-- Main Content -->
<div class="max-w-4xl mx-auto animate-fadeInUp">
    
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold glow-text">Blog bewerken</h1>
            <p class="text-sm mt-1" style="color: var(--text-muted);">Bewerk de blogpost</p>
        </div>
        <a href="/admin/pages/blogs/index.php" 
           class="glass px-4 py-2 rounded-xl flex items-center space-x-2 hover:bg-white/10 transition"
           data-testid="back-to-blogs-btn">
            <i class="bi bi-arrow-left"></i>
            <span>Terug naar overzicht</span>
        </a>
    </div>

    <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-xl <?= $messageType === 'success' ? 'bg-emerald-500/20 border border-emerald-500/30 text-emerald-400' : 'bg-rose-500/20 border border-rose-500/30 text-rose-400' ?>">
            <i class="bi bi-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?> mr-2"></i>
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Edit Form -->
    <div class="card-glass p-8">
        <form method="post" enctype="multipart/form-data" class="space-y-6" data-testid="blog-edit-form">
            
            <!-- Title -->
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                    <i class="bi bi-type mr-2 text-teal-400"></i>Titel
                </label>
                <input type="text" 
                       name="title" 
                       value="<?= htmlspecialchars($blog['title']) ?>" 
                       required
                       class="w-full px-4 py-3 rounded-xl glass border border-transparent focus:border-teal-500 transition"
                       style="background: var(--bg-glass); color: var(--text-primary);"
                       data-testid="blog-title-input">
            </div>

            <!-- Author -->
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                    <i class="bi bi-person mr-2 text-teal-400"></i>Auteur
                </label>
                <input type="text" 
                       name="author" 
                       value="<?= htmlspecialchars($blog['author']) ?>" 
                       readonly
                       class="w-full px-4 py-3 rounded-xl glass border border-transparent opacity-60 cursor-not-allowed"
                       style="background: var(--bg-glass); color: var(--text-primary);">
            </div>

            <!-- Content -->
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                    <i class="bi bi-text-paragraph mr-2 text-teal-400"></i>Inhoud
                </label>
                <textarea name="content" 
                          id="content" 
                          rows="12"
                          class="w-full px-4 py-3 rounded-xl glass border border-transparent focus:border-teal-500 transition"
                          style="background: var(--bg-glass); color: var(--text-primary);"
                          data-testid="blog-content-input"><?= htmlspecialchars($blog['content']) ?></textarea>
            </div>

            <!-- Current Image -->
            <?php if (!empty($blog['image'])): ?>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-image mr-2 text-teal-400"></i>Huidige afbeelding
                    </label>
                    <div class="glass p-4 rounded-xl inline-block">
                        <img src="<?= htmlspecialchars($blog['image']) ?>" 
                             alt="Blog afbeelding" 
                             class="max-w-xs rounded-lg">
                    </div>
                </div>
            <?php endif; ?>

            <!-- New Image -->
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                    <i class="bi bi-upload mr-2 text-teal-400"></i>Nieuwe afbeelding (optioneel)
                </label>
                <input type="file" 
                       name="image" 
                       accept="image/jpeg,image/png,image/webp"
                       class="w-full px-4 py-3 rounded-xl glass border border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-teal-500/20 file:text-teal-400 hover:file:bg-teal-500/30 transition"
                       style="background: var(--bg-glass); color: var(--text-primary);"
                       data-testid="blog-image-input">
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4 pt-4">
                <a href="/admin/pages/blogs/index.php" 
                   class="px-6 py-3 rounded-xl glass hover:bg-white/10 transition">
                    Annuleren
                </a>
                <button type="submit" 
                        class="accent-bg px-6 py-3 rounded-xl font-semibold text-white flex items-center space-x-2"
                        data-testid="blog-save-btn">
                    <i class="bi bi-check-lg"></i>
                    <span>Opslaan</span>
                </button>
            </div>
        </form>
    </div>
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

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
