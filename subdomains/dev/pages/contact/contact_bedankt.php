<?php 
header("Refresh: 5; URL=/index.php");
include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; 
?>
<main class="contactbedankt-section">
    <div class="contactbedankt-content">
        <h1>Bedankt voor uw bericht</h1>
        <p>We hebben uw bericht goed ontvangen en nemen zo spoedig mogelijk contact met u op.</p>
        <a href="/index.php" class="btn">Terug naar homepagina</a>
    </div>
</main>
<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
