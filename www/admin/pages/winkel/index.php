<?php
include $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
include $_SERVER["DOCUMENT_ROOT"] . '/header.php';
?>
    <div class="container mt-3">
        <div class="d-flex align-items-center">
            <h1 class="h3 text-center text-md-left">Windels green &amp; deco resin</h1>
        </div>
        <nav class="navbar navbar-expand-lg navbar-custom mt-3">
            <a class="navbar-brand" href="/index.php"> <i class="fa fa-home mr-2"></i> Home</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/profile.php"><i class="fas fa-user mr-2"></i> Mijn Profiel</a>
                    </li>
                    <li class="nav-item d-flex align-items-center notification">
                        <a class="nav-link" id="chat-href"><i class="fas fa-comments mr-2"></i> chat
                            <!-- Het chat-icoon -->
                            <span id="badge" class="badge badge-danger badge-pill  d-none">0</span>
                            <!-- De badge met het aantal nieuwe berichten -->
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link" href="/received_messages.php"><i class="fa fa-bell mr-2" style="transform: rotate(-45deg);"></i><!-- Todo: Maak received-messages.php -->
                            Meldingen
                            <span class="badge badge-danger ml-2">1</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/gdpr.php"><i class="fas fa-shield-alt mr-2"></i> GDPR</a> <!-- Todo: Maak gdpr.php -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/whistleblower.php"><i class="fas fa-bullhorn mr-2"></i> <!-- Todo: Maak whistleblower.php -->
                            Klokkenluidersmelding</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="info-bar d-flex justify-content-between align-items-center">
            <div class="dropdown">
                <button class="btn btn-success" type="button">
                    Ingelogd als: <i class="fas fa-user mr-2"></i> Webshop Customer
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item bg-info text-white" href="/profile.php"><i class="fas fa-user"></i> Mijn
                        Profiel</a>
                    <a class="dropdown-item bg-info  text-white" href="/clock_in_out.php"><i class="fas fa-clock"></i>
                        Klok in/out</a>
                    <a class="dropdown-item bg-info  text-white" href="/user_rooster.php"><i
                                class="fas fa-calendar"></i> Mijn uurrooster</a>
                    <a class="dropdown-item bg-warning" href="/pauze.php"><i class="fas fa-coffee"></i> Pauze</a>
                    <a class="dropdown-item bg-danger text-white" href="/logout.php"><i class="fas fa-sign-out-alt"></i>
                        Logout</a>
                </div>
            </div>
            <div class="navbar-text" id="datetime"></div>
        </div>

        <div id="winkel-status" class="info-opening">


            <div class="alert alert-danger text-center">📅 Nu gesloten, maar vandaag geopend van 19:00 tot 21:00.</div>
        </div>


        <!-- GDPR Waarschuwing -->
        <div class="alert alert-warning mt-3" role="alert">
            <strong>Let op!</strong> U heeft onze nieuwste GDPR-voorwaarden nog niet geaccepteerd.
            <a href="/gdpr.php" class="alert-link">Klik hier</a> om deze te accepteren en verder te gaan. <!-- Todo: Maak gdpr.php -->
        </div>

        <!-- Nieuwe alert voor 2FA activering -->
        <div class="alert alert-warning mt-3" role="alert">
            <strong>Veiligheidswaarschuwing!</strong> U heeft nog geen tweestapsverificatie (2FA) ingeschakeld.
            <a href="/profile.php#twofactor-tab" class="alert-link">Klik hier</a> om 2FA in te schakelen en uw account
            te beveiligen.
        </div>

        <!-- Toon alle actieve alerts -->
    </div>
    <div class="container mt-5">
        <div class="container mt-5">
            <div class="card shadow-lg p-4 main-content">
                <!-- Knoppenweergave op basis van rol -->
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-home"></i>
                                    Home </h5>
                                <a href="/index.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Home </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-image"></i>
                                    Product afbeelding album </h5>
                                <a href="/images/products/product_img_folder.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Product afbeelding album </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-tshirt"></i>
                                    Producten </h5>
                                <a href="/admin/pages/winkel/producten" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Producten </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-warehouse "></i>
                                    schappenplan </h5>
                                <a href="/admin/pages/winkel/schappenplan/index.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar schappenplan </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-tag"></i>
                                    Schaplabel </h5>
                                <a href="/admin/pages/winkel/schaplabel/index.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Schaplabel </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-dolly"></i>
                                    Bestellingen </h5>
                                <a href="/admin/pages/winkel/orders/orders_view.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Bestellingen </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-clipboard-list"></i>
                                    Favv
                                </h5>
                                <a href="/admin/voedselproblemen/index.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Favv
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php

include $_SERVER["DOCUMENT_ROOT"] . '/footer.php';
