<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// -------------------------------------------------
// Blog ophalen
// -------------------------------------------------
if (!isset($_GET['blog_id'])) {
    header("Location: /pages/blogs/");
    exit;
}

$blog_id = (int)$_GET['blog_id'];

$stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("HTTP/1.1 404 Not Found");
    include $_SERVER['DOCUMENT_ROOT'] . '/pages/errors/404.php';
    exit;
}

$blog = $result->fetch_assoc();

// -------------------------------------------------
// OG + share data
// -------------------------------------------------
$canonicalUrl = "https://windelsgreen-decoresin.com/pages/blogs/post.php?blog_id=" . $blog_id;
$shareUrl    = urlencode($canonicalUrl);

$ogTitle       = $blog['title'];
$ogDescription = substr(strip_tags($blog['content']), 0, 160);
$ogImage       = $blog['image'] ?? '';
$ogType        = 'article';

// -------------------------------------------------
// Header
// -------------------------------------------------
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<div class="container my-5">
    <article class="blog-post">

        <header class="mb-4">
            <h1 class="mb-2"><?= htmlspecialchars($blog['title']); ?></h1>

            <div class="text-muted small">
                Geplaatst door <strong><?= htmlspecialchars($blog['author']); ?></strong>
                • <?= date('d-m-Y', strtotime($blog['created_at'])); ?>
            </div>
        </header>

        <?php if (!empty($blog['image'])): ?>
            <figure class="mb-4">
                <img
                        src="<?= htmlspecialchars($blog['image']); ?>"
                        alt="<?= htmlspecialchars($blog['title']); ?>"
                        class="img-fluid rounded w-100"
                >
            </figure>
        <?php endif; ?>

        <section class="blog-content mb-5">
            <?= $blog['content']; ?>
        </section>

        <footer class="blog-footer d-flex flex-wrap gap-3 align-items-center">
            <a
                    href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-outline-primary"
            >
                📤 Deel op Facebook
            </a>

            <a href="/pages/blogs/" class="btn btn-link">
                ← Terug naar blog
            </a>
        </footer>

    </article>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
