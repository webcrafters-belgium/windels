<?php

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$count = 0;
$session_id = session_id();
$session_lifetime = 1800;

// Insert session into sessions table
$getSessionQuery = "SELECT time FROM `sessions` WHERE session_id = ?";
$stmt = $conn->prepare($getSessionQuery);
if ($stmt) {
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $stmt->bind_result($last_time);
    $stmt->fetch();
    $stmt->close();

    if ($last_time) {
        // Controleer of de sessie nog geldig is
        $time_difference = time() - strtotime($last_time);
        if ($time_difference > $session_lifetime) {
            // Sessie is verlopen, update tijd
            $updateSessionQuery = "UPDATE `sessions` SET time = NOW() WHERE session_id = ?";
            $stmt = $conn->prepare($updateSessionQuery);
            $stmt->bind_param("s", $session_id);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        // Sessie bestaat nog niet, voeg deze toe
        $addSessionQuery = "INSERT INTO `sessions` (session_id, time) VALUES (?, NOW())";
        $stmt = $conn->prepare($addSessionQuery);
        $stmt->bind_param("s", $session_id);
        $stmt->execute();
        $stmt->close();
    }
} else {
    die("Error preparing statement: " . $conn->error);
}

// Query to get the total number of items in the cart
$query = "SELECT COUNT(*) AS item_count FROM cart_items WHERE session_id = ?";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("s", $session_id);  // "s" for string type
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the result
    if ($row = $result->fetch_assoc()) {
        $count = $row['item_count'];  // The number of items in the cart
    } else {
        $count = 0;  // Default to 0 if no items are found
    }
} else {
    die("Error preparing statement: " . $conn->error);
}

$DBcost = 0; // Initialize variable

// Query to calculate the total cost of items in the cart
$query = "SELECT SUM(price * quantity) AS total_cost FROM cart_items WHERE session_id = ?";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("s", $session_id);  // Bind session ID
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the result
    if ($row = $result->fetch_assoc()) {
        $DBcost = $row['total_cost'] ?? 0;  // Default to 0 if no cost is found
    }
} else {
    die("Error preparing statement: " . $conn->error);
}

// echo "<br>Total items in cart: " . $count . " <br>";


