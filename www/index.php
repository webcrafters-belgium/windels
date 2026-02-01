<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require $_SERVER['DOCUMENT_ROOT'] . '/functions/helpers/shop_helpers.php';
require $_SERVER['DOCUMENT_ROOT'] . '/functions/helpers/vacation_helpers.php';

$pagetitle = "Windels Green & Deco Resin | Epoxy, Terrazzo & Kaarsen";
$seoDescription = "Ontdek bij Windels unieke epoxy, terrazzo en kaarsen die lokaal handgemaakt en duurzaam afgewerkt zijn.";
$seoImage = "https://windelsgreen-decoresin.com/images/layout/new_index/eco-resin-mini-tray-terrazzo-craft-workshop-edinburgh-portrait-big.png";
$products = getRandomProducts($conn, 20);

include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<!-- HERO -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">

            <!-- LINKERKANT HERO TEKST -->
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h1 class="display-5 fw-bold mb-4">
                    Epoxy, terrazzo & kaarsen <br>
                    met oog voor detail
                </h1>

                <p class="lead mb-4">
                    Ontdek unieke handgemaakte resincreaties, terrazzo trays en premium geurkaarsen.
                    Lokaal gemaakt, duurzaam verpakt, met aandacht voor afwerking.
                </p>

                <div class="d-flex gap-3 mb-4">
                    <a href="/pages/shop/" class="btn btn-primary btn-lg">Bekijk de winkel</a>
                    <a href="/pages/about/" class="btn btn-outline-primary btn-lg">Meer over ons</a>
                </div>

                <div class="d-flex gap-4 small text-muted">
                    <span>📦 Gratis afhalen mogelijk</span>
                    <span>🖐️ Handgemaakt & lokaal</span>
                </div>
            </div>

            <!-- RECHTS: HERO IMAGES MASONRY -->
            <div class="col-lg-6">
                <div class="hero-masonry">

                    <div class="mason-item h-big">
                        <img src="/images/layout/new_index/eco-resin-mini-tray-terrazzo-craft-workshop-edinburgh-portrait-big.png" alt="">
                    </div>

                    <div class="mason-item h-medium">
                        <img src="/images/layout/new_index/image-4.webp" alt="">
                    </div>

                    <div class="mason-item h-medium">
                        <img src="/images/categories/geur.webp" alt="Kaars, handgemaakte kaars door windels green & deco resin">
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


<!-- VAKANTIE BANNER -->
<section class="py-3">
    <div class="container-fluid">
        <?php getActiveVacationBanner($conn); ?>
    </div>
</section>

<!-- ADDED-TO-CART -->
<section id="addedToCart" class="added-to-cart"></section>

<!-- CATEGORIEËN HEADER -->
<section class="py-5 overflow-hidden">
    <div class="container-fluid home-categories-wrapper">

        <div class="home-section-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="home-section-title">Categorieën</h2>
                <p class="home-section-subtitle">Vind sneller wat je zoekt.</p>
            </div>
            <a href="/pages/shop/" class="home-section-link">Bekijk alle categorieën →</a>
        </div>

        <div class="row g-4">

            <?php
            $sql = "SELECT id, name, slug, image_path, icon_class FROM categories ORDER BY name ASC";
            $result = $conn->query($sql);

            while ($cat = $result->fetch_assoc()):

                $link  = "/pages/shop/category.php?category=" . htmlspecialchars($cat['slug']);
                $image = $cat['image_path'] ?: "/images/categories/default.webp";  // fallback
                $icon  = $cat['icon_class'] ?: "bi-tag";
                ?>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="<?= $link ?>" class="text-decoration-none cat-card-link">
                        <div class="cat-card shadow-sm rounded-4 overflow-hidden">

                            <div class="cat-card-image-wrapper">
                                <img src="<?= $image ?>" alt="<?= htmlspecialchars($cat['name']) ?>" class="cat-card-image">
                            </div>

                            <div class="cat-card-body text-center">
                                <h5 class="mb-1"><?= htmlspecialchars($cat['name']) ?></h5>
                                <p class="small text-muted mb-0">Bekijk producten</p>
                            </div>

                        </div>
                    </a>
                </div>

            <?php endwhile; ?>

        </div>

    </div>
</section>

