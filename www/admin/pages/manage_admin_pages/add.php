<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $icon_class = $_POST['icon_class'];
    $url = $_POST['url'];
    $section = $_POST['section'];
    $visibility = $_POST['visibility'];
    $roles = $_POST['roles'];
    $display_order = (int)$_POST['display_order'];
    $user_id = 0;

    $stmt = $conn->prepare("INSERT INTO admin_pages (title, icon_class, url, section, visibility, roles, display_order, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssii", $title, $icon_class, $url, $section, $visibility, $roles, $display_order, $user_id);
    if ($stmt->execute()) {
        header("Location: /admin/");
        exit;
    } else {
        $error = "Fout bij toevoegen.";
    }
}
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-plus-circle accent-primary mr-3"></i>Adminpagina toevoegen
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Maak een nieuwe snelkoppeling aan voor het dashboard</p>
        </div>
        <a href="/admin/" class="glass px-5 py-3 rounded-xl font-semibold hover:bg-white/10 transition flex items-center gap-2" style="color: var(--text-secondary);">
            <i class="bi bi-arrow-left"></i>Terug
        </a>
    </div>
</div>

<?php if (isset($error)): ?>
<div class="card-glass p-4 mb-6 border-rose-500/30 bg-rose-500/10">
    <div class="flex items-center space-x-3 text-rose-400">
        <i class="bi bi-exclamation-triangle"></i>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
</div>
<?php endif; ?>

<!-- FORM -->
<div class="card-glass p-8">
    <form method="post" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col">
                <label for="title" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Titel</label>
                <input type="text" name="title" id="title" required
                       class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);"
                       placeholder="Bijv. Producten beheer">
            </div>

            <div class="flex flex-col">
                <label for="icon_class" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Icon Class (bijv. bi-box)</label>
                <input type="text" name="icon_class" id="icon_class"
                       class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);"
                       placeholder="bi-box-seam">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col">
                <label for="url" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">URL (relatief)</label>
                <input type="text" name="url" id="url" required
                       class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);"
                       placeholder="/pages/products/">
            </div>

            <div class="flex flex-col">
                <label for="section" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Sectie</label>
                <input type="text" name="section" id="section" required
                       class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);"
                       placeholder="Webshop">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex flex-col">
                <label for="visibility" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Zichtbaarheid</label>
                <select name="visibility" id="visibility"
                        class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
                    <option value="visible">Zichtbaar</option>
                    <option value="hidden">Verborgen</option>
                </select>
            </div>

            <div class="flex flex-col">
                <label for="roles" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Toegestane Rol</label>
                <select name="roles" id="roles"
                        class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="user">User</option>
                </select>
            </div>

            <div class="flex flex-col">
                <label for="display_order" class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Volgorde</label>
                <input type="number" name="display_order" id="display_order" value="0"
                       class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="accent-bg text-white px-8 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
                <i class="bi bi-check-lg"></i>Opslaan
            </button>
            <a href="/admin/" class="glass px-6 py-3 rounded-xl font-semibold hover:bg-white/10 transition">
                Annuleren
            </a>
        </div>
    </form>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
