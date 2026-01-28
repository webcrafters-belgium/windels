<?php
session_start();
if (!isset($_SESSION['partner_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';
$partner_id = $_SESSION['partner_id'];
$is_actief = 0;
// Logo ophalen
$current_logo = null;
$stmt = $mysqli->prepare("SELECT is_actief FROM funeral_partners WHERE id = ?");
$stmt->bind_param('i', $partner_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $is_actief = (int)$row['is_actief'];
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
.btn-home {
    background-color: #1e4025;
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: 30px;
    border: none;
    cursor: pointer;
    display: block;
    width: fit-content;
    margin: 2rem auto 0 auto;
    font-weight: 600;
    text-align: center;
    transition: background-color 0.2s ease;
    text-decoration: none;
}

.btn-home:hover {
    background-color: #2e6a3f;
}
.alert-deactivated{
    margin:20px auto;
    padding:15px;
    border-radius:8px;
    background:#f8d7da;
    color:#721c24;
    border:1px solid #f5c6cb;
    font-weight:bold;
    max-width:600px;
    text-align:center
}
.alert-info {
    margin: 20px auto;
    padding: 15px;
    border-radius: 8px;
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
    font-weight: bold;
    text-align: center;
}
.alert-info a {
    color: #0c5460;
    font-weight: bold;
    text-decoration: underline;
}
.alert-info a:hover {
    text-decoration: none;
}
.alert-deactivated{
    margin:20px auto;
    padding:15px;
    border-radius:8px;
    background:#f8d7da;
    color:#721c24;
    border:1px solid #f5c6cb;
    font-weight:bold;
    text-align:center
}
</style>
<main class="dashboard-page container">
    <div class="dashboard-welcome">
        <h1>Accountbeheer</h1>
        <p>Beheer je bedrijfsgegevens, wijzig je wachtwoord en upload een nieuw bedrijfslogo voor gebruik op facturen of documenten.</p>
    </div>
    <?php if ($is_actief): ?>
    <div class="alert-info">
    <strong>Wil je de samenwerking stopzetten?</strong><br>
    Stuur ons een e-mail via <a href="mailto:info@windelsgreen-decoresin.com">info@windelsgreen-decoresin.com</a> om dit aan te vragen.<br>
    De mogelijkheid om dit zelfstandig te doen wordt later dit jaar toegevoegd.
</div>
<?php endif; ?>
    <?php if (!$is_actief): ?>
    <div class="alert-deactivated">
        <strong>Opgelet:</strong> Dit account is gedeactiveerd.<br>
        De samenwerking is (tijdelijk) stopgezet. gegevens wijzigen, bedrijfslogo wijzigen & over-mij pagina wijzigen is niet meer mogelijk.
    </div>
<?php endif; ?>

    <div class="dashboard-actions">
    <?php if ($is_actief): ?>
        <div class="dashboard-card">
            <h2>Gegevens Wijzigen</h2>
            <p>Pas je adres en bedrijfsnaam aan.</p>
            <a href="profiel_bewerken.php" class="btn">Gegevens wijzigen</a>
        </div>
        <?php endif; ?>
        <div class="dashboard-card">
            <h2>Wachtwoord Instellen</h2>
            <p>Wijzig je huidige wachtwoord.</p>
            <a href="wachtwoord_instellen.php" class="btn">Wachtwoord wijzigen</a>
        </div> 
        <?php if ($is_actief): ?>
        <div class="dashboard-card">
            <h2>Bedrijfslogo Wijzigen</h2>
            <p>Upload een nieuw logo ter vervanging van het bestaande.</p>
            <a href="logo_wijzigen.php" class="btn">Logo wijzigen</a>
        </div>
       
        <div class="dashboard-card">
            <h2>Over mij pagina</h2>
            <p>Hier kan je de klant pagina over mij bekijken en bewerken.</p>
            <a href="over_mij.php" class="btn">over mij wijzigen</a>
        </div>
        <?php endif; ?>
    </div>
    <a href="../dashboard.php" class="btn-home">Terug naar dashboard</a>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
