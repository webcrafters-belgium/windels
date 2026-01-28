<div class="container-fluid">
    <h3>Best verkochte</h3>
    <div class="swiper-container">
        <div class=" swiper-wrapper best-selling-swiper-wrapper">
            <!-- Hier worden de producten dynamisch geladen via JavaScript -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Haal de producten op via fetch
        fetch('/functions/shop/amount-sold.php')
            .then(response => response.json())
            .then(data => {
                const swiperWrapper = document.querySelector('.best-selling-swiper-wrapper');
                swiperWrapper.innerHTML = ''; // Leeg de wrapper voordat we de producten toevoegen

                data.forEach(product => {
                    // Voeg het product toe aan de swiper
                    const productHTML = `
                        <div class="best-selling-item swiper-slide">
                            <span class="badge bg-success position-absolute m-3">Best verkocht</span>
                            <figure>
                                <a href="/product/${product.id}" title="${product.title}">
                                    <img src="${product.product_image}" class="tab-image" alt="${product.title}">
                                </a>
                            </figure>
                            <h3>${product.title}</h3>
                            <span class="price">€${product.total_product_price}</span>
                            <div class="d-flex align-items-center justify-content-between">
                                <a href="#" class="nav-link">Toevoegen aan winkelmandje <i class="bi bi-shopping-cart"></i></a>
                            </div>
                        </div>
                    `;
                    swiperWrapper.innerHTML += productHTML;
                });

                // Initialiseer Swiper na het toevoegen van de producten
                new Swiper('.swiper-container', {
                    navigation: {
                        nextEl: '.best-selling-carousel-next',
                        prevEl: '.best-selling-carousel-prev',
                    },
                    slidesPerView: 4,
                    spaceBetween: 20,
                    loop: true, // Als je een loop wilt gebruiken
                });
            })
            .catch(error => console.error('Error fetching top products:', error));
    });
</script>
