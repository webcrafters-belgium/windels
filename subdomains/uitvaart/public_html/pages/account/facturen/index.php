<?php
session_start();
if (!isset($_SESSION['partner_id'])) {
    header("Location: login.php");
    exit;
}
?>
<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>
<style>
     body {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
}
.dashboard-page {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 3rem auto 2rem auto;
}

</style>
<main class="dashboard-page container">
    <div class="dashboard-welcome">
        <h1>Facturatie</h1>
        <p>Welkom in het facturatiegedeelte van je account. Hier kan je eenvoudig facturen bekijken en opvolgen zoals opgemaakt door onze administratie.</p>
    </div>
    <div class="dashboard-actions">
        <div class="dashboard-card">
            <h2>Maandoverzicht</h2>
            <p>Bekijk een overzicht van alle bestellingen die deze maand gefactureerd zullen worden.</p>
            <a href="maandoverzicht.php" class="btn">Bekijk overzicht</a>
        </div>
        <div class="dashboard-card">
            <h2>Mijn Facturen</h2>
            <p>Toegang tot je volledige facturenhistoriek, overzichtelijk per maand.</p>
            <a href="facturen.php" class="btn">Facturen bekijken</a>
        </div>
        <div class="dashboard-card">
            <h2>Terug naar Dashboard</h2>
            <p>Ga terug naar het hoofdmenu van je account.</p>
            <a href="../dashboard.php" class="btn">Terug naar dashboard</a>
        </div>
    </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
