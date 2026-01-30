<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';
?>

<div class="admin-promo">
    <h1>Nieuwe Promo Toevoegen</h1>
    <form action="save.php" method="post" enctype="multipart/form-data">
        <label>Titel:<input type="text" name="title" required></label>
        <label>Subtitel:<input type="text" name="subtitle"></label>
        <label>Korting (%):<input type="number" name="discount_percentage" step="0.1" required></label>

        <div>
            <label>Type promo:</label>
            <input type="radio" name="promo_type" value="product" checked onclick="switchType('product')"> Product
            <input type="radio" name="promo_type" value="category" onclick="switchType('category')"> Categorie
            <input type="radio" name="promo_type" value="subcategory" onclick="switchType('subcategory')"> Subcategorie
        </div>

        <div id="productField">
            <label>Product SKU: <input type="text" name="product_sku"></label>
        </div>

        <div id="categoryField" style="display:none;">
            <label>Categorie:
                <select name="category_id" id="categorySelect">
                    <option value="">-- Kies categorie --</option>
                    <?php
                    $res = $conn->query("SELECT id, name FROM categories ORDER BY name");
                    while ($row = $res->fetch_assoc()) {
                        echo '<option value="'.$row['id'].'">'.htmlspecialchars($row['name']).'</option>';
                    }
                    ?>
                </select>
            </label>
        </div>

        <div id="subcategoryField" style="display:none;">
            <label>Subcategorie:
                <select name="subcategory_id" id="subcategorySelect">
                    <option value="">-- Kies subcategorie --</option>
                </select>
            </label>
        </div>

        <label>Startdatum: <input type="datetime-local" name="start_date"></label>
        <label>Einddatum: <input type="datetime-local" name="end_date"></label>
        <label>Afbeelding (optioneel): <input type="file" name="image"></label>

        <button type="submit">Promo opslaan</button>
    </form>
</div>

<script>
    function switchType(type) {
        document.getElementById('productField').style.display = type === 'product' ? 'block' : 'none';
        document.getElementById('categoryField').style.display = type === 'category' ? 'block' : 'none';
        document.getElementById('subcategoryField').style.display = type === 'subcategory' ? 'block' : 'none';
    }

    document.getElementById('categorySelect').addEventListener('change', function () {
        fetch('/API/shop/get_subcategories.php?category_id=' + this.value)
            .then(res => res.json())
            .then(data => {
                let subcat = document.getElementById('subcategorySelect');
                subcat.innerHTML = '<option value="">-- Kies subcategorie --</option>';
                data.forEach(sub => {
                    subcat.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
                });
            });
    });
</script>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
