<?php
// /partials/shop/sidebar_filters.php
?>

<!-- Sidebar Filters -->
<!-- Actieve filters tonen -->
<?php if (!empty($_GET)): ?>
    <div class="mb-3">
        <h6>Actieve filters:</h6>
        <ul class="list-inline small">
            <?php foreach ($_GET as $key => $value): ?>
                <?php if ($key === 'search' || $key === 'min_price' || $key === 'max_price' || $key === 'sort') continue; ?>
                <li class="list-inline-item badge bg-primary text-white">
                    <?= ucfirst($key) ?>: <?= htmlspecialchars($value) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="/pages/shop/" class="btn btn-sm btn-outline-secondary">❌ Verwijder filters</a>
    </div>
<?php endif; ?>


<!-- ✅ Categorie & Subcategorie -->
<div class="border rounded p-3 bg-offwhite shadow-sm mb-4">
    <h4 class="mb-3">Categorieën</h4>
    <div class="accordion" id="categoryAccordion">
        <?php while ($category = $categoryResult->fetch_assoc()): ?>
            <?php
            $isActive = (isset($_GET['category']) && $_GET['category'] === $category['slug']);
            $collapseClass = $isActive ? 'show' : '';
            $activeCat = $isActive ? 'fw-bold text-primary' : '';
            ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading<?= $category['id'] ?>">
                    <button class="accordion-button <?= $collapseClass ?> <?= $activeCat ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $category['id'] ?>">
                        <?= htmlspecialchars($category['name']); ?>
                    </button>
                </h2>
                <div id="collapse<?= $category['id'] ?>" class="accordion-collapse collapse <?= $collapseClass ?>" data-bs-parent="#categoryAccordion">
                    <div class="accordion-body">
                        <?php if (!empty($subcategories[$category['id']])): ?>
                            <form method="get">
                                <input type="hidden" name="category" value="<?= $category['slug'] ?>">
                                <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                                <input type="hidden" name="min_price" value="<?= $minPrice ?>">
                                <input type="hidden" name="max_price" value="<?= $maxPrice ?>">
                                <input type="hidden" name="sort" value="<?= $sort ?>">

                                <?php foreach ($subcategories[$category['id']] as $sub): ?>
                                    <?php
                                    $isChecked = isset($_GET['sub']) && $_GET['sub'] === $sub['slug'];
                                    ?>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="radio" name="sub" value="<?= $sub['slug'] ?>" id="sub_<?= $sub['id'] ?>" <?= $isChecked ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="sub_<?= $sub['id'] ?>">
                                            <?= htmlspecialchars($sub['name']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                                <button type="submit" class="btn btn-sm btn-outline-primary mt-2">Toepassen</button>
                            </form>
                        <?php else: ?>
                            <p class="text-muted small mb-0">Geen subcategorieën</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- 🔢 Prijsfilter -->
<div class="border rounded p-3 bg-offwhite shadow-sm mb-4">
    <h5>Filter op prijs</h5>
    <form method="get" action="">
        <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm); ?>">
        <div class="mb-2">
            <input type="number" name="min_price" class="form-control mb-1" placeholder="Min €" value="<?= $minPrice; ?>">
            <input type="number" name="max_price" class="form-control" placeholder="Max €" value="<?= $maxPrice; ?>">
        </div>
        <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
    </form>
</div>

<!-- 🧠 Zoekterm behouden -->
<?php if (!empty($searchTerm)): ?>
    <div class="alert alert-info p-2 small mb-3">
        🔍 Zoekterm: <strong><?= htmlspecialchars($searchTerm) ?></strong>
    </div>
<?php endif; ?>

<!-- 🔁 Sorteerveld -->
<div class="border rounded p-3 bg-offwhite shadow-sm">
    <h5>Sorteren</h5>
    <form method="get" action="">
        <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm); ?>">
        <input type="hidden" name="min_price" value="<?= $minPrice; ?>">
        <input type="hidden" name="max_price" value="<?= $maxPrice; ?>">
        <input type="hidden" name="category" value="<?= $_GET['category'] ?? '' ?>">
        <input type="hidden" name="sub" value="<?= $_GET['sub'] ?? '' ?>">

        <select name="sort" class="form-select mb-2" onchange="this.form.submit()">
            <option value="default" <?= $sort === 'default' ? 'selected' : '' ?>>Standaard</option>
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Prijs: laag naar hoog</option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Prijs: hoog naar laag</option>
            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Nieuwste producten</option>
        </select>
    </form>
</div>