<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

// Haal alle categorieën op uit de database
$sql = "SELECT * FROM categories ORDER BY id ASC";
$result = $conn->query($sql);
$categories = $result->fetch_all(MYSQLI_ASSOC);
?>

<section class="py-5">
    <div class="container">
        <h1 class="mb-4">Categorieën Beheren</h1>
        <a href="/pages/categories/category_add.php" class="btn btn-success mb-3">+ Nieuwe Categorie</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Slug</th>
                    <th>Icon</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= $category['id']; ?></td>
                        <td><?= htmlspecialchars($category['name']); ?></td>
                        <td><?= htmlspecialchars($category['slug']); ?></td>
                        <td><i class="bi <?= htmlspecialchars($category['icon_class']); ?>"></i></td>
                        <td>
                            <a href="/pages/categories/category_edit.php?id=<?= $category['id']; ?>" class="btn btn-primary btn-sm">Bewerken</a>
                            <a href="/functions/categories/category_delete.php?id=<?= $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Weet je zeker dat je deze categorie wilt verwijderen?')">Verwijderen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
