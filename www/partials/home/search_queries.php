<?php
// Laatste zoekopdrachten ophalen
$mostSearchedSql = "SELECT query FROM search_queries ORDER BY search_date DESC LIMIT 10";
$mostSearchedSqlResult = $conn->query($mostSearchedSql);
?>

<section class="py-5">
    <div class="container">
        <h2 class="mb-4">Mensen zoeken ook naar</h2>
        <?php if ($mostSearchedSqlResult && $mostSearchedSqlResult->num_rows > 0): ?>
            <?php while($row = $mostSearchedSqlResult->fetch_assoc()): ?>
                <a href="/zoeken.php?q=<?= urlencode($row['query']) ?>" class="btn btn-warning me-2 mb-2">
                    <?= htmlspecialchars($row['query']) ?>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Geen zoekopdrachten gevonden.</p>
        <?php endif; ?>
    </div>
</section>
