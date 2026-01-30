<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: /pages/account/login");
    exit;
}

$result = $conn->query("
    SELECT id, title, author, created_at
    FROM blog_posts
    ORDER BY created_at DESC
");
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-journal-text accent-primary mr-3"></i>Blogbeheer
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Beheer en publiceer blogartikelen</p>
        </div>
        <a href="/admin/pages/blogs/add.php"
           class="accent-bg text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
            <i class="bi bi-plus-circle"></i>
            Nieuwe blog
        </a>
    </div>
</div>

<!-- BLOG LIST -->
<div class="card-glass p-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500/30 to-pink-500/30 flex items-center justify-center">
                <i class="bi bi-journal-text text-xl text-rose-400"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Alle Blogs</h2>
        </div>
    </div>
    
    <div class="space-y-4">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($blog = $result->fetch_assoc()): ?>
                <div class="card-glass p-5 flex justify-between items-center group hover:border-teal-500/30 transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500/20 to-cyan-500/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="bi bi-file-earmark-text text-xl text-teal-400"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg" style="color: var(--text-primary);"><?= htmlspecialchars($blog['title']) ?></h3>
                            <p class="text-sm flex items-center gap-2" style="color: var(--text-muted);">
                                <i class="bi bi-person"></i>
                                <?= htmlspecialchars($blog['author']) ?>
                                <span class="mx-1">•</span>
                                <i class="bi bi-calendar3"></i>
                                <?= date('d-m-Y', strtotime($blog['created_at'])) ?>
                            </p>
                        </div>
                    </div>

                    <a href="/admin/pages/blogs/edit.php?id=<?= $blog['id'] ?>"
                       class="accent-bg text-white px-5 py-2 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
                        <i class="bi bi-pencil"></i>
                        Bewerken
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="py-16 text-center">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-500/20 to-slate-600/20 flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-journal text-4xl" style="color: var(--text-muted);"></i>
                </div>
                <p class="text-lg font-semibold mb-1" style="color: var(--text-secondary);">Geen blogs gevonden</p>
                <p class="text-sm" style="color: var(--text-muted);">Begin met het schrijven van je eerste blog</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
