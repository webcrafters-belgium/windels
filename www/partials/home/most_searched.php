<?php
// partials/home/most_searched.php
?>
<section class="py-5">
    <div class="container-fluid">
        <h2 class="my-5">Mensen zoeken ook naar</h2>

        <?php
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Meest voorkomende zoektermen ophalen
        $sql = "
            SELECT query, COUNT(*) as total 
            FROM search_queries 
            GROUP BY query 
            ORDER BY total DESC 
            LIMIT 5
        ";
        $result = $conn->query($sql);
        $queries = [];
        while ($row = $result->fetch_assoc()) {
            $queries[] = $row['query'];
        }

        if (count($queries) === 0) {
            echo "<p class='text-muted'>Geen zoekopdrachten gevonden.</p>";
        } else {
            // Producten ophalen op basis van deze zoektermen
            $types = str_repeat('s', count($queries));
            $stmt = $conn->prepare("
                SELECT p.id, p.name, p.price, i.image_path, i.webp_path
                FROM products p
                LEFT JOIN product_images i ON p.id = i.product_id
                WHERE " . implode(' OR ', array_fill(0, count($queries), 'p.name LIKE CONCAT("%", ?, "%")')) . "
                GROUP BY p.id
                LIMIT 15
            ");
            $stmt->bind_param($types, ...$queries);
            $stmt->execute();
            $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            if (count($products) > 0) {
                ?>
                <div id="mostSearchedCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $active = 'active';
                        foreach (array_chunk($products, 1) as $chunk) {
                            echo '<div class="carousel-item ' . $active . '"><div class="row">';
                            foreach ($chunk as $p) {
                                $img = $p['image_path'] ?? '/images/placeholder.png';
                                $webp = $p['webp_path'] ?? null;
                                echo '<div class="col-md-4">
                                        <div class="card h-100 text-center shadow-sm border-0">
                                            <div class="ratio ratio-1x1">
                                                <picture>
                                                    ' . ($webp ? '<source srcset="' . htmlspecialchars($webp) . '" type="image/webp">' : '') . '
                                                    <img src="' . htmlspecialchars($img) . '" 
                                                         class="card-img-top object-fit-cover" 
                                                         alt="' . htmlspecialchars($p['name']) . '">
                                                </picture>
                                            </div>
                                            
                                        </div>
                                      </div>';
                            }
                            echo '</div></div>';
                            $active = '';
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#mostSearchedCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#mostSearchedCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                <?php
            } else {
                echo "<p class='text-muted'>Geen producten gevonden voor deze zoekopdrachten.</p>";
            }
        }
        ?>
    </div>
</section>

<style>
    .card img {
        object-fit: cover;
    }
    .card .ratio {
        height: 250px; /* vaste hoogte */
    }
</style>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const items = document.querySelectorAll("#mostSearchedCarousel .carousel-item");

        items.forEach(item => {
            if (!item.classList.contains("active")) {
                item.style.display = "none";
            }
        });

        const carousel = document.getElementById("mostSearchedCarousel");

        carousel.addEventListener("slide.bs.carousel", function (e) {
            // Eerst ALLES verbergen
            items.forEach(i => i.style.display = "none");

            // Het item dat actief wordt: zichtbaar maken
            const next = e.relatedTarget;
            next.style.display = "block";
        });
    });
</script>

