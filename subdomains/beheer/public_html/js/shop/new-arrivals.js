document.addEventListener('DOMContentLoaded', function () {
    var swiper = new Swiper('.products-carousel', {
        loop: true,
        slidesPerView: 4,
        spaceBetween: 20,
        navigation: {
            nextEl: '.products-carousel-next',
            prevEl: '.products-carousel-prev',
        },
        breakpoints: {
            320: { slidesPerView: 1, spaceBetween: 10 },
            768: { slidesPerView: 2, spaceBetween: 15 },
            1024: { slidesPerView: 4, spaceBetween: 20 }
        }
    });
});