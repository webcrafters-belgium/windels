// Gegevens voor de banners (kan ook van een server worden geladen)
const banners = [
    {
        category: "Handgemaakt",
        title: "Unieke Resin Kunstwerken",
        description: "Creëer een exclusieve sfeer met onze handgemaakte resin kunstwerken. Van tafelbladen tot wanddecoraties, elk stuk is uniek en perfect voor elk interieur.",
        link: "#resin-art",
        image: "/images/layout/resin-art/resin-art-1.png"
    },
    {
        category: "Op Maat",
        title: "Op Maat Gemaakte Kunst",
        description: "Wij specialiseren ons in gepersonaliseerde resin creaties, ideaal voor zakelijke ruimtes, huwelijkscadeaus of persoonlijke interieurprojecten.",
        link: "#custom-projects",
        image: "/images/layout/resin-art/100__handgemaakte_harskunst-removebg-preview.png"
    },
    {
        category: "Groene Decoratie",
        title: "Planten & Resin Combinaties",
        description: "Combineer het natuurlijke met het moderne: unieke resin ontwerpen gecombineerd met duurzame planten voor een milieuvriendelijke uitstraling.",
        link: "#green-decor",
        image: "/images/layout/resin-art/green-deco.png"
    }
];

// Functie om de banners dynamisch toe te voegen aan de swiper
function generateSwiperSlides() {
    const swiperWrapper = document.getElementById('main-swiper-wrapper1');

    banners.forEach(banner => {
        const slideHTML = `
                            <div class="swiper-slide">
                                <div class="row banner-content p-5">
                                    <div class="content-wrapper col-md-7 m-auto justify-content-center align-items-center d-flex flex-column">
                                        <div class="categories my-3 text-center">${banner.category}</div>
                                        <h3 class="display-4 text-center">${banner.title}</h3>
                                        <p  class="text-center">${banner.description}</p>
                                        <a href="${banner.link}" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1 px-4 py-3 mt-3">Bekijk Collectie</a>
                                    </div>
                                    <div class="img-wrapper col-md-5  m-auto justify-content-center align-items-center">
                                        <img loading="lazy" src="${banner.image}" class="img-fluid" alt="${banner.title}">
                                    </div>
                                </div>
                            </div>
                        `;
        swiperWrapper.innerHTML += slideHTML;
    });
}

// Wacht tot het DOM volledig geladen is en genereer de slides
document.addEventListener('DOMContentLoaded', function () {
    generateSwiperSlides();

    // Initialiseer de Swiper na het genereren van de slides
    var swiper = new Swiper(".main-swiper1", {
        speed: 500,
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });
});