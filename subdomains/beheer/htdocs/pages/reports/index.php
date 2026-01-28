<?php
// Start de sessie
session_start();

// Vereiste instellingen en initialisaties
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Inclusie van de header
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<main class="container py-4">
    <h1 class="h3 mb-4">Welkom op de nieuwe pagina</h1>
    <p>Dit is de report-pagina.</p>

    <!-- Jouw inhoud hier -->
</main>

<?php
// Inclusie van de footer
include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>
