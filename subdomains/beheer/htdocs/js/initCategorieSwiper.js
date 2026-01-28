var initCategorieSwiper = function() {
    // Swiper voor de categorieën
    var category_swiper = new Swiper(".category-carousel", {
        slidesPerView: 6,
        spaceBetween: 30,
        speed: 500,
        navigation: {
            nextEl: ".category-carousel-next",
            prevEl: ".category-carousel-prev",
        },
        breakpoints: {
            0: {
                slidesPerView: 2,
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

initCategorieSwiper();
