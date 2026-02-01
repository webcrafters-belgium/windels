<?php
require_once __DIR__ . '/../../includes/header.php';

$result = $conn->query("
    SELECT id, title, author, created_at
    FROM blog_posts
    ORDER BY created_at DESC
");
?>

<!-- Main Content -->
<div class="max-w-6xl mx-auto animate-fadeInUp">
    
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold glow-text">Blogbeheer</h1>
            <p class="text-sm mt-1" style="color: var(--text-muted);">Beheer alle blogposts</p>
        </div>
        <a href="/admin/pages/blogs/add.php" 
           class="accent-bg px-5 py-3 rounded-xl font-semibold text-white flex items-center space-x-2"
           data-testid="add-blog-btn">
            <i class="bi bi-plus-circle"></i>
            <span>Nieuwe blog</span>
        </a>
    </div>

    <!-- Blog List -->
    <div class="card-glass p-6">
        <div class="space-y-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($blog = $result->fetch_assoc()): ?>
                    <div class="glass p-5 rounded-xl flex justify-between items-center group hover:border-teal-500/30 transition-all" data-testid="blog-item-<?= $blog['id'] ?>">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500/20 to-pink-500/20 flex items-center justify-center">
                                <i class="bi bi-journal-text text-xl text-rose-400"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg"><?= htmlspecialchars($blog['title']) ?></h3>
                                <p class="text-sm" style="color: var(--text-muted);">
                                    <i class="bi bi-person-fill mr-1"></i><?= htmlspecialchars($blog['author']) ?>
                                    <span class="mx-2">•</span>
                                    <i class="bi bi-calendar3 mr-1"></i><?= date('d-m-Y', strtotime($blog['created_at'])) ?>
                                </p>
                            </div>
                        </div>

                        <a href="/admin/pages/blogs/edit.php?id=<?= $blog['id'] ?>"
                           class="px-4 py-2 rounded-lg bg-teal-500/20 text-teal-400 hover:bg-teal-500/30 transition flex items-center space-x-2"
                           data-testid="edit-blog-<?= $blog['id'] ?>">
                            <i class="bi bi-pencil"></i>
                            <span>Bewerken</span>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center py-12" style="color: var(--text-muted);">
                    <i class="bi bi-journal-x text-5xl mb-4 block opacity-50"></i>
                    <p class="text-lg">Geen blogposts gevonden</p>
                    <p class="text-sm mt-2">Klik op "Nieuwe blog" om te beginnen</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
