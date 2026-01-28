<?php

include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';
session_start();
// Alleen voor ingelogde uitvaartdiensten
if (!isset($_SESSION['partner_id'])) {  header("Location: /pages/account/login.php"); exit; }

/* ====== Helpers ====== */
function decryptFieldNullable(?string $data, string $key): ?string {
    if ($data === null || $data === '') { return null; }
    $bin = base64_decode($data, true);
    if ($bin === false || strlen($bin) < 17) { return null; } // 16B IV + data
    $iv = substr($bin, 0, 16);
    $encrypted = substr($bin, 16);
    $out = openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    return ($out === false) ? null : $out;
}
function dieSafe($msg){ http_response_code(400); die($msg); }
function has_col(mysqli $db, string $table, string $column): bool {
    $t = $db->real_escape_string($table);
    $c = $db->real_escape_string($column);
    if ($q = $db->query("SHOW COLUMNS FROM `$t` LIKE '$c'")) {
        $ok = (bool)$q->num_rows; $q->close(); return $ok;
    }
    return false;
}
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

/* ====== Input ====== */
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) { dieSafe("Ongeldig order."); }

/* ====== Order + klant ====== */
$sql = "
    SELECT o.id, o.order_number, o.created_at, o.status,
           op.klant_naam, op.klant_email, op.klant_telefoon, op.klant_adres,
           op.klantnummer_partner, op.partner_opmerking
    FROM orders o
    LEFT JOIN order_private op ON o.id = op.order_id
    WHERE o.id = ? AND o.funeral_partner_id = ?
";

if (!$stmt = $mysqli->prepare($sql)) { dieSafe("Fout voorbereiden query (order)."); }
if (!$stmt->bind_param('ii', $order_id, $_SESSION['partner_id'])) { dieSafe("Fout binden parameters (order)."); }
if (!$stmt->execute()) { dieSafe("Fout uitvoeren query (order)."); }
$result = $stmt->get_result();
$order = $result ? $result->fetch_assoc() : null;
$stmt->close();

if (!$order) { dieSafe("Bestelling niet gevonden."); }

// Decrypt klantgegevens
$order['klant_naam']     = decryptFieldNullable($order['klant_naam']    ?? null, $encryption_key) ?? '-';
$order['klant_email']    = decryptFieldNullable($order['klant_email']   ?? null, $encryption_key) ?? '-';
$order['klant_telefoon'] = decryptFieldNullable($order['klant_telefoon']?? null, $encryption_key) ?? '-';
$order['klant_adres']    = decryptFieldNullable($order['klant_adres']   ?? null, $encryption_key) ?? '-';
$order['klantnummer_partner'] = decryptFieldNullable($order['klantnummer_partner'] ?? null, $encryption_key) ?? '-';
$order['partner_opmerking']    = decryptFieldNullable($order['partner_opmerking']    ?? null, $encryption_key) ?? '';

/* ====== Orderregels (met varianten) ====== */
// Dynamisch kolommen meenemen indien aanwezig
$has_pname = has_col($mysqli, 'order_products', 'product_name');
$has_price = has_col($mysqli, 'order_products', 'unit_price');
$has_vmeta = has_col($mysqli, 'order_products', 'variant_meta');

$select_cols = ['product_id','quantity'];
if ($has_pname) $select_cols[] = 'product_name';
if ($has_price) $select_cols[] = 'unit_price';
if ($has_vmeta) $select_cols[] = 'variant_meta';

$sql = "SELECT ".implode(',', $select_cols)." FROM order_products WHERE order_id = ?";
if (!$stmt = $mysqli->prepare($sql)) { dieSafe("Fout voorbereiden query (regels)."); }
if (!$stmt->bind_param('i', $order_id)) { dieSafe("Fout binden parameters (regels)."); }
if (!$stmt->execute()) { dieSafe("Fout uitvoeren query (regels)."); }
$result = $stmt->get_result();
$orderItems = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$stmt->close();

