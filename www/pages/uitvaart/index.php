<?php
// FILE: /pages/uitvaart/index.php

session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$pagetitle = "Uitvaart – Een tastbare herinnering | Windels Green & Deco Resin";
$pagedescription = "Ons gespecialiseerde uitvaartplatform voor handgemaakte herdenkingsobjecten in epoxy en terrazzo. Met zorg, rust en respect.";
$pagekeywords = "uitvaart, herdenking, epoxy herinnering, terrazzo, asverwerking";

include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<main id="main">

    <!-- HERO -->
    <section class="hero-section bg-offwhite py-5">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-5 fw-bold mb-3">
                        Een blijvende herinnering
                    </h1>
                    <p class="lead mb-4">
                        Binnen Windels Green & Deco Resin hebben we een
                        apart uitvaartplatform opgezet, volledig gericht op
                        rust, maatwerk en persoonlijke begeleiding.
                    </p>

                    <a href="https://uitvaart.windelsgreen-decoresin.com"
                       class="btn btn-primary btn-lg rounded-pill px-4"
                       rel="noopener"
                    >
                        Naar ons uitvaartplatform
                    </a>
                </div>

                <div class="col-lg-6">
                    <div class="rounded-4 shadow-soft overflow-hidden">
                        <img
                            src="/images/categories/uitvaart.webp"
                            alt="Uitvaart herdenkingsobject in epoxy"
                            class="img-fluid w-100"
                            loading="lazy"
                        >
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CONTEXT -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-lg-9">
                    <p class="fs-5 text-muted mb-4">
                        Uitvaartcreaties vragen een andere aanpak dan onze
                        reguliere collecties. Daarom werken we binnen ons
                        eigen platform met extra tijd, overleg en zorg.
                    </p>

                    <p class="fs-5 text-muted mb-4">
                        Elk stuk wordt in overleg samengesteld en met respect
                        vervaardigd. Geen standaardproducten, geen druk,
                        enkel aandacht voor wat telt.
                    </p>

                    <div class="bg-light rounded-4 p-4 mt-4">
                        <h3 class="h5 fw-bold mb-2">
                            Wat je mag verwachten
                        </h3>
                        <ul class="mb-0">
                            <li>persoonlijke begeleiding binnen ons team</li>
                            <li>maatwerk in epoxy en terrazzo</li>
                            <li>discrete en respectvolle verwerking</li>
                            <li>heldere afspraken en communicatie</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5 bg-offwhite">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">
                Meer weten over onze uitvaartcreaties?
            </h2>
            <p class="text-muted mb-4">
                Bezoek ons gespecialiseerde uitvaartplatform.
            </p>

            <a href="https://uitvaart.windelsgreen-decoresin.com"
               class="btn btn-outline-primary btn-lg rounded-pill px-5"
               rel="noopener"
            >
                Uitvaartcollectie bekijken
            </a>
        </div>
    </section>

</main>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