<!-- PRODUCTGRID -->
<section class="py-5 product-grid-section">
    <div class="container-fluid">

        <div class="home-section-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="home-section-title">Uitgelichte producten</h2>
                <p class="home-section-subtitle">Een selectie handgemaakte creaties die populair zijn bij klanten.</p>
            </div>
            <a href="/pages/shop/" class="home-section-link">Bekijk alle producten →</a>
        </div>

        <div class="masonry-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-item">

                    <div class="product-image-wrapper">
                        <img loading="lazy"
                             src="<?= htmlspecialchars($product['product_image']); ?>"
                             alt="<?= htmlspecialchars($product['name'] ?? 'Product'); ?>">
                    </div>

                    <div class="product-item-content">
                        <div>
                            <h3 class="product-title mb-1">
                                <?= htmlspecialchars($product['name'] ?? 'Product'); ?>
                            </h3>

                            <div class="product-price fw-bold">
                                €<?= number_format((float)$product['price'], 2, ',', '.'); ?>
                            </div>
                        </div>

                        <?php if ($product['stock_status'] === 'instock' && (int)$product['stock_quantity'] > 0): ?>
                            <button class="btn btn-primary w-100 mt-3 add-to-cart"
                                    data-id="<?= (int)$product['id']; ?>"
                                    data-qty="1"
                                    data-price="<?= (int)round($product['price'] * 100); ?>"
                                    data-name="<?= htmlspecialchars($product['name']); ?>">
                                🛒 Toevoegen aan winkelwagen
                            </button>
                        <?php else: ?>
                            <span class="text-danger fw-bold mt-3">Uitverkocht</span>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>




<!-- LIFESTYLE SECTION / IMG_9552 -->
<section class="py-5">
    <div class="container-fluid">
        <div class="row align-items-center g-5">

            <div class="col-lg-6">
                <img src="/images/layout/new_index/IMG_9552.jpg"
                     alt="Lifestyle resin decor"
                     class="img-fluid rounded-4 shadow-sm">
            </div>

            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-3">Ambacht, sfeer & detail</h2>

                <p class="lead mb-4">
                    Elk stuk wordt met de hand gegoten, geschuurd en afgewerkt.
                    We gebruiken hoogwaardige resin, duurzame pigmenten en lokale materialen.
                </p>

                <ul class="list-unstyled fs-5 mb-4">
                    <li class="mb-2">• Handgemaakt in België</li>
                    <li class="mb-2">• Duurzame afwerking</li>
                    <li class="mb-2">• Unieke kleurencombinaties</li>
                </ul>

                <a href="/pages/about/" class="btn btn-dark btn-lg">Meer over het atelier</a>
            </div>

        </div>
    </div>
</section>


<!-- NIEUWSBRIEF -->
<section class="py-5">
    <div class="container-fluid">

        <div class="newsletter-bg rounded-5 p-5 shadow-sm">

            <div class="row align-items-center">
                <div class="col-lg-6 text-white">
                    <h2 class="display-5 fw-bold">Ontvang updates & nieuwe collecties</h2>
                    <p class="fs-5">Schrijf je in en krijg als eerste toegang tot limited editions en promoties.</p>
                </div>

                <div class="col-lg-6">
                    <form class="bg-offwhite p-4 rounded-4 shadow-sm">
                        <div class="mb-3">
                            <label class="form-label">E-mailadres</label>
                            <input type="email" class="form-control form-control-lg bg-undertext" placeholder="jouw@email.com" required>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="optIn" required>
                            <label class="form-check-label" for="optIn">
                                Ik schrijf me in voor de nieuwsbrief.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Inschrijven
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- APP PROMO -->
<section class="py-5">
    <div class="container-fluid">

        <div class="bg-undertext rounded-5 p-5 shadow-sm">
            <div class="row align-items-center">

                <div class="col-md-4 text-center">
                    <img src="/images/phone.png" alt="App" class="img-fluid" style="max-height: 350px;">
                </div>

                <div class="col-md-8">
                    <h2 class="fw-bold mb-4">Windels Green & Deco Resin App</h2>

                    <p class="fs-5">
                        Blijf op de hoogte van nieuwe resincollecties, exclusieve deals en snelle winkelervaring.
                    </p>

                    <form class="mt-4">
                        <div class="input-group input-group-lg mb-3">
                            <input type="email" class="bg-undertext form-control" placeholder="jouw e-mailadres" required>
                            <button class="btn btn-dark">Verzenden</button>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" id="news2" class="form-check-input" required>
                            <label for="news2" class="form-check-label">
                                Ik schrijf mij in voor de app-updates.
                            </label>
                        </div>
                    </form>

                </div>

            </div>
        </div>

    </div>
</section>


