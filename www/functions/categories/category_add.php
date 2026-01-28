 <?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    $icon = trim($_POST['icon_class']);

    if (!empty($name) && !empty($slug) && !empty($icon)) {
        $sql = "INSERT INTO categories (name, slug, icon_class) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $slug, $icon);
        $stmt->execute();
        header("Location: /admin/pages/categories.php");
        exit();
    } else {
        $error = "Vul alle velden in!";
    }
}
?>

<section class="py-5">
    <div class="container">
        <h1 class="mb-4">Nieuwe Categorie Toevoegen</h1>

        <?php if (!empty($error)): ?>
            <p class="text-danger"><?= $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Naam:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Slug (URL-vriendelijk):</label>
                <input type="text" name="slug" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Icon Class (bijv. `bi-box`):</label>
                <input type="text" name="icon_class" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Categorie Toevoegen</button>
        </form>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
