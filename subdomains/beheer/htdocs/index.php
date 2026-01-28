<?php
// index.php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$pagetitle = "Admin Dashboard";
$description = "Overzicht van het Windels Product Manager dashboard.";
$keywords = "Dashboard, Windels, Productbeheer, Voorraadbeheer";

include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

?>

<div class="container-fluid w-75">
    <div class="welcome-section mb-4">
        <h2>Welkom bij het Dashboard</h2>
        <p>Hier kun je een overzicht vinden van je producten, categorieën, en recente activiteiten.</p>
    </div>

    <div class="row"> 
        <!-- Blok: Productbeheer -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h3 class="card-title">Productbeheer</h3>
                    <ul>
                        <li><a href="/pages/products/products.php">Producten beheren</a></li>
                        <li><a href="/pages/categories/categories.php">Categorieën beheren</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Blok: Kalenderbeheer -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h3 class="card-title">Kalenderbeheer</h3>
                    <ul>
                        <li><a href="/pages/calendar/calendar.php">Kalender beheren</a></li>
                        <li><a href="/pages/calendar/opening_hours.php">Openingstijden beheren</a></li>
                        <li><a href="/pages/calendar/add_closed_day.php">Sluitingsdagen beheren</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Blok: Paginabeheer -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h3 class="card-title">Pagina Beheer</h3>
                    <ul>
                        <li><a href="/pages/paginabeheer/create_page.php">Nieuwe Pagina Aanmaken</a></li>
                        <li><a href="/pages/paginabeheer/manage_pages.php">Pagina's Beheren</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Blok: Logs en instellingen -->
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h3 class="card-title">Logs en Instellingen</h3>
                    <ul>
                        <li><a href="/pages/logs/index.php">Logs bekijken</a></li>
                        <li><a href="/pages/settings/index.php">Instellingen beheren</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
