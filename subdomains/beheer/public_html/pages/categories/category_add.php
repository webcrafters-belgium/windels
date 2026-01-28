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
        header("Location: /pages/categories/categories.php");
        exit();
    } else {
        $error = "Vul alle velden in!";
    }
}

// Uitgebreide lijst met Bootstrap-icons
$icons = [
    "bi-gift-fill", "bi-fire", "bi-droplet-fill", "bi-bricks",
    "bi-basket-fill", "bi-rulers", "bi-grid", "bi-boxes", "bi-tree",
    "bi-lightbulb-fill", "bi-palette-fill", "bi-paint-bucket", "bi-brush",
    "bi-tools", "bi-wrench", "bi-hammer", "bi-shop", "bi-tag-fill",
    "bi-card-list", "bi-credit-card-fill", "bi-bag-fill", "bi-basket3-fill",
    "bi-flower1", "bi-flower2", "bi-flower3", "bi-sun", "bi-cloud-fill",
    "bi-moon-stars-fill", "bi-star-fill", "bi-heart-fill", "bi-hand-thumbs-up-fill",
    "bi-box-seam", "bi-box-arrow-up", "bi-cart-fill", "bi-cart-check-fill",
    "bi-check2-circle", "bi-shield-lock-fill", "bi-lock-fill", "bi-unlock-fill",
    "bi-house-fill", "bi-house-door-fill", "bi-building", "bi-tree-fill",
    "bi-recycle", "bi-gem", "bi-hand-index-thumb-fill",
    "bi-pencil-fill", "bi-pen-fill", "bi-cup-fill", "bi-globe2", "bi-geo-alt-fill",
    "bi-shop-window", "bi-handbag-fill", "bi-hand-thumbs-up", "bi-cash-coin",
    "bi-receipt", "bi-clipboard-check-fill", "bi-trophy-fill"
];


?>

<section class="py-5 container">
    <div class="container-fluid">
        <h1 class="mb-4">Nieuwe Categorie Toevoegen</h1>

        <?php if (!empty($error)): ?>
            <p class="text-danger"><?= $error; ?></p>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
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
                        <label>Icon Class: <small>Selecteer een icon uit de lijst</small></label>
                        <input type="text" name="icon_class" id="icon_class" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Categorie Toevoegen</button>
                </form>
            </div>

            <!-- Icons weergave rechts -->
            <div class="col-md-6">
                <h5>Kies een icoon:</h5>
                <div class="icon-grid">
                    <?php foreach ($icons as $icon): ?>
                        <button type="button" class="icon-btn" onclick="selectIcon('<?= $icon; ?>')">
                            <i class="bi <?= $icon; ?>"></i>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>

<style>
    .icon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
        gap: 10px;
        margin-top: 10px;
    }

    .icon-btn {
        border: 1px solid #ddd;
        background: #fff;
        cursor: pointer;
        padding: 10px;
        text-align: center;
        font-size: 20px;
        width: 50px;
        height: 50px;
    }

    .icon-btn:hover {
        background: #f8f9fa;
    }
</style>

<script>
    function selectIcon(iconClass) {
        document.getElementById("icon_class").value = iconClass;
    }
</script>