/* ====== Producten ophalen / verrijken ====== */
$producten = [];
foreach ($orderItems as $item) {
    $id  = (int)$item['product_id'];
    $qty = (int)$item['quantity'];

    // Indien order_products.product_name/price bestaan -> gebruik die als bron
    $name_from_order  = $has_pname ? (string)($item['product_name'] ?? '') : '';
    $price_from_order = $has_price ? (float)($item['unit_price'] ?? 0) : null;

    // Variant_meta parsen
    $variant_meta = null;
    if ($has_vmeta && isset($item['variant_meta']) && $item['variant_meta'] !== null && $item['variant_meta'] !== '') {
        $dec = json_decode($item['variant_meta'], true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($dec)) { $variant_meta = $dec; }
    }

    $gevonden = false;

    // 1) Epoxy
    $sql = "
 SELECT 
    e.title, 
    e.total_product_price,
    e.margin,
    pa.gram AS gram_per_stuk
 FROM epoxy_products e
 LEFT JOIN product_as pa ON pa.product_id = e.id
 WHERE e.id = ? AND e.sub_category = 'uitvaart'
";

    if ($stmt = $mysqli_medewerkers->prepare($sql)) {
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                 // partnerprijs via marge berekenen
                $basePrice = (float)$row['total_product_price'];      // particulier incl. btw
                $marginPct = isset($row['margin']) ? (float)$row['margin'] : 0.0;

                $priceExVat = $basePrice / 1.21;
                $markup     = $marginPct / 100;

                if ($markup <= -1) {
                    // safety fallback
                    $partnerPriceExVat = $priceExVat;
                } else {
                    $productCost  = $priceExVat / (1 + $markup);
                    $marginAmount = $priceExVat - $productCost;
                    $halfMargin   = $marginAmount /$percentageuitvaart;

                    $partnerPriceExVat = $productCost + $halfMargin;
                }

                // partners: altijd incl. 21% btw
                $partnerPrice = $partnerPriceExVat * 1.21;
                $producten[] = [
                    'name'          => $name_from_order !== '' ? $name_from_order : (string)$row['title'],
                    'price' => $partnerPrice,
                    'quantity'      => $qty,
                    'category'      => 'epoxy',
                    'gram_per_stuk' => $row['gram_per_stuk'] !== null ? (float)$row['gram_per_stuk'] : null,
                    'variant_meta'  => $variant_meta
                ];
                $gevonden = true;
            }
        }
        $stmt->close();
    }
    if ($gevonden) continue;

    //* 2) Kaarsen
    $sql = "SELECT title, total_product_price, margin FROM kaarsen_products WHERE id = ? AND sub_category = 'uitvaart' LIMIT 1";
    if ($stmt = $mysqli_medewerkers->prepare($sql)) {
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $basePrice = (float)$row['total_product_price'];      // particulier incl. btw
                $marginPct = isset($row['margin']) ? (float)$row['margin'] : 0.0;

                $priceExVat = $basePrice / 1.21;
                $markup     = $marginPct / 100;

                if ($markup <= -1) {
                    // safety fallback
                    $partnerPriceExVat = $priceExVat;
                } else {
                    $productCost  = $priceExVat / (1 + $markup);
                    $marginAmount = $priceExVat - $productCost;
                    $halfMargin   = $marginAmount /$percentageuitvaart;

                    $partnerPriceExVat = $productCost + $halfMargin;
                }

                // partners: altijd incl. 21% btw
                $partnerPrice = $partnerPriceExVat * 1.21;
                $producten[] = [
                    'name'          => $name_from_order !== '' ? $name_from_order : (string)$row['title'],
                    'price' => $partnerPrice,
                    'quantity'      => $qty,
                    'category'      => 'kaarsen',
                    'gram_per_stuk' => null,
                    'variant_meta'  => $variant_meta
                ];
                $gevonden = true;
            }
        }
        $stmt->close();
    }
    if ($gevonden) continue;

    // 3) Inkoop
    $sql = "SELECT title, total_product_price, margin FROM inkoop_products WHERE id = ? AND sub_category = 'uitvaart' LIMIT 1";
    if ($stmt = $mysqli_medewerkers->prepare($sql)) {
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $basePrice = (float)$row['total_product_price'];      // particulier incl. btw
                $marginPct = isset($row['margin']) ? (float)$row['margin'] : 0.0;

                $priceExVat = $basePrice / 1.21;
                $markup     = $marginPct / 100;

                if ($markup <= -1) {
                    // safety fallback
                    $partnerPriceExVat = $priceExVat;
                } else {
                    $productCost  = $priceExVat / (1 + $markup);
                    $marginAmount = $priceExVat - $productCost;
                    $halfMargin   = $marginAmount /$percentageuitvaart;

                    $partnerPriceExVat = $productCost + $halfMargin;
                }

                // partners: altijd incl. 21% btw
                $partnerPrice = $partnerPriceExVat * 1.21;
                $producten[] = [
                    'name'          => $name_from_order !== '' ? $name_from_order : (string)$row['title'],
                    'price' => $partnerPrice,
                    'quantity'      => $qty,
                    'category'      => 'inkoop',
                    'gram_per_stuk' => null,
                    'variant_meta'  => $variant_meta
                ];
                $gevonden = true;
            }
        }
        $stmt->close();
    }

    // 4) Fallback
    if (!$gevonden) {
        $producten[] = [
            'name'          => ($name_from_order !== '' ? $name_from_order : "Product #$id"),
            'price'         => $price_from_order !== null ? $price_from_order : 0.0,
            'quantity'      => $qty,
            'category'      => 'onbekend',
            'gram_per_stuk' => null,
            'variant_meta'  => $variant_meta
        ];
    }
}

