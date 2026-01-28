<?php
session_start();
if (!isset($_SESSION['partner_id'])) {
    header("Location: login.php");
    exit;
}

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

$partner_id = $_SESSION['partner_id'];
$partner_naam = $_SESSION['partner_username'] ?? 'Partner';
$is_actief = 0;
// Logo ophalen
$current_logo = null;
$stmt = $mysqli->prepare("SELECT logo, is_actief FROM funeral_partners WHERE id = ?");
$stmt->bind_param('i', $partner_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $current_logo = $row['logo'];
    $is_actief = (int)$row['is_actief'];
}

// Pad naar het te tonen logo
$logo_src = $current_logo
    ? '/uploads/logos/' . htmlspecialchars($current_logo)
    : '/assets/images/logo-placeholder.png'; // standaardafbeelding
?>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; ?>

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

.partner-logo-wrapper {
    display: inline-block;
    background: #fff;
    border: 1px solid #ddd;
    padding: 0.5rem;
    border-radius: 100px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    max-width: 120px;
    margin: 0 auto;
}

.partner-logo-wrapper img {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 100px;
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
        <div class="partner-logo-wrapper">
            <a href="/pages/account/profiel/logo_wijzigen.php"><img src="<?= $logo_src ?>" alt="Bedrijfslogo of standaardlogo"></a>
        </div>
        

        <h1>Welkom, <?= htmlspecialchars($partner_naam) ?></h1>
        <p>Beheer hier eenvoudig al je bestellingen, facturen en accountgegevens.</p>
    </div>
    <?php if (!$is_actief): ?>
    <div class="alert-deactivated">
        <strong>Opgelet:</strong> Dit account is gedeactiveerd.<br>
        De samenwerking is (tijdelijk) stopgezet. Bestellen is niet meer mogelijk.
    </div>
<?php endif; ?>
    <div class="dashboard-actions">
        <?php if ($is_actief): ?>
        <div class="dashboard-card">
            <h2>Mijn Bestellingen</h2>
            <p>Bekijk en beheer alle geplaatste bestellingen of maak een bestelling aan.</p>
            <a href="orders/index.php" class="btn">Naar bestellingen</a>
        </div>
        <?php endif; ?>
        <div class="dashboard-card">
            <h2>Mijn Facturen</h2>
            <p>Toegang tot je maandoverzicht en facturen.</p>
            <a href="facturen/index.php" class="btn">Naar facturen</a>
        </div>
        <div class="dashboard-card">
            <h2>Accountbeheer</h2>
            <p>Wijzig je gegevens, logo of wachtwoord.</p>
            <a href="profiel/index.php" class="btn">Beheer account</a>
        </div>
        <div class="dashboard-card">
            <h2>Uitloggen</h2>
            <p>Beëindig je sessie veilig.</p>
            <a href="logout.php" class="btn">Uitloggen</a>
        </div>
    </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
