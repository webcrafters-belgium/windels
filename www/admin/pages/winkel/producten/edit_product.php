<?php
error_reporting(E_ALL ^ (E_NOTICE ^ E_WARNING));
ini_set('display_errors', 1);

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$product_id) {
    echo "<div class='alert alert-danger'>Geen geldig product geselecteerd.</div>";
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

$sku = $product['sku'];

if (!$product) {
    echo "<div class='alert alert-danger'>Product niet gevonden.</div>";
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php';
    exit;
}

$product_sku = $product['sku'];

// Enkel de eerste (hoofd)afbeelding ophalen op basis van SKU
$imageQuery = $conn->prepare("SELECT image_path FROM product_images WHERE product_id = ? LIMIT 1");
$imageQuery->bind_param("s", $product_id);
$imageQuery->execute();
$imageResult = $imageQuery->get_result();
$productImage = $imageResult->fetch_assoc();

$subResult = $conn->query("SELECT s.id, s.name FROM subcategories s INNER JOIN product_subcategories ps ON ps.subcategory_id = s.id WHERE ps.product_id = $product_id LIMIT 1");
$subcat = $subResult->fetch_assoc();
$subcategory_id = $subcat['id'] ?? '';

$catResult = $conn->query("SELECT c.id, c.name FROM categories c INNER JOIN product_categories pc ON pc.category_id = c.id WHERE pc.product_id = $product_id LIMIT 1");
$cat = $catResult->fetch_assoc();
$category_id = $cat['id'] ?? '';

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
$subcategories = $conn->query("SELECT id, name, parent_id FROM subcategories ORDER BY name ASC");
?>

<div id="updateSuccessModal" style="display:none; position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 15px; border-radius: 5px; z-index: 9999;">
    ✅ Product succesvol bijgewerkt.
</div>


<script>
    function getUrlParam(name) {
        const url = new URL(window.location.href);
        return url.searchParams.get(name);
    }

    document.addEventListener("DOMContentLoaded", function () {
        if (getUrlParam("updated") === "1") {
            const modal = document.getElementById("updateSuccessModal");
            if (modal) {
                modal.style.display = "block";
                // Automatisch verbergen na 3 seconden
                setTimeout(() => {
                    modal.style.display = "none";
                }, 3000);
            }
        }
    });
</script>


<script src="https://cdn.tiny.cloud/1/xdjt6vqjx1h0oh9f9f084xr4z88g2dppg86b1c9atd4dvhfn/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        tinymce.init({
            selector: '#description',
            height: 300,
            menubar: false,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help'
        });
    });
</script>

<div class="container mt-4">
    <h2>Product bewerken</h2>
    <form action="/admin/functions/shop/products/update_product.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">

        <div class="mb-3">
            <label for="sku" class="form-label">SKU</label>
            <input type="text" class="form-control text-dark" id="sku" name="sku" value="<?= $sku ?>" readonly>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Naam</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Categorie</label>
            <select class="form-select" name="category" id="category">
                <option value="">-- Kies een categorie --</option>
                <?php while ($row = $categories->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>" <?= ($category_id == $row['id']) ? 'selected' : '' ?>><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="sub_category" class="form-label">Subcategorie</label>
            <select class="form-select" name="sub_category" id="sub_category">
                <option value="">-- Kies een subcategorie --</option>
                <?php while ($row = $subcategories->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>" <?= ($subcategory_id == $row['id']) ? 'selected' : '' ?>><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Prijs</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= $product['price'] ?>">
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Voorraad</label>
            <input type="number" class="form-control" id="stock" name="stock" value="<?= $product['stock_quantity'] ?>">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Omschrijving</label>
            <textarea class="form-control" name="description" id="description" rows="5"><?= $product['description'] ?></textarea>
        </div>

        <div class="mb-3">
            <label for="image_file" class="form-label">Afbeelding uploaden</label><br>
            <?php if (!empty($productImage['image_url'])): ?>
                <img src="<?= htmlspecialchars($productImage['image_url']) ?>" class="img-thumbnail me-2 mb-2" style="width:100px;" alt="Huidige afbeelding">
            <?php endif; ?>
            <input type="file" class="form-control mt-2" name="image_file" id="image_file" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Opslaan</button>
        <a href="index.php" class="btn btn-secondary">Annuleren</a>
    </form>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