<!-- USP RIJ -->
<section class="py-5">
    <div class="container-fluid">
        <div class="row row-cols-1 row-cols-sm-3 row-cols-lg-5 g-4">

            <div class="col">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-truck fs-1 text-dark"></i>
                    <div>
                        <h5 class="fw-bold">Snelle levering</h5>
                        <p class="text-muted mb-0">Verzending binnen 48 uur & korting vanaf €50.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-shield-lock fs-1 text-dark"></i>
                    <div>
                        <h5 class="fw-bold">Veilig winkelen</h5>
                        <p class="text-muted mb-0">Betaal veilig via moderne protocollen.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-award fs-1 text-dark"></i>
                    <div>
                        <h5 class="fw-bold">Topkwaliteit</h5>
                        <p class="text-muted mb-0">Hoogwaardige resin & terrazzo materialen.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-cash-coin fs-1 text-dark"></i>
                    <div>
                        <h5 class="fw-bold">Altijd voordeel</h5>
                        <p class="text-muted mb-0">Regelmatig acties & bundelaanbiedingen.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-gift fs-1 text-dark"></i>
                    <div>
                        <h5 class="fw-bold">Unieke items</h5>
                        <p class="text-muted mb-0">Alles handgemaakt & uniek.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- BLOG POSTS -->
<?php
$blogSql = "
        SELECT id, title, content, author,
        DATE_FORMAT(created_at, '%d/%m/%Y') AS post_date
        FROM blog_posts
        ORDER BY created_at DESC
        LIMIT 3
    ";
$blogRes = $conn->query($blogSql);

$blogPosts = [];
if ($blogRes && $blogRes->num_rows > 0) {
    while ($row = $blogRes->fetch_assoc()) {
        $blogPosts[] = $row;
    }
}
?>

<section class="py-5 bg-undertext">
    <div class="container">
        <h2 class="mb-4 fw-bold">Laatste blogberichten</h2>

        <div class="row g-4">

            <?php if (!empty($blogPosts)): ?>
                <?php foreach ($blogPosts as $post): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 rounded-4  bg-offwhite">

                            <div class="card-body">
                                <h5 class="card-title mb-2"><?= htmlspecialchars($post['title']); ?></h5>

                                <p class="card-text text-muted">
                                    <?= mb_strimwidth(strip_tags($post['content']), 0, 140, '...'); ?>
                                </p>
                            </div>

                            <div class="card-footer bg-undertext border-0">
                                <small class="text-muted">
                                    Door <?= htmlspecialchars($post['author']); ?> – <?= htmlspecialchars($post['post_date']); ?>
                                </small><br>

                                <a href="/pages/blogs/post.php?id=<?= $post['id']; ?>"
                                   class="btn btn-primary btn-sm mt-3">
                                    Lees meer →
                                </a>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Er zijn nog geen blogberichten.</p>
            <?php endif; ?>

        </div>
    </div>
</section>


