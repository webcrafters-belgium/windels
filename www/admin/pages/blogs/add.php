<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-plus-circle accent-primary mr-3"></i>Nieuwe blogpost
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Schrijf een nieuwe blogpost</p>
        </div>
        <a href="/admin/pages/blogs/index.php" class="glass px-5 py-3 rounded-xl font-semibold hover:bg-white/10 transition flex items-center gap-2" style="color: var(--text-secondary);">
            <i class="bi bi-arrow-left"></i>Terug
        </a>
    </div>
</div>

<!-- FORM -->
<div class="card-glass p-8">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/partials/forms/blog_add_form.php'; ?>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
