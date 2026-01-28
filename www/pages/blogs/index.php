<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc'; // Zorg voor de juiste databaseverbinding

// Als blog_id is ingesteld, haal dan enkel dat specifieke blog op
if (isset($_GET['blog_id'])) {
    $blog_id = $_GET['blog_id'];
    // Zorg ervoor dat het ID een geldig getal is (beveiliging tegen SQL-injectie)
    $blog_id = intval($blog_id);

    // Query om een specifiek blog op te halen
    $selectQuery = "
        SELECT * FROM `blog_posts` WHERE `id` = $blog_id;
    ";


} else {
    // Als blog_id niet is ingesteld, haal dan alle blogs op
    $selectQuery = "
        SELECT * FROM `blog_posts` ORDER BY created_at DESC;
    ";
}

$result = $conn->query($selectQuery);

// Controleer of de query succesvol was
if ($result === false) {
    die("Er is een fout opgetreden bij het ophalen van de blogberichten.");
}

$blogs = [];
while ($row = $result->fetch_assoc()) {
    $blogs[] = $row;
}

?>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>

<div class="container my-5">

    <?php if (count($blogs) > 0): ?>
        <div class="blogs-list">
            <?php if (isset($blog_id)): ?>
                <!-- Toon alleen het geselecteerde blog -->
                <?php $blog = $blogs[0]; ?>
                <div class="blog-item">
                    <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
                    <p><strong>Geplaatst door: </strong><?php echo htmlspecialchars($blog['author']); ?> | <strong>Datum: </strong><?php echo date('d-m-Y', strtotime($blog['created_at'])); ?></p>
                    <?php if (!empty($blog['image'])): ?>
                        <img src="<?= htmlspecialchars($blog['image']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>" class="img-fluid rounded mb-4">
                    <?php endif; ?>
                    <div class="blog-content">
                        <?= $blog['content'] ?>
                    </div>

                    <div class="blog-content">
                        <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <!-- Toon de lijst met blogs -->
                <?php foreach ($blogs as $blog): ?>
                    <div class="blog-item">
                        <h2>
                            <a href="/pages/blogs/post.php?blog_id=<?php echo $blog['id']; ?>"><?php echo htmlspecialchars($blog['title']); ?></a>
                        </h2>
                        <?php if (!empty($blog['image'])): ?>
                            <img src="<?= htmlspecialchars($blog['image']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>" class="img-thumbnail mb-2" style="max-height:150px;">
                        <?php endif; ?>

                        <p><strong>Geplaatst door: </strong><?php echo htmlspecialchars($blog['author']); ?> | <strong>Datum: </strong><?php echo date('d-m-Y', strtotime($blog['created_at'])); ?></p>
                        <p><?php echo substr(strip_tags($blog['content']), 0, 150); ?>...</p>
                        <a href="/pages/blogs/post.php?blog_id=<?php echo $blog['id']; ?>" class="btn btn-primary">Lees meer</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>Er zijn geen blogs beschikbaar.</p>
    <?php endif; ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>

