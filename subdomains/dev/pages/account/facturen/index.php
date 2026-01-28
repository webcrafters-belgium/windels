<?php
session_start();
if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/account/login.php");
    exit;
}

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';

$partner_id = (int)$_SESSION['partner_id'];
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

$billing_preference = null;
$billing_recipient  = null;
$billing_weekday    = null;
$billing_month_day  = null;
$partner_vat        = null;

if ($stmt = $mysqli->prepare("
    SELECT billing_preference, billing_recipient, billing_weekday, billing_month_day, btw_nummer
    FROM funeral_partners
    WHERE id=? LIMIT 1
")) {
    $stmt->bind_param('i', $partner_id);
    if ($stmt->execute()) {
        $stmt->bind_result($bp, $br, $bw, $bm, $vat);
        if ($stmt->fetch()) {
            $billing_preference = $bp ?: null;
            $billing_recipient  = $br ?: null;
            $billing_weekday    = $bw !== null ? (int)$bw : null;
            $billing_month_day  = $bm !== null ? (int)$bm : null;
            $partner_vat        = $vat ?: null;
        }
    }
    $stmt->close();
}

if (!function_exists('e')) {
    function e(string $v): string {
        return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    }
}

$prefLabels = [
    'per_order' => 'Per order',
    'weekly'    => 'Wekelijks',
    'monthly'   => 'Maandelijks'
];
$recpLabels = [
    'partner'  => 'Uitvaartdienst',
    'customer' => 'Eindklant'
];
$weekdayNames = [
    1 => 'maandag',
    2 => 'dinsdag',
    3 => 'woensdag',
    4 => 'donderdag',
    5 => 'vrijdag',
    6 => 'zaterdag',
    7 => 'zondag'
];

$prefText = $prefLabels[$billing_preference] ?? 'Nog niet ingesteld';
$recpText = $recpLabels[$billing_recipient] ?? 'Nog niet ingesteld';

$detailText = '';
if ($billing_preference === 'weekly' && $billing_weekday) {
    $detailText = 'Factuur op '.($weekdayNames[$billing_weekday] ?? 'gekozen weekdag');
} elseif ($billing_preference === 'monthly' && $billing_month_day) {
    $detailText = 'Factuur rond dag '.$billing_month_day.' van de maand';
}

$partner_country = 'Onbekend';
if (!empty($partner_vat) && is_string($partner_vat)) {
    $vat = strtoupper(trim($partner_vat));
    if (str_starts_with($vat, 'BE')) {
        $partner_country = 'België';
    } elseif (str_starts_with($vat, 'NL')) {
        $partner_country = 'Nederland';
    }
}
?>
<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>
<style>
body{background:url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;background-size:cover;}
.dashboard-page{background-color:rgba(255,255,255,.9);padding:3rem 2rem;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);margin:3rem auto 2rem auto;}
.dashboard-welcome{background-color:#f4f6f4;padding:1.5rem 1.5rem;border-radius:8px;margin-bottom:1.5rem;text-align:center;box-shadow:0 4px 12px rgba(0,0,0,.04);}
.dashboard-welcome h1{margin-bottom:.5rem;font-size:1.4rem;color:#2a5934;}
.dashboard-welcome p{font-size:1rem;color:#444;margin-bottom:0;}
.info-box{background:#f5f5f5;border-left:4px solid #5a7d5a;padding:1rem 1.5rem;margin:1.5rem 0;border-radius:.5rem;box-shadow:0 1px 3px rgba(0,0,0,.05);font-size:.95rem;color:#333;}
.info-row{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.5rem;}
.info-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;background:#f1f5f4;border:1px solid #dcdcdc;font-size:.8rem;}
.dashboard-actions{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.25rem;margin-top:1.5rem;}
.dashboard-card{background:#fff;border-radius:10px;padding:1.25rem;border:1px solid #e8e8e8;box-shadow:0 2px 6px rgba(0,0,0,.03);display:flex;flex-direction:column;justify-content:space-between;}
.dashboard-card h2{font-size:1.1rem;margin:0 0 .35rem 0;color:#2a5934;}
.dashboard-card p{font-size:.95rem;margin:0 0 .9rem 0;color:#555;}
.btn{display:inline-block;padding:.55rem 1.1rem;border-radius:999px;background:#2a5934;color:#fff;text-decoration:none;font-size:.9rem;font-weight:600;border:none;cursor:pointer;transition:background .15s,box-shadow .15s,transform .05s;}
.btn:hover{background:#357647;box-shadow:0 4px 10px rgba(0,0,0,.12);}
.btn:active{transform:translateY(1px);}
@media(max-width:768px){.dashboard-page{padding:2rem 1.25rem;margin:2rem 1rem;}}
</style>

<main class="dashboard-page container">
    <div class="dashboard-welcome">
        <h1>Facturatie</h1>
        <p>Beheer hier je facturen, maandoverzichten en facturatie-instellingen.</p>
    </div>

    <div class="info-box">
        <strong>Huidige facturatie-instellingen</strong>
        <div class="info-row">
            <span class="info-badge">Voorkeur: <?= e($prefText) ?></span>
            <span class="info-badge">Ontvanger: <?= e($recpText) ?></span>
            <?php if ($detailText !== ''): ?>
                <span class="info-badge"><?= e($detailText) ?></span>
            <?php endif; ?>
            <span class="info-badge">Land (btw): <?= e($partner_country) ?></span>
        </div>
        <div style="margin-top:.4rem;font-size:.85rem;color:#555;">
            Pas deze instellingen aan via <strong>Factuur Instellingen</strong> wanneer je manier of frequentie van facturatie verandert.
        </div>
    </div>

    <div class="dashboard-actions">
    <?php if(!empty($partner_email) && $is_actief): ?>
        <div class="dashboard-card">
            <h2>Maandoverzicht</h2>
            <p>Bekijk een overzicht van alle bestellingen die voor jouw facturatieperiode meetellen (per order, per week of per maand).</p>
            <a href="maandoverzicht.php" class="btn">Bekijk overzicht</a>
        </div>
<?php endif; ?>
        <div class="dashboard-card">
            <h2>Mijn Facturen</h2>
            <p>Raadpleeg je facturen zoals opgemaakt in Onfact, download PDF’s en volg betalingen op.</p>
            <a href="facturen.php" class="btn">Facturen bekijken</a>
        </div>
        <?php if(!empty($partner_email) && $is_actief): ?>
        <div class="dashboard-card">
            <h2>Factuur Instellingen</h2>
            <p>Bepaal wie de factuur ontvangt (partner of eindklant) en hoe vaak er gefactureerd wordt.</p>
            <a href="FacturatieSetup.php" class="btn">Instellingen openen</a>
        </div>
        <?php endif; ?>
        <div class="dashboard-card">
            <h2>Terug naar Dashboard</h2>
            <p>Ga terug naar het hoofdmenu van je account en beheer andere onderdelen.</p>
            <a href="../dashboard.php" class="btn">Terug naar dashboard</a>
        </div>
    </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
