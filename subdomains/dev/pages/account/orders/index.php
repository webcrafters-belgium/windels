<?php
session_start();
if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/account/login.php");
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


.dashboard-page {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 3rem auto 2rem auto;
}

.dashboard-welcome {
    background-color: #f4f6f4;
    padding: 1.5rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
}

.dashboard-welcome h1 {
    margin-bottom: 0.5rem;
    font-size: 1.4rem;
    color: #2a5934;
}

.dashboard-welcome p {
    font-size: 1rem;
    color: #444;
    margin-bottom: 1rem;
}

/* ✅ Mobiele optimalisatie */
@media (max-width: 768px) {


    .dashboard-page {
        padding: 2rem 1rem;
        margin: 2rem 1rem;
        border-radius: 8px;
    }
}


</style>
<main class="dashboard-page container">
    <div class="dashboard-welcome">
        <h1>Bestellingen</h1>
        <p>Beheer hier eenvoudig alle bestellingen voor je klanten. Je kan nieuwe bestellingen plaatsen en bestaande opvolgen.</p>
    </div>
    <div class="dashboard-actions">
        <div class="dashboard-card">
            <h2>Nieuwe Bestelling</h2>
            <p>Plaats snel en eenvoudig een nieuwe bestelling voor een nabestaande.</p>
            <a href="/pages/assortiment.php" class="btn">Bestelling plaatsen</a>
        </div>
        <div class="dashboard-card">
            <h2>Mijn Bestellingen</h2>
            <p>Bekijk en volg de status van je eerder geplaatste bestellingen.</p>
            <a href="mijn_bestellingen.php" class="btn">Bestellingen bekijken</a>
        </div>
        <div class="dashboard-card">
            <h2>Terug naar Dashboard</h2>
            <p>Ga terug naar het hoofdmenu van je account.</p>
            <a href="../dashboard.php" class="btn">Terug naar dashboard</a>
        </div>
    </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
