<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . '/header2.php';
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/functions/helpers/shop_helpers.php';
include $_SERVER['DOCUMENT_ROOT'] . '/functions/helpers/vacation_helpers.php';

// Producten
$products = getRandomProducts($conn, 20);

// Vakantiebanner
getActiveVacationBanner($conn);
?>

<!-- HERO SECTION -->
<section class="relative w-full h-[380px] md:h-[460px] bg-cover bg-center rounded-b-[40px] overflow-hidden"
         style="background-image:url('/images/background-pattern.jpg')">

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 h-full flex flex-col justify-center">
        <h1 class="text-white text-4xl md:text-6xl font-extrabold drop-shadow">
            Windels Green & Deco Resin
        </h1>
        <p class="text-white/90 text-lg md:text-2xl mt-4 max-w-xl">
            Kwaliteit, vakmanschap en passie voor interieur en decoratie.
        </p>

        <div class="mt-6">
            <a href="/pages/shop/"
               class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-xl shadow-lg shadow-green-600/30 transition">
                Shop nu →
            </a>
        </div>
    </div>
</section>


<!-- ADD TO CART ALERT -->
<div id="addedToCart"
     class="hidden fixed top-5 right-5 bg-green-600 text-white px-6 py-4 rounded-xl shadow-xl z-50 text-sm">
</div>


<!-- CATEGORIEËN -->
<section class="py-16 bg-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900">Categorieën</h2>

            <div class="flex items-center gap-4 mt-4 md:mt-0">
                <a href="/pages/shop/categories"
                   class="text-gray-600 hover:text-green-600 font-medium">
                    Alle categorieën →
                </a>

                <button class="category-carousel-prev w-10 h-10 bg-offwhite rounded-xl border shadow-sm hover:bg-green-500 hover:text-white transition flex justify-center items-center">❮</button>
                <button class="category-carousel-next w-10 h-10 bg-offwhite rounded-xl border shadow-sm hover:bg-green-500 hover:text-white transition flex justify-center items-center">❯</button>
            </div>
        </div>

        <div class="swiper category-carousel" id="slider_001">
            <div class="swiper-wrapper">

                <?php
                $sql = "SELECT * FROM categories";
                $result = $conn->query($sql);
                while ($category = $result->fetch_assoc()):
                    ?>
                    <a href="/pages/shop/category.php?category=<?= htmlspecialchars($category['slug']); ?>"
                       class="swiper-slide block group">
                        <div class="bg-offwhite rounded-2xl shadow-sm border border-gray-100 p-10 text-center
                                    transition-all duration-300 group-hover:-translate-y-3 group-hover:shadow-xl">

                            <div class="text-5xl text-green-500 mb-6">
                                <i class="<?= $category['icon_class'] ?>"></i>
                            </div>

                            <h3 class="text-xl font-semibold text-gray-800">
                                <?= htmlspecialchars($category['name']); ?>
                            </h3>
                        </div>
                    </a>
                <?php endwhile; ?>

            </div>
        </div>
    </div>
</section>


<!-- PRODUCT CAROUSEL -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900">Nieuwe Producten</h2>

            <div class="flex items-center gap-4 mt-4 md:mt-0">
                <a href="/pages/shop/"
                   class="text-gray-600 hover:text-green-600 font-medium">Bekijk alles →</a>

                <button class="brand-carousel-prev w-10 h-10 bg-offwhite rounded-xl border shadow-sm hover:bg-green-500 hover:text-white transition flex justify-center items-center">❮</button>
                <button class="brand-carousel-next w-10 h-10 bg-offwhite rounded-xl border shadow-sm hover:bg-green-500 hover:text-white transition flex justify-center items-center">❯</button>
            </div>
        </div>

        <div class="swiper brand-carousel">
            <div class="swiper-wrapper">

                <?php foreach ($products as $product): ?>
                    <div class="swiper-slide p-2">
                        <div class="bg-offwhite rounded-2xl shadow-sm hover:shadow-xl transition p-5 flex flex-col">

                            <a href="/pages/shop/products/product.php?id=<?= $product['id'] ?>">
                                <img loading="lazy"
                                     src="<?= htmlspecialchars($product['product_image']) ?>"
                                     class="rounded-xl w-full h-48 object-cover">
                            </a>

                            <h5 class="text-xl font-semibold mt-4"><?= htmlspecialchars($product['name']) ?></h5>

                            <p class="text-gray-600 text-lg mt-1">
                                €<?= number_format($product['price'], 2, ',', '.') ?>
                            </p>

                            <?php if ($product['stock_status'] === 'instock'): ?>
                                <button class="mt-auto bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-xl add-to-cart"
                                        data-id="<?= $product['id']; ?>"
                                        data-qty="1"
                                        data-price="<?= (int) round($product['price'] * 100) ?>"
                                        data-name="<?= htmlspecialchars($product['name']); ?>">
                                    🛒 In winkelwagen
                                </button>
                            <?php else: ?>
                                <span class="mt-4 text-red-600 font-bold">Uitverkocht</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

    </div>
</section>


<!-- POPULAIRE PRODUCTEN GRID -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">

        <h3 class="text-3xl font-extrabold text-gray-900">Populaire producten</h3>

        <div class="grid gap-10 mt-12 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">

            <?php foreach ($products as $product): ?>
                <div class="bg-offwhite rounded-2xl shadow-sm hover:shadow-xl transition p-5 flex flex-col">

                    <a href="/pages/shop/products/product.php?id=<?= $product['id']; ?>">
                        <img src="<?= htmlspecialchars($product['product_image']); ?>"
                             class="rounded-xl w-full h-48 object-cover">
                    </a>

                    <h4 class="text-lg font-semibold mt-4"><?= htmlspecialchars($product['name']); ?></h4>

                    <span class="text-gray-700 text-xl font-bold mt-1">€<?= number_format($product['price'], 2, ',', '.'); ?></span>

                    <?php if ($product['stock_status'] === 'instock'): ?>
                        <button class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-xl mt-4 w-full add-to-cart"
                                data-id="<?= $product['id']; ?>">
                            🛒 Toevoegen
                        </button>
                    <?php else: ?>
                        <span class="text-red-600 font-bold mt-4 text-center">Uitverkocht</span>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>


<?php
include $_SERVER['DOCUMENT_ROOT'] . '/partials/home/deal_of_the_week.php';
include $_SERVER['DOCUMENT_ROOT'] . '/partials/home/newsletter.php';
include $_SERVER['DOCUMENT_ROOT'] . '/partials/home/blogsection.php';
include $_SERVER['DOCUMENT_ROOT'] . '/partials/home/announcement.php';
include $_SERVER['DOCUMENT_ROOT'] . '/partials/home/most_searched.php';
include $_SERVER['DOCUMENT_ROOT'] . '/partials/home/bottom.php';
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
