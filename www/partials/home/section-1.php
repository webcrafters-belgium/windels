
<section class="py-3" style="background-image: url('/images/background-pattern.jpg');background-repeat: no-repeat; background-size: cover;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="banner-blocks">
                    <div class="banner-ad large bg-info block-1">
                        <div class="swiper main-swiper1 h-100">
                            <div class="swiper-wrapper h-100" id="main-swiper-wrapper1">
                                <!-- Dynamisch gegenereerde slides komen hier -->
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>

                    <script>
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
                                        <img src="${banner.image}" class="img-fluid" alt="${banner.title}">
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
                    </script>

                    <div class="banner-ad bg-success-subtle block-2" style="background: url('https://windels.webmagic.be/images/layout/resin-art/table1.png')
                    no-repeat right
                    bottom; background-size: 50%">
                        <div class="row banner-content p-5">
                            <div class="content-wrapper col-md-7">
                                <div class="categories sale mb-3 pb-3">Actie!</div>
                                <h3 class="banner-title">Limited Edition Resin Tafels</h3>
                                <p>Grijp nu je kans op een exclusieve resin tafel, met natuurlijke houtpatronen en premium afwerkingen.</p>
                                <a href="#limited-collection" class="d-flex align-items-center nav-link">Shop Collectie
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="banner-ad bg-danger block-3" style="background: url('/images/layout/resin-art/resin-art-2.png')
                    no-repeat right
                    bottom; background-size: 50%">
                        <div class="row banner-content p-5">
                            <div class="content-wrapper col-md-7">
                                <div class="categories sale mb-3 pb-3">15% Korting</div>
                                <h3 class="item-title">Wanddecoratie</h3>
                                <p>Ontdek onze prachtige resin wanddecoraties en profiteer van een speciale korting tijdens deze actieperiode.</p>
                                <a href="#wall-art" class="d-flex align-items-center nav-link">Shop Collectie <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
