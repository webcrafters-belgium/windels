document.addEventListener("DOMContentLoaded", function () {
    var initBrandSwiper = function () {
        if (document.querySelector(".brand-carousel")) {
            var brand_swiper = new Swiper(".brand-carousel", {
                slidesPerView: 4,
                spaceBetween: 30,
                speed: 500,
                navigation: {
                    nextEl: ".brand-carousel-next",
                    prevEl: ".brand-carousel-prev",
                },
                breakpoints: {
                    0: {
                        slidesPerView: 2,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    991: {
                        slidesPerView: 3,
                    },
                    1500: {
                        slidesPerView: 4,
                    },
                },
            });
        }
    };

    initBrandSwiper();
});
