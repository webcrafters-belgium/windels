<?php
session_start();
include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc'; // voor $mysqli

// --- helpers ---
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function fsToWebPath(string $fsPath): string {
  $root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
  return preg_replace('#^'.preg_quote($root, '#').'#', '', $fsPath) ?: $fsPath;
}

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($order_id <= 0) {
  http_response_code(400);
  die('Ongeldige order.');
}

// --- ordernummer ophalen ---
$order_number = null;
$stmt = $mysqli->prepare("SELECT order_number FROM orders WHERE id=? LIMIT 1");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$stmt->bind_result($order_number);
$stmt->fetch();
$stmt->close();

if (!$order_number) {
  // Fallback (zou zelden nodig moeten zijn)
  $order_number = "ORD-".date('Y')."-".str_pad((string)$order_id, 4, '0', STR_PAD_LEFT);
}

// --- pakbon-pad ophalen uit DB (order_file_refs) ---
$pakbonFsPath = null;
$stmt = $mysqli->prepare("SELECT pakbon_path FROM order_file_refs WHERE order_id=? LIMIT 1");
$stmt->bind_param('i', $order_id);
$stmt->execute();
$stmt->bind_result($pakbonFsPath);
$stmt->fetch();
$stmt->close();

// Fallback: ouder pad-formaat als er nog geen rij in order_file_refs is
if (!$pakbonFsPath) {
  $pakbonFsPath = $_SERVER['DOCUMENT_ROOT'].'/pages/orders/pdf/pakbon_'.$order_id.'.pdf';
}

// Bestaat het bestand echt?
$pakbonUrl = (is_string($pakbonFsPath) && is_file($pakbonFsPath)) ? fsToWebPath($pakbonFsPath) : null;
?>
<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; ?>
<style>
body{background:url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;background-size:cover}
.bevestiging-container{background-color:rgba(255,255,255,.9);padding:3rem 2rem;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);margin:3rem auto 2rem}
a.bevestiging{color:#2f4f4f}
.btn{display:inline-block;background:#2d4739;color:#fff;padding:.75rem 1.25rem;border-radius:8px;text-decoration:none}
.btn[aria-disabled="true"]{opacity:.6;pointer-events:none}
</style>

<main class="bevestiging-container">
  <h2>Bestelling succesvol geplaatst</h2>
  <p>Uw bestelling is geregistreerd met ordernummer:</p>
  <h3><?= h($order_number) ?></h3>

  <p><strong>Belangrijk voor particuliere klanten:</strong><br>
    Voor het vervaardigen van uw herinneringsproducten hebben wij de as nodig. U kunt de as op twee manieren bezorgen:
  </p>
  <ul>
    <li>De as persoonlijk bij ons in de winkel afgeven.</li>
    <li>Een afspraak maken via <a href="mailto:info@windelsgreen-decoresin.com" class="bevestiging">info@windelsgreen-decoresin.com</a>
      zodat wij de as bij u thuis komen ophalen (binnen een straal van 50 km rond onze winkel).</li>
  </ul>

  <p>Zodra wij de as ontvangen hebben, starten we de productie van uw bestelling.</p>
  <p>U ontvangt zo dadelijk ook een bevestigingsmail met uw factuur en pakbon in bijlage.</p>

  <div style="margin:2rem 0">
    <?php if ($pakbonUrl): ?>
      <a href="<?= h($pakbonUrl) ?>" class="btn" target="_blank" download>📄 Pakbon downloaden</a>
    <?php else: ?>
      <a class="btn" aria-disabled="true">📄 Pakbon wordt gegenereerd…</a>
    <?php endif; ?>
  </div>

  <a href="/" class="btn">Terug naar home</a>
</main>
<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