function logPageView($conn): void
{
    // Haal de user agent en IP-adres van de gebruiker op
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Bereid de SQL-query voor om gegevens in de database in te voegen
    $query = "INSERT INTO pageviews (user_agent, `ip-address`, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Bind parameters aan de query
        $stmt->bind_param("ss", $user_agent, $ip_address);

        // Voer de query uit
        if ($stmt->execute()) {
            //echo "Page view successfully logged.";
        } else {
            echo "Error logging page view: " . $stmt->error;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}


// Database verbinding
$conn = new mysqli("localhost", "matthias", "7824", "windelsbe_db");

// Controleer de verbinding
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verkrijg de zoekopdracht van het zoekformulier
if (isset($_GET['search'])) {
    $searchTerm = $conn->real_escape_string($_GET['search']); // Voorkom SQL-injectie

    // Zoekopdracht opslaan in de database
    $sql = "INSERT INTO search_queries (query) VALUES ('$searchTerm')";
}


echo '

<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Windels - Deco resin</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="author" content="Gielen Matthias || Webcrafters">
    <meta name="keywords" content="Windels, Green, Deco, hars, epoxy, Hamont, Bocholt, decoratie, feestdagen">
    <meta name="description" content="">

    <link rel="icon" href="/favicon.ico">
    <link rel="stylesheet" href="/css/swiper-bundle.min.css">
    <link href="/css/bootstrap-5.3.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/vendor.css">
    <link rel="stylesheet" type="text/css" href="/css/styles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    <script src="/js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@latest/swiper-bundle.min.js"></script>


</head>
<body>
';

// include $_SERVER['DOCUMENT_ROOT'] . '/assets/svg/iconsList.php'

?>
<div class="preloader-wrapper">
    <div class="preloader"></div>
</div>

<!--
<script src="/js/shop/getCartItems.js"></script>
-->
<?php

echo'
<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasCart" aria-labelledby="Mijn winkelmand">
    <div class="offcanvas-header justify-content-center">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Sluiten"></button>
    </div>
    <div class="offcanvas-body">
        <div class="order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Je winkelmandje</span>
                <span class="badge bg-primary rounded-pill" id="cart-item-count">€' . $count . '</span> 
                <!-- Dynamisch bijgewerkte item count -->
            </h4>
            <ul class="list-group mb-3">
                <!-- Dynamisch gegenereerde lijstitems worden hier ingevoegd -->
            </ul>
            <button class="w-100 btn btn-primary btn-lg" type="button" data-bs-dismiss="offcanvas" aria-label="Proceed to checkout">
                Doorgaan naar afrekenen
            </button>
        </div>
    </div>
</div>



<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasSearch" aria-labelledby="Zoeken">
    <div class="offcanvas-header justify-content-center">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Sluiten"></button>
    </div>
    <div class="offcanvas-body">
        <div class="order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Zoeken</span>
            </h4>
            <form role="search" action="/" method="get" class="d-flex mt-3 gap-0">
                <input class="form-control rounded-start rounded-0 bg-light" type="email" placeholder="Waar ben je naar op zoek?" aria-label="Waar ben je naar op zoek?">
                <button class="btn btn-dark rounded-end rounded-0" type="submit">Zoeken</button>
            </form>
        </div>
    </div>
</div>
';
?>
<header>
    <div class="container-fluid">
        <div class="row py-3 border-bottom">
            <!-- Logo sectie -->
            <div class="col-sm-4 col-lg-3 text-center text-sm-start">
                <div class="main-logo">
                    <a href="/">
                        <img src="/images/windels-logo.svg" alt="Windels Green Decor & Resin logo" class="img-fluid"
                             style="width: 45%;">
                    </a>
                </div>
            </div>

            <!-- Zoekbalk sectie -->
            <div class="col-sm-6 offset-sm-2 offset-md-0 col-lg-5 d-none d-lg-block">
                <div class="search-bar row bg-light p-2 my-2 rounded-4">
                    <div class="col-md-4 d-none d-md-block">
                        <select class="form-select border-0 bg-transparent">
                            <option>Alle Categorieën</option>
                            <option>Groene Decoratie</option>
                            <option>Hars Kunstwerken</option>
                            <option>Workshops</option>
                        </select>
                    </div>
                    <div class="col-11 col-md-7">
                        <form id="search-form" class="text-center" action="/" method="post">
                            <label>
                                <input type="text" class="form-control border-0 bg-transparent" placeholder="Zoek meer dan 300 producten"/>
                            </label>
                        </form>
                    </div>
                    <div class="col-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Support en winkelwagen sectie -->
            <div class="col-sm-8 col-lg-4 d-flex justify-content-end gap-5 align-items-center mt-4 mt-sm-0 justify-content-center justify-content-sm-end">
                <div class="support-box text-end d-none d-xl-block">
                    <span class="fs-6 text-muted">Vragen of ondersteuning?</span>
                    <h5 class="mb-0">+32 11 75 33 19</h5>
                </div>

                <ul class="d-flex justify-content-end list-unstyled m-0">
                    <li>
                        <a href="/pages/account/" class="rounded-circle bg-light p-2 mx-1">
                            <i class="bi bi-person"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="rounded-circle bg-light p-2 mx-1">
                            <i class="bi bi-heart"></i>
                        </a>
                    </li>
                    <li class="d-lg-none">
                        <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                            <i class="bi bi-cart"></i>
                        </a>
                    </li>
                    <li class="d-lg-none">
                        <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSearch" aria-controls="offcanvasSearch">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                </ul>

                <div class="cart text-end d-none d-lg-block dropdown">
                    <button class="border-0 bg-transparent d-flex flex-column gap-2 lh-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                        <span class="fs-6 text-muted dropdown-toggle">Uw Winkelwagen</span>
                        <span class="cart-total fs-5 fw-bold">€<?php echo number_format($DBcost, 2, ',', '.'); ?> </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row py-3">
            <div class="d-flex  justify-content-center justify-content-sm-between align-items-center">
                <nav class="main-menu d-flex navbar navbar-expand-lg">

                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                            aria-controls="offcanvasNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">

                        <div class="offcanvas-header justify-content-center">
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>

                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end menu-list list-unstyled d-flex gap-md-3 mb-0">
                                <li class="nav-item">
                                    <a href="/" class="nav-link">Home</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="/pages/shop" class="nav-link dropdown-toggle" role="button"
                                       data-bs-toggle="dropdown" aria-expanded="false">Winkel</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/pages/shop/promo" class="dropdown-item">Promo actie</a>
                                            <ul class="dropdown">
                                                <li><a href="/pages/shop/promo">Promo actie</a></li>
                                                <li><a href="/pages/shop/promo/voorwaarden">Actie & promo’s
                                                        Voorwaarden</a></li>
                                                <li><a href="/pages/shop/promo/verjaardag">Verjaardagsactie</a></li>
                                                <li><a href="/pages/shop/promo/facebook-win-actie">Win actie
                                                        facebook</a></li>
                                                <li><a href="/pages/shop/promo/duo-ticket-win">Winactie duo ticket
                                                        indoorkerstmarkt Oudsbergen</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="/pages/shop/onze-producten" class="dropdown-item">Onze producten</a>
                                            <ul class="dropdown">
                                                <li><a href="/pages/shop/cadeaus">Cadeaus & geschenkmanden</a></li>
                                                <li><a href="/pages/shop/epoxyhars">Epoxyhars producten</a></li>
                                                <li><a href="/pages/shop/geurkaarsen">Hand gemaakte geurkaarsen</a></li>
                                                <li><a href="/pages/shop/terrazzo">Terrazzo producten</a></li>
                                                <li><a href="/pages/shop/groenten-fruit">Verse groenten & fruit</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="/pages/shop/overige-producten" class="dropdown-item">Overige producten</a>
                                            <ul class="dropdown">
                                                <li><a href="/pages/shop/partijhandel">Partijhandel</a></li>
                                                <li><a href="/pages/shop/uit-assortiment">Uit assortiment</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="/pages/shop/winkel" class="dropdown-item">Winkel</a>
                                            <ul class="dropdown">
                                                <li><a href="/pages/shop/orders-tracking">Orders tracking</a></li>
                                                <li><a href="/pages/shop/winkelwagen">Winkelwagen</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="/pages/account/mijn-account" class="nav-link dropdown-toggle"
                                       role="button" data-bs-toggle="dropdown" aria-expanded="false">Mijn account</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/pages/account/login" class="dropdown-item">Login</a></li>
                                        <li><a href="/pages/account/register" class="dropdown-item">Register</a></li>
                                        <li><a href="/pages/account/accountgegevens"
                                               class="dropdown-item">Accountgegevens</a></li>
                                        <li><a href="/pages/account/bestellingen" class="dropdown-item">Bestellingen</a></li>
                                        <li><a href="/pages/account/favoriete-producten" class="dropdown-item">Mijn favoriete producten</a></li>
                                        <li><a href="/pages/account/mijn-punten" class="dropdown-item">Mijn punten</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="/pages/shop/orders-tracking" class="nav-link">Orders tracking</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="/pages" class="nav-link dropdown-toggle" role="button"
                                       data-bs-toggle="dropdown" aria-expanded="false">Pagina’s</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/pages/shop/promo" class="dropdown-item">Actie’s &
                                                promo’s</a>
                                            <ul class="dropdown">
                                                <li><a href="/pages/shop/promo/acties-promos">Acties & promo’s</a></li>
                                                <li><a href="/pages/shop/promo/win-facebook">Win actie facebook</a>
                                                    <ul class="dropdown">
                                                        <li><a
                                                                    href="/pages/shop/promo/facebook-algemene-voorwaarden">Algemene voorwaarden facebook win actie</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href="/pages/shop/producten" class="dropdown-item">Producten</a>
                                            <ul class="dropdown">
                                                <li><a href="/pages/shop/producten/epoxyhars">Epoxyhars</a>
                                                    <ul class="dropdown">
                                                        <li><a href="/pages/shop/producten/over-epoxyhars">Over Epoxyhars</a></li>
                                                        <li><a href="/pages/shop/producten/epoxyhars-garantie">Epoxyhars Garantie</a></li>
                                                        <li><a href="/pages/shop/producten/maatwerk-epoxyhars">Epoxyhars op maat gemaakt</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="/pages/shop/producten/terrazzo">Terrazzo</a>
                                                    <ul class="dropdown">
                                                        <li><a href="/pages/shop/producten/over-terrazzo">Over Terrazzo</a></li>
                                                        <li><a href="/pages/shop/producten/terrazzo-garantie">Terrazzo Garantie</a></li>
                                                        <li><a href="/pages/shop/producten/maatwerk-terrazzo">Terrazzo op maat gemaakt</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href="/pages/kalender" class="dropdown-item">Kalender</a>
                                            <ul class="dropdown">
                                                <li><a href="/pages/kalender/evenementen">Evenementen</a></li>
                                                <li><a href="/pages/kalender/jaarmarkt">Jaarmarkt</a></li>
                                                <li><a href="/pages/kalender/avondmarkt">Avondmarkt</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="/pages/over-ons" class="nav-link">Over ons</a>
                                </li>
                                <li class="nav-item">
                                    <a href="/pages/contact" class="nav-link">Contact</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>