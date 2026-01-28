<?php
// new-arrivals.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Lijst van producttabellen
$productTables = ['products'];

// Leeg de array voor producten
$products = [];

foreach ($productTables as $table) {
    // Haal de laatste 5 SKU's op die een voorraadwijziging hebben gehad
    $stockHistoryQuery = "
    SELECT product_sku
    FROM stock_history 
    WHERE stock_change != 0
    GROUP BY product_sku
    ORDER BY MAX(change_date) DESC
    LIMIT 5
";


    if ($stockHistoryResult = $conn->query($stockHistoryQuery)) {
        // Haal de producten op uit de juiste tabel voor elke SKU
        while ($stockHistoryRow = $stockHistoryResult->fetch_assoc()) {
            $sku = $stockHistoryRow['product_sku'];

            // Zoek naar dit product in de betreffende producttabel
            $productQuery = "SELECT * FROM `$table` WHERE sku = ?";
            $stmt = $conn->prepare($productQuery);

            if ($stmt === false) {
                die('Fout bij het voorbereiden van de query: ' . $conn->error);
            }

            $stmt->bind_param('s', $sku); // Bind de SKU
            $stmt->execute();
            $productResult = $stmt->get_result();

            if ($productResult->num_rows > 0) {
                // Voeg het product toe aan de productenlijst
                while ($product = $productResult->fetch_assoc()) {
                    // Voeg productinformatie toe aan de array
                    $products[] = $product;
                }
            }
            $stmt->close();
        }
    } else {
        die('Fout bij het uitvoeren van de stock history query: ' . $conn->error);
    }
}

?>

<section class="py-5 overflow-hidden">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header d-flex justify-content-between">
                    <h2 class="section-title">Nieuwe producten</h2>
                    <div class="d-flex align-items-center">
                        <a href="#" class="btn-link text-decoration-none">Bekijk alle producten →</a>
                        <div class="swiper-buttons">
                            <button class="swiper-prev products-carousel-prev btn btn-primary">❮</button>
                            <button class="swiper-next products-carousel-next btn btn-primary">❯</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="products-carousel swiper">
                    <div class="swiper-wrapper">
                        <?php
                        // Controleer of er producten zijn
                        if (!empty($products)) {
                            foreach ($products as $product) {
                                echo <<<HTML
                                <div class="product-item swiper-slide">
                                    <a href="#" class="btn-wishlist"><svg width="24" height="24"><use xlink:href="#heart"></use></svg></a>
                                    <figure>
                                        <a href="product.php?id={$product['id']}" title="{$product['title']}">
                                            <img src="{$product['product_image']}" class="tab-image" loading="lazy" 
                                            alt="{$product['title']}">
                                        </a>
                                    </figure>
                                    <h3>{$product['title']}</h3>
                                    <span class="rating"><svg width="24" height="24" class="text-primary"><i class="bi bi-star-solid"></i></svg> 4.5</span>
                                    <span class="price">€{$product['total_product_price']}</span>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="input-group product-qty">
                                            <span class="input-group-btn">
                                                <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                    <svg width="16" height="16"><use xlink:href="#minus"></use></svg>
                                                </button>
                                            </span>
                                            <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                            <span class="input-group-btn">
                                                <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                    <svg width="16" height="16"><use xlink:href="#plus"></use></svg>
                                                </button>
                                            </span>
                                        </div>
                                        <a href="#" class="nav-link">Toevoegen aan winkelmandje <iconify-icon icon="uil:shopping-cart"></iconify-icon></a>
                                    </div>
                                </div>
                                HTML;
                            }
                        } else {
                            echo <<<HTML
                            <p>No new products found.</p>
                            HTML;
                        }
                        ?>
                    </div>
                </div>
                <!-- / products-carousel -->
            </div>
        </div>
    </div>
</section>

