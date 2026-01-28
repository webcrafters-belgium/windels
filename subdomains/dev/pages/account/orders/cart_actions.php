<?php
// Start sessie + laad ini (DB, sessie, etc.)

include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';
session_start();
// Alleen voor ingelogde partners
if (empty($_SESSION['partner_id'])) {
  header("Location: /pages/account/login.php");
  exit;
}
$partner_id = (int)$_SESSION['partner_id'];

// Kies DB-handle ($mysqli_medewerkers prefereren)
$DB = null;
if (isset($mysqli_medewerkers) && $mysqli_medewerkers instanceof mysqli) {
  $DB = $mysqli_medewerkers;
} elseif (isset($mysqli) && $mysqli instanceof mysqli) {
  $DB = $mysqli;
}
if (!$DB) {
  http_response_code(500);
  die('DB-verbinding ontbreekt (mysqli).');
}

// CSRF + methode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /pages/account/orders/cart.php'); exit; }
if (empty($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
  http_response_code(400); die('Ongeldige aanvraag (CSRF).');
}

$CART_URL = '/pages/account/orders/cart.php';
$action   = $_POST['action'] ?? '';

// Cart ophalen/aanmaken voor deze partner
function get_or_create_cart_id(mysqli $db, int $partner_id): int {
  $cart_id = 0;
  if ($st = $db->prepare("SELECT id FROM carts WHERE partner_id=?")) {
    $st->bind_param('i', $partner_id);
    $st->execute(); $st->bind_result($cart_id); $st->fetch(); $st->close();
  }
  if (!$cart_id && ($st = $db->prepare("INSERT INTO carts(partner_id) VALUES(?)"))) {
    $st->bind_param('i', $partner_id);
    $st->execute(); $cart_id = $st->insert_id; $st->close();
  }
  return (int)$cart_id;
}
$cart_id = get_or_create_cart_id($DB, $partner_id);

// Helper redirect
function back(string $url){ header('Location: '.$url); exit; }

// Column-detectie (éénmalig per request)
function table_has_column(mysqli $db, string $table, string $column): bool {
  $table = $db->real_escape_string($table);
  $column = $db->real_escape_string($column);
  $q = $db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
  if ($q) { $ok = (bool)$q->num_rows; $q->close(); return $ok; }
  return false;
}
$HAS_VARIANT_META = table_has_column($DB, 'cart_items', 'variant_meta');
$HAS_VARIANT_KEY  = table_has_column($DB, 'cart_items', 'variant_key');

// ===== Acties =====
if ($action === 'add') {
  // Normalisatie + whitelist
  $map   = ['epoxy'=>'epoxy','kaars'=>'kaars','inkoop'=>'inkoop'];
  $ptype = $map[$_POST['product_type'] ?? ''] ?? '';
  $pid   = (int)($_POST['product_id'] ?? 0);
  $name  = trim((string)($_POST['name'] ?? ''));
  $price = (float)($_POST['unit_price'] ?? 0);
  $qty   = max(1, (int)($_POST['qty'] ?? 1));

  if (!$ptype || $pid <= 0 || $price <= 0 || $name === '') {
    back($CART_URL);
  }

  // ===== Variants parsen (color + checkbox opties) =====
  $variant_color   = null;
  if (isset($_POST['variant']) && is_array($_POST['variant'])) {
    $variant_color = isset($_POST['variant']['color']) ? trim((string)$_POST['variant']['color']) : null;
    if ($variant_color === '') { $variant_color = null; }
  }
  $variant_options = [];
  if (!empty($_POST['variant_options']) && is_array($_POST['variant_options'])) {
    foreach ($_POST['variant_options'] as $opt) {
      $v = trim((string)$opt);
      if ($v !== '') { $variant_options[] = $v; }
    }
    $variant_options = array_values(array_unique($variant_options));
  }

  // Meta JSON
  $meta = [];
  if ($variant_color !== null) { $meta['color'] = $variant_color; }
  if (!empty($variant_options)) { $meta['options'] = $variant_options; }
  $variant_meta_json = !empty($meta) ? json_encode($meta, JSON_UNESCAPED_UNICODE) : null;

  // Als jouw tabel deze kolommen NIET heeft, slaan we ze automatisch over
  $HAS_VARIANT_META = table_has_column($DB, 'cart_items', 'variant_meta');
  $HAS_VARIANT_KEY  = table_has_column($DB, 'cart_items', 'variant_key');

  // Optioneel: leesbaarheid in naam als variant_meta kolom ontbreekt
  if (!$HAS_VARIANT_META) {
    $pretty = [];
    if ($variant_color)    { $pretty[] = "kleur: ".$variant_color; }
    if ($variant_options)  { $pretty[] = "opties: ".implode(', ', $variant_options); }
    if ($pretty) { $name .= ' ('.implode(' | ', $pretty).')'; }
  }

  // ===== ALTIJD NIEUWE RIJ: uitsluitend INSERT, geen UPSERT =====
  if ($HAS_VARIANT_META && $HAS_VARIANT_KEY) {
    // --- Altijd een deterministische variant_key (varchar(64) NOT NULL) ---
    $norm = ['ptype'=>$ptype,'pid'=>$pid];
    if ($variant_color !== null) { $norm['color'] = $variant_color; }
    if (!empty($variant_options)) {
      sort($variant_options, SORT_NATURAL|SORT_FLAG_CASE);
      $norm['options'] = $variant_options;
    }
    // 64-tekens hex (sha256) past in varchar(64)
    $variant_key = hash('sha256', json_encode($norm, JSON_UNESCAPED_UNICODE));

    $sql = "INSERT INTO cart_items
              (cart_id, product_type, product_id, name, unit_price, qty, variant_meta, variant_key, created_at, updated_at)
            VALUES (?,?,?,?,?,?,?,?,NOW(),NOW())";
    if ($st = $DB->prepare($sql)) {
      // i s i s d i s s
      $st->bind_param('isisdiss',
        $cart_id, $ptype, $pid, $name, $price, $qty, $variant_meta_json, $variant_key
      );
      $st->execute(); $st->close();
    }
  } elseif ($HAS_VARIANT_META && !$HAS_VARIANT_KEY) {
    $sql = "INSERT INTO cart_items
              (cart_id, product_type, product_id, name, unit_price, qty, variant_meta, created_at, updated_at)
            VALUES (?,?,?,?,?,?,?,NOW(),NOW())";
    if ($st = $DB->prepare($sql)) {
      // i s i s d i s
      $st->bind_param('isisdis',
        $cart_id, $ptype, $pid, $name, $price, $qty, $variant_meta_json
      );
      $st->execute(); $st->close();
    }
  } else {
    $sql = "INSERT INTO cart_items
              (cart_id, product_type, product_id, name, unit_price, qty, created_at, updated_at)
            VALUES (?,?,?,?,?,?,NOW(),NOW())";
    if ($st = $DB->prepare($sql)) {
      // i s i s d i
      $st->bind_param('isisdi', $cart_id, $ptype, $pid, $name, $price, $qty);
      $st->execute(); $st->close();
    }
  }

  back($CART_URL);
}


if ($action === 'bulk_update') {
  // Verwijderen (met ownership check)
  if (!empty($_POST['remove_id'])) {
    $rid = (int)$_POST['remove_id'];
    if ($st = $DB->prepare("DELETE FROM cart_items
                             WHERE id=? AND cart_id IN (SELECT id FROM carts WHERE id=? AND partner_id=?)")) {
      $st->bind_param('iii', $rid, $cart_id, $partner_id);
      $st->execute(); $st->close();
    }
  }

  // Aantallen bijwerken (met ownership check)
  if (!empty($_POST['qty']) && is_array($_POST['qty'])) {
    foreach ($_POST['qty'] as $id => $q) {
      $id = (int)$id; $q = max(1, (int)$q);
      if ($st = $DB->prepare("UPDATE cart_items
                               SET qty=?, updated_at=NOW()
                               WHERE id=? AND cart_id IN (SELECT id FROM carts WHERE id=? AND partner_id=?)")) {
        $st->bind_param('iiii', $q, $id, $cart_id, $partner_id);
        $st->execute(); $st->close();
      }
    }
  }
  back($CART_URL);
}

if ($action === 'clear') {
  if ($st = $DB->prepare("DELETE FROM cart_items WHERE cart_id IN (SELECT id FROM carts WHERE id=? AND partner_id=?)")) {
    $st->bind_param('ii', $cart_id, $partner_id);
    $st->execute(); $st->close();
  }
  back($CART_URL);
}

// Default fallback
back($CART_URL);
