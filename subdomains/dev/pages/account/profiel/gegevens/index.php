<?php
session_start();
if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/account/login.php");
    exit;
}
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';
$partner_id = $_SESSION['partner_id'];
$is_actief = 0;
// Logo ophalen
$current_logo = null;
$stmt = $mysqli->prepare("SELECT email, is_actief FROM funeral_partners WHERE id = ?");
$stmt->bind_param('i', $partner_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $is_actief = (int)$row['is_actief'];
    $partner_email = isset($row['email']) ? (string)$row['email'] : '';
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
        <h1>Account gegevens beheer</h1>
        <p>Beheer je bedrijfsgegevens, wijzig je wachtwoord en upload een nieuw bedrijfslogo.</p>
    </div>

    <div class="dashboard-actions">

        <div class="dashboard-card">
            <h2>Gegevens Wijzigen</h2>
            <p>Pas je adres en bedrijfsnaam aan.</p>
            <a href="profiel_bewerken.php" class="btn">Gegevens wijzigen</a>
        </div>
       
        <div class="dashboard-card">
            <h2>Wachtwoord Instellen</h2>
            <p>Wijzig je huidige wachtwoord.</p>
            <a href="wachtwoord_instellen.php" class="btn">Wachtwoord wijzigen</a>
        </div> 

        <div class="dashboard-card">
            <h2>Bedrijfslogo Wijzigen</h2>
            <p>Upload een nieuw logo ter vervanging van het bestaande.</p>
            <a href="logo_wijzigen.php" class="btn">Logo wijzigen</a>
        </div>
       
        <div class="dashboard-card">
            <h2>Terug naar Accountbeheer</h2>
            <p>Ga terug naar het hoofd accountbeheer.</p>
            <a href="../index.php" class="btn">Terug naar Accountbeheer</a>
        </div>
    </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
