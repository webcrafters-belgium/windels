<?php
/**
 * /pages/account/orders/bevestiging.php
 * Toont orderbevestiging voor account-flow (zonder betaling).
 * - Haalt ordernummer uit DB
 * - Haalt pakbon-pad uit order_file_refs
 * - Toont downloadlink wanneer bestand bestaat
 */

session_start();
include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';

// Alleen voor ingelogde uitvaartdiensten
if (empty($_SESSION['partner_id'])) {
  header("Location: /pages/account/login.php");
  exit;
}
$partner_id = (int)$_SESSION['partner_id'];

// order_id uit query
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($order_id <= 0) {
  header("Location: /pages/account/dashboard.php");
  exit;
}

// DB check + ordernummer ophalen
$order_number = null;
if (isset($mysqli) && $mysqli instanceof mysqli) {
  // Order moet van deze partner zijn
  $stmt = $mysqli->prepare("SELECT order_number FROM orders WHERE id=? AND funeral_partner_id=? LIMIT 1");
  $stmt->bind_param('ii', $order_id, $partner_id);
  $stmt->execute();
  $stmt->bind_result($order_number_db);
  $ok = $stmt->fetch();
  $stmt->close();

  if ($ok && $order_number_db) {
    $order_number = $order_number_db;
  } else {
    // Niet gevonden of niet van deze partner
    header("Location: /pages/account/dashboard.php");
    exit;
  }
} else {
  // DB niet beschikbaar → eenvoudige fallback (zou eigenlijk niet moeten gebeuren)
  $order_number = "ORD-".date('Y')."-".str_pad((string)$order_id, 4, '0', STR_PAD_LEFT);
}

// Pakbon-pad uit order_file_refs
$pakbonFs = null;
if (isset($mysqli) && $mysqli instanceof mysqli) {
  // Tabel garanderen (idempotent)
  $mysqli->query("CREATE TABLE IF NOT EXISTS `order_file_refs`(
    `order_id` INT PRIMARY KEY,
    `pakbon_path`  VARCHAR(512) NOT NULL,
    `factuur_path` VARCHAR(512) NULL,
    `ubl_path`     VARCHAR(512) NULL,
    `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_order_file_refs_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

  $stmt = $mysqli->prepare("SELECT pakbon_path FROM order_file_refs WHERE order_id=? LIMIT 1");
  $stmt->bind_param('i', $order_id);
  $stmt->execute();
  $stmt->bind_result($pakbon_path_db);
  if ($stmt->fetch()) {
    $pakbonFs = $pakbon_path_db ?: null;
  }
  $stmt->close();
}

// Helper: FS-pad → URL-pad (relatief)
function fsPathToUrl(?string $fsPath): ?string {
  if (!$fsPath) return null;
  $doc = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
  $fs  = str_replace('\\','/',$fsPath);
  if ($doc && str_starts_with($fs, $doc)) {
    $rel = substr($fs, strlen($doc)); // begint met '/'
    return $rel ?: null;
  }
  // Als het al een URL-pad lijkt (begint met '/')
  if (str_starts_with($fs, '/')) return $fs;
  return null;
}

// Is er een echte pakbon?
$pakbonUrl = null;
if ($pakbonFs && is_file($pakbonFs)) {
  $pakbonUrl = fsPathToUrl($pakbonFs);
} else {
  // Fallback: oud padpatroon (alleen tonen als bestand bestaat)
  $fallbackFs = $_SERVER['DOCUMENT_ROOT'].'/pages/account/orders/pdf/pakbon_'.$order_id.'.pdf';
  if (is_file($fallbackFs)) {
    $pakbonUrl = '/pages/account/orders/pdf/pakbon_'.$order_id.'.pdf';
  }
}
?>
<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; ?>
<style>
body {
  background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
  background-size: cover;
}
.bevestiging-container {
  background-color: rgba(255, 255, 255, 0.9);
  padding: 3rem 2rem;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  margin: 3rem auto 2rem auto;
}
.btn {
  display:inline-block; padding:.8rem 1.2rem; border-radius:8px;
  background:#2d4739; color:#fff; text-decoration:none; font-weight:600;
}
.btn:focus, .btn:hover { opacity:.9; }
.notice { margin-top:1rem; color:#444; }
</style>

<main class="bevestiging-container">
  <h2>Bestelling succesvol geplaatst</h2>
  <p>Uw bestelling is geregistreerd met ordernummer:</p>
  <h3><?= htmlspecialchars($order_number) ?></h3>

  <p class="notice">
    De klantgegevens zijn alleen zichtbaar voor uw uitvaartdienst en worden niet gedeeld met ons.
  </p>

  <div style="margin: 2rem 0;">
    <?php if ($pakbonUrl): ?>
      <a href="<?= htmlspecialchars($pakbonUrl) ?>" class="btn" target="_blank" download>
        📄 Pakbon downloaden
      </a>
    <?php else: ?>
      <em>De pakbon is nog niet beschikbaar. Vernieuw deze pagina later of neem contact op indien dit aanhoudt.</em>
    <?php endif; ?>
  </div>

  <a href="/pages/account/dashboard.php" class="btn">Terug naar dashboard</a>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