<!-- MOST SEARCHED -->
<section class="py-5">
    <div class="container-fluid">

        <h2 class="mb-4 fw-bold">Mensen zoeken ook naar</h2>

        <?php
        // queries ophalen
        $qres = $conn->query("
                SELECT query, COUNT(*) AS total
                FROM search_queries
                GROUP BY query
                ORDER BY total DESC
                LIMIT 5
            ");

        $queries = [];
        while ($r = $qres->fetch_assoc()) {
            $queries[] = $r['query'];
        }

        if (empty($queries)) {
            echo "<p class='text-muted'>Geen zoekopdrachten gevonden.</p>";
        } else {

            $types = str_repeat('s', count($queries));

            // producten zoeken via meerdere LIKE’s
            $stmt = $conn->prepare("
                    SELECT p.id, p.name, p.price,
                           i.image_path, i.webp_path
                    FROM products p
                    LEFT JOIN product_images i ON p.id = i.product_id
                    WHERE " . implode(" OR ", array_fill(0, count($queries), "p.name LIKE CONCAT('%', ?, '%')")) . "
                    GROUP BY p.id
                    LIMIT 40
                ");

            $stmt->bind_param($types, ...$queries);
            $stmt->execute();
            $msProducts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            if (empty($msProducts)) {
                echo "<p class='text-muted'>Geen bijpassende producten gevonden.</p>";
            } else { ?>

                <div id="mostSearchedWrapper">

                    <div id="mostSearchedContainer" class="row g-4">
                        <?php foreach ($msProducts as $p): ?>
                            <?php
                            $img = $p['webp_path'] ?: $p['image_path'] ?: '/images/placeholder.png';
                            ?>
                            <div class="col-12 col-sm-6 col-md-4 most-searched-item">

                                <div class="card h-100 text-center shadow-sm border-0 rounded-4">

                                    <div class="ratio ratio-1x1">
                                        <img src="<?= htmlspecialchars($img); ?>"
                                             class="card-img-top object-fit-cover rounded-top-4"
                                             alt="<?= htmlspecialchars($p['name']); ?>">
                                    </div>

                                    <div class="card-body bg-undertext">
                                        <h5 class="mb-2"><?= htmlspecialchars($p['name']); ?></h5>

                                        <?php if (!is_null($p['price'])): ?>
                                            <p class="fw-bold mb-0">
                                                € <?= number_format((float)$p['price'], 2, ',', '.'); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">

                        <button type="button"
                                class="btn btn-outline-primary"
                                id="mostSearchedPrev">
                            « Vorige
                        </button>

                        <span id="mostSearchedIndicator" class="fw-bold"></span>

                        <button type="button"
                                class="btn btn-outline-primary"
                                id="mostSearchedNext">
                            Volgende »
                        </button>

                    </div>

                </div>

            <?php }
        }
        ?>
    </div>
</section>

<!-- MOST SEARCHED PAGINATION SCRIPT -->
<script>
    document.addEventListener("DOMContentLoaded", function () {

        const container = document.getElementById("mostSearchedContainer");
        if (!container) return;

        const items = [...container.querySelectorAll(".most-searched-item")];
        if (items.length === 0) return;

        const prevBtn = document.getElementById("mostSearchedPrev");
        const nextBtn = document.getElementById("mostSearchedNext");
        const indicatorEl = document.getElementById("mostSearchedIndicator");

        let currentPage = 0;
        let itemsPerPage = getItemsPerPage();
        let totalPages = Math.ceil(items.length / itemsPerPage);

        function getItemsPerPage() {
            const w = window.innerWidth;
            if (w < 576) return 1;
            if (w < 992) return 2;
            return 3;
        }

        function renderPage() {

            const newItemsPerPage = getItemsPerPage();
            if (newItemsPerPage !== itemsPerPage) {
                itemsPerPage = newItemsPerPage;
                totalPages = Math.ceil(items.length / itemsPerPage);
                if (currentPage >= totalPages) currentPage = totalPages - 1;
            }

            items.forEach((item, index) => {
                const pageIndex = Math.floor(index / itemsPerPage);
                item.style.display = (pageIndex === currentPage) ? "" : "none";
            });

            indicatorEl.textContent = (currentPage + 1) + " / " + totalPages;

            prevBtn.disabled = currentPage === 0;
            nextBtn.disabled = currentPage >= totalPages - 1;
        }

        prevBtn.addEventListener("click", function () {
            if (currentPage > 0) {
                currentPage--;
                renderPage();
            }
        });

        nextBtn.addEventListener("click", function () {
            if (currentPage < totalPages - 1) {
                currentPage++;
                renderPage();
            }
        });

        window.addEventListener("resize", function () {
            renderPage();
        });

        renderPage();
    });
</script>


<!-- ADDED TO CART TOAST -->
<style>
    #addedToCartToast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #198754;
        color: white;
        padding: 15px 25px;
        border-radius: 12px;
        display: none;
        z-index: 9999;
        font-weight: 600;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }
</style>

<div id="addedToCartToast">Toegevoegd aan winkelwagen</div>

<script>
    document.addEventListener("click", function (e) {

        if (!e.target.matches(".add-to-cart")) return;

        const toast = document.getElementById("addedToCartToast");

        toast.style.display = "block";
        toast.style.opacity = "1";

        setTimeout(() => {
            toast.style.opacity = "0";
            setTimeout(() => toast.style.display = "none", 400);
        }, 1800);
    });
</script>


<!-- ANNOUNCEMENT SYSTEM -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const saved = localStorage.getItem("hideAnnouncement");
        const bar = document.getElementById("announcementBar");
        if (!bar) return;

        if (saved === "true") {
            bar.style.display = "none";
            return;
        }

        const closeBtn = document.getElementById("announcementClose");
        if (!closeBtn) return;

        closeBtn.addEventListener("click", function () {
            localStorage.setItem("hideAnnouncement", "true");
            bar.style.opacity = "0";

            setTimeout(() => {
                bar.style.display = "none";
            }, 350);
        });
    });
</script>


<!-- IMAGE LAZY-BOOST -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const imgs = document.querySelectorAll("img[loading='lazy']");

        imgs.forEach(img => {
            if (img.complete) return;
            img.style.filter = "blur(3px)";
            img.style.transition = "filter 0.4s ease";

            img.onload = () => img.style.filter = "blur(0)";
        });
    });
</script>

<?php

include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
