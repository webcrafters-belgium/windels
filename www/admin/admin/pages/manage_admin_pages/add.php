<?php
require_once __DIR__ . '/../../includes/header.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $icon_class = $_POST['icon_class'] ?? '';
    $url = $_POST['url'] ?? '';
    $section = $_POST['section'] ?? '';
    $visibility = $_POST['visibility'] ?? 'visible';
    $roles = $_POST['roles'] ?? 'admin';
    $display_order = (int)($_POST['display_order'] ?? 0);
    $user_id = 0;

    $stmt = $conn->prepare("INSERT INTO admin_pages (title, icon_class, url, section, visibility, roles, display_order, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssii", $title, $icon_class, $url, $section, $visibility, $roles, $display_order, $user_id);
    
    if ($stmt->execute()) {
        header("Location: /admin/?created=1");
        exit;
    } else {
        $message = 'Er is een fout opgetreden bij het toevoegen.';
        $messageType = 'error';
    }
}
?>

<!-- Main Content -->
<div class="max-w-2xl mx-auto animate-fadeInUp">
    
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold glow-text">Adminpagina toevoegen</h1>
            <p class="text-sm mt-1" style="color: var(--text-muted);">Voeg een nieuwe snelkoppeling toe aan het admin dashboard</p>
        </div>
        <a href="/admin/" 
           class="glass px-4 py-2 rounded-xl flex items-center space-x-2 hover:bg-white/10 transition">
            <i class="bi bi-arrow-left"></i>
            <span>Terug</span>
        </a>
    </div>

    <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-xl bg-rose-500/20 border border-rose-500/30 text-rose-400">
            <i class="bi bi-exclamation-circle mr-2"></i>
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="card-glass p-8">
        <form method="post" class="space-y-6" data-testid="add-admin-page-form">
            
            <!-- Title -->
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                    <i class="bi bi-type mr-2 text-teal-400"></i>Titel <span class="text-rose-400">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       required
                       placeholder="Bijv. Producten, Orders..."
                       class="w-full px-4 py-3 rounded-xl glass border border-transparent focus:border-teal-500 transition"
                       style="background: var(--bg-glass); color: var(--text-primary);">
            </div>

            <!-- Icon Class -->
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                    <i class="bi bi-emoji-smile mr-2 text-teal-400"></i>Icon Class
                </label>
                <input type="text" 
                       name="icon_class" 
                       placeholder="Bijv. bi-box, bi-cart, bi-gear..."
                       class="w-full px-4 py-3 rounded-xl glass border border-transparent focus:border-teal-500 transition"
                       style="background: var(--bg-glass); color: var(--text-primary);">
                <p class="text-xs mt-1" style="color: var(--text-muted);">
                    Gebruik Bootstrap Icons klassen. <a href="https://icons.getbootstrap.com/" target="_blank" class="text-teal-400 hover:underline">Bekijk alle iconen</a>
                </p>
            </div>

            <!-- URL -->
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                    <i class="bi bi-link-45deg mr-2 text-teal-400"></i>URL (relatief) <span class="text-rose-400">*</span>
                </label>
                <input type="text" 
                       name="url" 
                       required
                       placeholder="Bijv. /admin/pages/products/"
                       class="w-full px-4 py-3 rounded-xl glass border border-transparent focus:border-teal-500 transition"
                       style="background: var(--bg-glass); color: var(--text-primary);">
            </div>

            <!-- Section -->
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                    <i class="bi bi-folder mr-2 text-teal-400"></i>Sectie <span class="text-rose-400">*</span>
                </label>
                <input type="text" 
                       name="section" 
                       required
                       placeholder="Bijv. Webshop, Marketing, Tools..."
                       class="w-full px-4 py-3 rounded-xl glass border border-transparent focus:border-teal-500 transition"
                       style="background: var(--bg-glass); color: var(--text-primary);">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Visibility -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-eye mr-2 text-teal-400"></i>Zichtbaarheid
                    </label>
                    <select name="visibility" 
                            class="w-full px-4 py-3 rounded-xl glass border border-transparent focus:border-teal-500 transition"
                            style="background: var(--bg-glass); color: var(--text-primary);">
                        <option value="visible">Zichtbaar</option>
                        <option value="hidden">Verborgen</option>
                    </select>
                </div>

                <!-- Roles -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        <i class="bi bi-shield-check mr-2 text-teal-400"></i>Toegestane Rol
                    </label>
                    <select name="roles" 
                            class="w-full px-4 py-3 rounded-xl glass border border-transparent focus:border-teal-500 transition"
                            style="background: var(--bg-glass); color: var(--text-primary);">
                        <option value="admin">Admin</option>
                        <option value="editor">Editor</option>
                        <option value="user">User</option>
                    </select>
                </div>
            </div>

            <!-- Display Order -->
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                    <i class="bi bi-sort-numeric-down mr-2 text-teal-400"></i>Volgorde
                </label>
                <input type="number" 
                       name="display_order" 
                       value="0"
                       class="w-full px-4 py-3 rounded-xl glass border border-transparent focus:border-teal-500 transition"
                       style="background: var(--bg-glass); color: var(--text-primary);">
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4 pt-4">
                <a href="/admin/" 
                   class="px-6 py-3 rounded-xl glass hover:bg-white/10 transition">
                    Annuleren
                </a>
                <button type="submit" 
                        class="accent-bg px-6 py-3 rounded-xl font-semibold text-white flex items-center space-x-2">
                    <i class="bi bi-plus-circle"></i>
                    <span>Toevoegen</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
