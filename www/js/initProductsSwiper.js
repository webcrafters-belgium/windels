// Swiper voor de products

var initProductsSwiper = function() {
    var products_swiper = new Swiper(".products-carousel", {
        slidesPerView: 5,
        spaceBetween: 30,
        speed: 500,
        navigation: {
            nextEl: ".products-carousel-next",
            prevEl: ".products-carousel-prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 3,
            },
            991: {
                slidesPerView: 4,
            },
            1500: {
                slidesPerView: 6,
            },
        }
    });
}

initProductsSwiper();