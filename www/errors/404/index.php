<?php
    $pagetitle = '404 - Niet gevonden';
    include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>

<div class="container-fluid  h-100 py-5">
    <h1 class="text-center">Oeps! Pagina niet gevonden.</h1>
    <div class="bg-404"></div>
    <p  class="text-center">De pagina die je zoekt, bestaat niet of is verplaatst. <a href="/">Keer terug naar de
            homepagina</a>.</p>
</div>

<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
?>