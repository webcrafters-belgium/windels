<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include_once $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php';

session_start();

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


<div class="p-8 text-gray-200">
    <h1 class="text-2xl font-bold mb-6">Blogbeheer</h1>

    <a href="/admin/pages/blogs/add.php"
       class="inline-block mb-4 px-4 py-2 bg-green-600 rounded">
        + Nieuwe blog
    </a>

    <div class="space-y-4">
        <?php while ($blog = $result->fetch_assoc()): ?>
            <div class="p-4 bg-[#1a1a1a] border border-gray-800 rounded flex justify-between items-center">
                <div>
                    <h3 class="font-semibold"><?= htmlspecialchars($blog['title']) ?></h3>
                    <p class="text-sm text-gray-400">
                        <?= htmlspecialchars($blog['author']) ?> •
                        <?= date('d-m-Y', strtotime($blog['created_at'])) ?>
                    </p>
                </div>

                <a href="/admin/pages/blogs/edit.php?id=<?= $blog['id'] ?>"
                   class="text-green-500 hover:underline">
                    Bewerken
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'; ?>
