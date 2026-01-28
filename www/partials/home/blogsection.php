<?php
// Blogposts ophalen
$getPostsSql = "SELECT id, title, content, author, DATE_FORMAT(created_at, '%d %m %Y') AS post_date 
                FROM blog_posts 
                ORDER BY created_at DESC 
                LIMIT 3";
$getPostsResult = $conn->query($getPostsSql);
$posts = [];
if ($getPostsResult && $getPostsResult->num_rows > 0) {
    while($row = $getPostsResult->fetch_assoc()) {
        $posts[] = $row;
    }
}
?>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4">Laatste blogberichten</h2>
        <div class="row">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                                <p class="card-text">
                                    <?= mb_strimwidth(strip_tags($post['content']), 0, 150, '...') ?>
                                </p>
                            </div>
                            <div class="card-footer bg-white">
                                <small class="text-muted">Door <?= htmlspecialchars($post['author']) ?> op <?= htmlspecialchars($post['post_date']) ?></small><br>
                                <a href="/pages/blogs/post.php?id=<?= $post['id'] ?>" class="btn btn-primary btn-sm mt-2">Lees meer</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Er zijn nog geen blogberichten geplaatst.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