include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';
?>
<style>
body{background:url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;background-size:cover}
.orderview{
  background-color:rgba(255,255,255,.9);
  padding:3rem 2rem;
  border-radius:12px;
  box-shadow:0 4px 10px rgba(0,0,0,.05);
  margin:3rem auto 2rem
}
form>.btn{ padding: 12px; }

/* Variant weergave */
.variant-mini{margin:.25rem 0 0;font-size:12px;color:#444}
.variant-chip{display:inline-block;background:#eef7f3;border:1px solid #cbe6d9;color:#004d36;border-radius:999px;padding:.1rem .45rem;margin-right:6px;margin-top:4px}
.variant-color{display:inline-flex;align-items:center;gap:6px;margin-top:4px}
.variant-color .swatch{display:inline-block;width:12px;height:12px;border-radius:50%;border:1px solid rgba(0,0,0,.35)}
table{width:100%;border-collapse:collapse}
th,td{padding:10px;border-bottom:1px solid #eee;text-align:left;vertical-align:top}
.note{border:1px solid #e5e7eb;background:#fafafa;padding:10px;border-radius:8px;color:#374151}
.orderview h3{
    margin-top: 1.5rem;
}
.btn-group{
    margin-top: 1.5rem;
}
</style>

<main>
  <div class="orderview">
    <h2>Bestelling <?= h($order['order_number']) ?></h2>
    <p><strong>Datum:</strong> <?= h($order['created_at']) ?></p>

    <h3>Klantgegevens</h3>
    <p>
      <strong>Klantnummer (uitvaartdienst):</strong> <?= h($order['klantnummer_partner'] ?? '-') ?><br>
      <strong>Naam:</strong> <?= h($order['klant_naam']) ?><br>
      <strong>Email:</strong> <?= h($order['klant_email']) ?><br>
      <strong>Telefoon:</strong> <?= h($order['klant_telefoon']) ?><br>
      <strong>Adres:</strong><br><?= nl2br(h($order['klant_adres'])) ?>
    </p>

    <?php if ($order['partner_opmerking'] !== ''): ?>
    <h3>Opmerking van partner</h3>
    <div class="note"><?= nl2br(h($order['partner_opmerking'])) ?></div>
    <?php endif; ?>

    <h3>Bestelde producten</h3>
    <table>
      <thead>
        <tr>
          <th style="text-align:left;">Product</th>
          <th>Aantal</th>
          <th>Prijs (per stuk)</th>
          <th>Totaal</th>
        </tr>
      </thead>
      <tbody>
        <?php $totaal = 0.0;
        foreach ($producten as $p):
          $prijs = (float)$p['price'];
$aantal = (int)$p['quantity'];
$subtotaal = $prijs * $aantal;
$totaal += $subtotaal;


          // Variant HTML
          $variantHtml = '';
          if (!empty($p['variant_meta']) && is_array($p['variant_meta'])) {
            $parts = [];

            // Kleur met swatch (alleen veilige HEX tonen als swatch)
            if (!empty($p['variant_meta']['color'])) {
              $raw = (string)$p['variant_meta']['color'];
              $hex = trim($raw);
              if (preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $hex)) {
                $hexEsc = h($hex);
                $parts[] = '<span class="variant-color"><span class="swatch" style="background:'.$hexEsc.'"></span>'.$hexEsc.'</span>';
              } else {
                $parts[] = 'Kleur: '.h($raw);
              }
            }

            // Opties als chipjes
            if (!empty($p['variant_meta']['options']) && is_array($p['variant_meta']['options'])) {
              $chips = '';
              foreach ($p['variant_meta']['options'] as $op) {
                $chips .= '<span class="variant-chip">'.h($op).'</span>';
              }
              if ($chips !== '') { $parts[] = $chips; }
            }

            if ($parts) {
              $variantHtml = '<div class="variant-mini">'.implode(' ', $parts).'</div>';
            }
          }
        ?>
          <tr>
            <td><?= h($p['name']) ?><?= $variantHtml ?></td>
            <td style="text-align:center;"><?= $aantal ?></td>
            <td style="text-align:center;"><sup>€</sup><?= number_format($prijs, 2, ',', '.') ?></td>
            <td style="text-align:center;"><sup>€</sup><?= number_format($subtotaal, 2, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3" style="text-align:right;">Totaal:</th>
          <th><sup>€</sup><?= number_format($totaal, 2, ',', '.') ?></th>
        </tr>
      </tfoot>
    </table>

    <?php
    // AS-OVERZICHT opbouwen
    $asRows = [];
    $totaalAsGram = 0.0;
    $nr = 1;

    foreach ($producten as $p) {
      if (($p['category'] ?? null) === 'epoxy' && $p['gram_per_stuk'] !== null) {
        $g = (float)$p['gram_per_stuk'];
        $q = (int)$p['quantity'];
        $t = $g * $q;
        $totaalAsGram += $t;

        $asRows[] = [
          'nr'            => $nr++,
          'product'       => $p['name'],
          'gram_per_stuk' => $g,
          'aantal'        => $q,
          'totaal'        => $t,
        ];
      }
    }
    ?>

    <?php if (!empty($asRows)): ?>
      <h3>As-overzicht (epoxy)</h3>
      <table>
        <thead>
          <tr>
            <th style="text-align:left;">#</th>
            <th style="text-align:left;">Product</th>
            <th style="text-align:center;">Gram/stuk</th>
            <th style="text-align:center;">Aantal</th>
            <th style="text-align:center;">Totaal gram</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($asRows as $r): ?>
            <tr>
              <td><?= (int)$r['nr'] ?></td>
              <td><?= h($r['product']) ?></td>
              <td style="text-align:center;"><?= round((float)$r['gram_per_stuk']) ?></td>
              <td style="text-align:center;"><?= (int)$r['aantal'] ?></td>
              <td style="text-align:center;"><?= round((float)$r['totaal']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="4" style="text-align:right;">Totaal te leveren as</th>
            <th style="text-align:center;"><?= round((float)$totaalAsGram) ?> g</th>
          </tr>
        </tfoot>
      </table>
    <?php else: ?>
      <p><em>Geen epoxyproducten met geregistreerde as-hoeveelheid gevonden.</em></p>
    <?php endif; ?>

    <h3>Status</h3>
    <?php 
      $status = (string)($order['status'] ?? 'onbekend');
      $status_class = 'status-' . $status;
      $status_label = ucfirst(str_replace('_', ' ', $status));
    ?>
    <p><strong>Huidige status:</strong>
      <span class="status-label <?= h($status_class) ?>"><?= h($status_label) ?></span>
    </p>
    <div class="btn-group">
        <a href="/pages/account/orders/pdf/pakbon_<?= (int)$order['id'] ?>.pdf" target="_blank" class="btn" download>📄 Pakbon downloaden</a>
    
        <?php if ($status === 'aangemaakt'): ?>
        <form method="post" action="update_order_status.php" style="display:inline-block;" onsubmit="return confirm('Weet je zeker dat je deze bestelling wilt annuleren?');">
            <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
            <input type="hidden" name="status" value="geannuleerd">
            <button type="submit" class="btn btn-danger">Bestelling annuleren</button>
        </form>

        <form method="post" action="update_order_status.php" style="display:inline-block;" onsubmit="return confirm('Bevestig dat de as is verzonden.');">
            <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
            <input type="hidden" name="status" value="as_verzonden">
            <button type="submit" class="btn btn-primary">Markeer als 'As verzonden'</button>
        </form>
        <?php endif; ?>

        <p><a href="mijn_bestellingen.php" class="btn">← Terug naar overzicht</a></p>
    </div>
  </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
