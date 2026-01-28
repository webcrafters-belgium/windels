<?php


require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';


session_start();
if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/account/login.php");
    exit;
}
require dirname($_SERVER['DOCUMENT_ROOT']).'/secure/onfact_helpers.inc.php';
$partner_id = $_SESSION['partner_id'];
$partner_naam = $_SESSION['bedrijf_naam'] ?? 'Partner';
$is_actief = 0;
// Logo ophalen
$current_logo = null;
$stmt = $mysqli->prepare("SELECT logo, email, is_actief FROM funeral_partners WHERE id = ?");
$stmt->bind_param('i', $partner_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $current_logo = $row['logo'];
    $is_actief = (int)$row['is_actief'];
    $partner_email = isset($row['email']) ? (string)$row['email'] : '';

}

// Pad naar het te tonen logo
$logo_src = $current_logo
    ? '/uploads/logos/' . htmlspecialchars($current_logo)
    : '/assets/images/logo-placeholder.png'; // standaardafbeelding



// Statistieken initialiseren
$stats_bestellingen_maand = 0;
$stats_in_productie = 0;
$stats_open_facturen = null;

/* 1. Bestellingen deze maand */
$stmt = $mysqli->prepare("
    SELECT COUNT(*) AS totaal
    FROM orders
    WHERE funeral_partner_id = ?
      AND created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
");
$stmt->bind_param('i', $partner_id);
$stmt->execute();
$stmt->bind_result($stats_bestellingen_maand);
$stmt->fetch();
$stmt->close();

/* 2. Bestellingen in productie
   -> pas de statussen aan naar wat jij gebruikt in je orders-tabel */
$stmt = $mysqli->prepare("
    SELECT COUNT(*) AS totaal
    FROM orders
    WHERE funeral_partner_id = ?
      AND status IN ('in_behandeling','in_productie','as_verzonden')
");
$stmt->bind_param('i', $partner_id);
$stmt->execute();
$stmt->bind_result($stats_in_productie);
$stmt->fetch();
$stmt->close();

$stats_open_facturen = getOpenInvoiceCountForPartner($partner_id, $mysqli, $config);

$laatste_bestellingen = [];
if(!empty($partner_email)){
  $stmt = $mysqli->prepare("SELECT id,order_number,created_at,status FROM orders WHERE funeral_partner_id=? ORDER BY created_at DESC LIMIT 5");
  $stmt->bind_param('i',$partner_id);
  $stmt->execute();
  $result_orders = $stmt->get_result();
  while($r = $result_orders->fetch_assoc()){ $laatste_bestellingen[] = $r; }
  $stmt->close();
}

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
}.dashboard-hero{text-align:center;background:#f4f6f4;padding:1.8rem 1.5rem;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.04);margin-bottom:1.5rem}
.dashboard-hero-avatar{width:80px;height:80px;border-radius:50%;overflow:hidden;margin:0 auto .8rem auto;border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.08)}
.dashboard-hero-avatar img{width:100%;height:100%;object-fit:cover}
.dashboard-hero h1{font-size:1.6rem;color:#2a5934;margin:0 0 .4rem}
.dashboard-hero p{color:#555;margin:0}

.dashboard-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:1rem;margin-bottom:1.8rem}
.stat-card{background:#fff;border-radius:12px;padding:1rem;box-shadow:0 2px 8px rgba(0,0,0,.04);text-align:center}
.stat-card h2{font-size:.95rem;margin-bottom:.25rem;color:#2a5934}
.stat-value{font-size:1.7rem;font-weight:700;color:#222}

.dashboard-main-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.2rem;margin-bottom:2rem}
.dashboard-card{background:#fff;border-radius:12px;padding:1.2rem;box-shadow:0 3px 10px rgba(0,0,0,.05);text-align:left}
.dashboard-card h3{font-size:1.05rem;margin-bottom:.4rem;color:#2a5934}
.dashboard-card p{font-size:.9rem;color:#555;margin-bottom:.9rem}
.logout-card{background:#fafafa}

.btn-outline{background:transparent;border:2px solid #1e4025;color:#1e4025}
.btn-outline:hover{background:#1e4025;color:#fff}

.dashboard-quick{text-align:center;margin-bottom:1.8rem}
.dashboard-quick h2{font-size:1.05rem;margin-bottom:.6rem;color:#2a5934}
.dashboard-quick-buttons{display:flex;flex-wrap:wrap;justify-content:center;gap:.5rem}
.btn-small{padding:.4rem .9rem;font-size:.85rem}

.dashboard-latest{background:#fff;border-radius:12px;padding:1.2rem;box-shadow:0 3px 10px rgba(0,0,0,.05)}
.dashboard-latest-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:.7rem}
.dashboard-latest-header h2{font-size:1rem;color:#2a5934;margin:0}

.dashboard-table{width:100%;border-collapse:separate;border-spacing:0;font-size:.9rem}
.dashboard-table thead th{background:#f4f6f4;padding:.55rem .7rem;color:#2a5934;font-weight:600;border-bottom:1px solid #e3e3e3; text-align:left}
.dashboard-table thead th:first-child{border-top-left-radius:10px}
.dashboard-table thead th:last-child{border-top-right-radius:10px}
.dashboard-table td{padding:.5rem .7rem;border-bottom:1px solid #eee;color:#444}
.dashboard-table tbody tr:hover{background:#fafdfb}
.text-right{text-align:right}

.status-badge{padding:.2rem .55rem;border-radius:999px;font-size:.8rem;font-weight:600;color:#fff;display:inline-block}
.status-aangemaakt{background:#0C5F36}
.status-as_verzonden{background:#9A631A}
.status-in_behandeling{background:#1B7A4E}
.status-klaar{background:#2E4A48}
.status-geannuleerd{background:#B3261E}

@media(max-width:768px){
  .dashboard-page{padding:2rem 1rem;margin:2rem 1rem}
  .dashboard-latest-header{flex-direction:column;align-items:flex-start;gap:.5rem}
}

</style>

<main class="dashboard-page container">
    <!-- Hero / welkom -->
    <section class="dashboard-hero">
        <div class="dashboard-hero-avatar">
            <img src="<?= $logo_src ?>" alt="Bedrijfslogo of standaardlogo">
        </div>
        <h1>Welkom,<br><?= htmlspecialchars($partner_naam) ?></h1>
        <p>Beheer hier eenvoudig al je bestellingen, facturen en accountgegevens.</p>
    </section>

    <!-- Stat tegels -->
    <section class="dashboard-stats">
        <div class="stat-card">
            <h2>Bestellingen deze maand</h2>
            <p class="stat-value"><?= (int)$stats_bestellingen_maand ?></p>
        </div>
        <div class="stat-card">
            <h2>In productie</h2>
            <p class="stat-value"><?= (int)$stats_in_productie ?></p>
        </div>
        <div class="stat-card">
            <h2>Openstaande facturen</h2>
            <p class="stat-value">
                <?= $stats_open_facturen === null ? 'n.v.t.' : (int)$stats_open_facturen ?>
            </p>
        </div>
    </section>

    <!-- Hoofdkaarten -->
    <section class="dashboard-main-cards">
        <?php if(!empty($partner_email) && $is_actief): ?>
            <article class="dashboard-card">
                <h3>Mijn Bestellingen</h3>
                <p>Bekijk en beheer alle geplaatste bestellingen of maak een bestelling aan.</p>
                <a href="orders/mijn_bestellingen.php" class="btn">Naar bestellingen</a>
            </article>
            <?php endif; ?>
            <article class="dashboard-card">
                <h3>Mijn Facturen</h3>
                <p>Toegang tot je maandoverzicht en facturen.</p>
                <a href="facturen/index.php" class="btn">Naar facturen</a>
            </article>
            <?php if(!empty($partner_email) && $is_actief): ?>
            <article class="dashboard-card">
                <h3>Accountbeheer</h3>
                <p>Wijzig je gegevens, logo of wachtwoord.</p>
                <a href="profiel/index.php" class="btn">Beheer account</a>
            </article>
            <article class="dashboard-card">
                <h3>Vragen &amp; info</h3>
                <p>Lees de meest gestelde vragen over as-aanlevering en personalisatie.</p>
                <a href="/pages/account/faq.php" class="btn">Naar FAQ</a>
            </article>
        <?php endif; ?>

        <article class="dashboard-card logout-card">
            <h3>Uitloggen</h3>
            <p>Beëindig je sessie veilig.</p>
            <a href="logout.php" class="btn btn-outline">Uitloggen</a>
        </article>
    </section>

    <!-- Snelle acties -->
    <?php if(!empty($partner_email) && $is_actief): ?>
    <section class="dashboard-quick">
        <h2>Snelle acties</h2>
        <div class="dashboard-quick-buttons">
            <a href="/pages/assortiment.php" class="btn btn-small">Nieuwe bestelling</a>
            <a href="/pages/contact/contact.php" class="btn btn-small">Contact &amp; ondersteuning</a>
        </div>
    </section>
    <?php endif; ?>

    <!-- Laatste bestellingen -->
    <?php if(!empty($partner_email) && !empty($laatste_bestellingen)): ?>
    <section class="dashboard-latest">
        <div class="dashboard-latest-header">
            <h2>Laatste bestellingen</h2>
            <a href="orders/mijn_bestellingen.php" class="btn btn-small">Alle bestellingen</a>
        </div>
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Ordernr.</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($laatste_bestellingen as $o): ?>
                <?php $status_class = 'status-'.strtolower($o['status']); ?>
                <tr>
                    <td><?= htmlspecialchars(date('d-m-Y',strtotime($o['created_at']))) ?></td>
                    <td><?= htmlspecialchars($o['order_number']) ?></td>
                    <td>
                        <span class="status-badge <?= $status_class ?>">
                            <?= htmlspecialchars($o['status']) ?>
                        </span>
                    </td>
                    <td class="text-right">
                        <a href="orders/view_order.php?id=<?= (int)$o['id'] ?>" class="btn btn-small">Details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <?php endif; ?>
</main>


<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
