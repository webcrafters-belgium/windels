<?php


// Query om de laatste drie blogberichten op te halen
$sql = "SELECT id, title, content, author, DATE_FORMAT(created_at, '%Y %m %d') AS post_date FROM blog_posts ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($sql);

// Controleren of er resultaten zijn
if ($result->num_rows > 0) {
    $posts = [];
    while($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}
?>

<section id="latest-blog" class="py-5">
    <div class="container-fluid">
        <div class="row">
            <div class="section-header d-flex align-items-center justify-content-between my-5">
                <h2 class="section-title">Onze Recentste Blogs</h2>
                <div class="btn-wrap align-right">
                    <a href="/pages/blogs/" class="d-flex align-items-center nav-link">Lees Alle Artikelen <svg width="24" height="24"><use xlink:href="#arrow-right"></use></svg></a>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            // Controleer of er blogberichten zijn en toon ze
            if (isset($posts) && count($posts) > 0) {
                foreach ($posts as $post) {
                    ?>
                    <div class="col-md-4">
                        <article class="post-item card border-0 shadow-sm p-3">
                            <div class="image-holder zoom-effect">
                                <a href="/pages/blogs/?blog_id=<?php echo $post['id']; ?>">
                                    <img src="/images/post-thumb-1.jpg" alt="post" class="card-img-top">
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="post-meta d-flex text-uppercase gap-3 my-2 align-items-center">
                                    <div class="meta-date"><svg width="16" height="16"><use xlink:href="#calendar"></use></svg><?php echo $post['post_date']; ?></div>
                                    <div class="meta-categories"><svg width="16" height="16"><use xlink:href="#category"></use></svg>tips & tricks</div>
                                </div>
                                <div class="post-header">
                                    <h3 class="post-title">
                                        <a href="/pages/blogs/?blog_id=<?php echo $post['id']; ?>" class="text-decoration-none"><?php echo htmlspecialchars($post['title']); ?></a>
                                    </h3>
                                    <p><?php echo substr(htmlspecialchars($post['content']), 0, 150) . '...'; ?></p>
                                </div>
                            </div>
                        </article>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</section>

