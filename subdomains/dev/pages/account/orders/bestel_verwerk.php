<?php
/**
 * /pages/account/orders/bestel_verwerk.php
 * Verwerken van account-checkout ZONDER betaling.
 * - Slaat order op (orders, order_private, order_products)
 * - Maakt pakbon (PDF)
 * - Slaat pakbon-pad op in order_file_refs (factuur/ubl = NULL)
 * - Stuurt e-mail en haalt bijlagen uit DB
 * - Voegt Algemene Voorwaarden toe als losse bijlage (in-memory, niet opslaan)
 */

include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
session_start();

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php';

use Mpdf\Mpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;
use Dotenv\Dotenv;

// .env (SMTP)
$dotenv = Dotenv::createImmutable(dirname($_SERVER['DOCUMENT_ROOT']));
$dotenv->load();
$MAIL_USER = $_ENV['MAIL_USERNAME'] ?? getenv('MAIL_USERNAME');
$MAIL_PASS = $_ENV['MAIL_PASSWORD'] ?? getenv('MAIL_PASSWORD');

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

/* ------------------ Toegang/requests ------------------ */
if (empty($_SESSION['partner_id'])) { header("Location: /pages/account/login.php"); exit; }
$partner_id = (int)$_SESSION['partner_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: /pages/account/orders/cart_to_order.php");
  exit;
}

// CSRF
if (empty($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
  http_response_code(400); die('Ongeldige aanvraag (CSRF).');
}

/* ------------------ Helpers ------------------ */
function encryptField($data, $key) {
  if ($data==='') return '';
  $iv = random_bytes(16);
  $cipher = "AES-256-CBC";
  $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
  return base64_encode($iv.$encrypted);
}
function decryptField($data, $key) {
  if ($data==='') return '';
  $cipher = "AES-256-CBC";
  $raw = base64_decode($data, true);
  if ($raw===false || strlen($raw)<17) return '';
  $iv = substr($raw, 0, 16);
  $enc = substr($raw, 16);
  return openssl_decrypt($enc, $cipher, $key, 0, $iv) ?: '';
}
function has_col(mysqli $db, string $table, string $column): bool {
  $t = $db->real_escape_string($table);
  $c = $db->real_escape_string($column);
  if ($q = $db->query("SHOW COLUMNS FROM `$t` LIKE '$c'")) {
    $ok=(bool)$q->num_rows; $q->close(); return $ok;
  }
  return false;
}
function generateOrderNumber(mysqli $db){
  $year = date('Y');
  do {
    $rand = str_pad((string)random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    $orderNumber = "ORD-$year-$rand";
    $res = $db->query("SELECT id FROM orders WHERE order_number='".$db->real_escape_string($orderNumber)."'");
  } while ($res && $res->num_rows>0);
  return $orderNumber;
}

/* --- order_file_refs helpers (pakbon opslaan, factuur/ubl null) --- */
function ensureOrderFileRefsTable(mysqli $db){
  $db->query("CREATE TABLE IF NOT EXISTS `order_file_refs`(
    `order_id` INT PRIMARY KEY,
    `pakbon_path`  VARCHAR(512) NOT NULL,
    `factuur_path` VARCHAR(512) NULL,
    `ubl_path`     VARCHAR(512) NULL,
    `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_order_file_refs_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}
function upsertOrderFileRefs(mysqli $db,int $order_id,string $pakbonPath,?string $factuurPath,?string $ublPath){
  ensureOrderFileRefsTable($db);
  $sql="INSERT INTO order_file_refs (order_id,pakbon_path,factuur_path,ubl_path)
        VALUES (?,?,?,?)
        ON DUPLICATE KEY UPDATE pakbon_path=VALUES(pakbon_path),factuur_path=VALUES(factuur_path),ubl_path=VALUES(ubl_path)";
  $stmt=$db->prepare($sql);
  $stmt->bind_param('isss',$order_id,$pakbonPath,$factuurPath,$ublPath);
  $stmt->execute(); $stmt->close();
}
function getOrderFileRefs(mysqli $db,int $order_id): array{
  ensureOrderFileRefsTable($db);
  $stmt=$db->prepare("SELECT pakbon_path,factuur_path,ubl_path FROM order_file_refs WHERE order_id=? LIMIT 1");
  $stmt->bind_param('i',$order_id); $stmt->execute();
  $stmt->bind_result($pakbon,$factuur,$ubl);
  $ok=$stmt->fetch(); $stmt->close();
  return $ok ? ['pakbon'=>$pakbon,'factuur'=>$factuur,'ubl'=>$ubl] : ['pakbon'=>null,'factuur'=>null,'ubl'=>null];
}

/* ------------------ Invoer ------------------ */
$klant_naam    = trim($_POST['klant_naam'] ?? '');
$klant_email   = trim($_POST['klant_email'] ?? '');
$klant_tel     = trim($_POST['klant_telefoon'] ?? '');
$klant_adres   = trim($_POST['klant_adres'] ?? '');
$klantnr_part  = trim($_POST['klantnummer_partner'] ?? '');
$opmerking     = trim($_POST['partner_opmerking'] ?? '');
if (strlen($opmerking) > 5000) $opmerking = substr($opmerking, 0, 5000);

if ($klant_naam==='' || !filter_var($klant_email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400); die('Ongeldige klantgegevens.');
}

// Logistiek
$ashesMethod    = (string)($_POST['ashes_delivery_method'] ?? '');
$collectDate    = (string)($_POST['ashes_collect_date'] ?? '');
$collectTime    = (string)($_POST['ashes_collect_time'] ?? '');
$finishedMethod = (string)($_POST['finished_delivery_method'] ?? '');

$deliveryFeeInc = (float)($_POST['finished_delivery_fee'] ?? $_POST['delivery_cost_total'] ?? 0);
$distRawKm      = isset($_POST['distance_km_raw'])    ? (float)$_POST['distance_km_raw']    : null;
$distCapKm      = isset($_POST['distance_km_capped']) ? (float)$_POST['distance_km_capped'] : null;
$distMethod     = (string)($_POST['distance_method'] ?? '');
$ratePer10      = 3.00;
if ($ashesMethod==='afgehaald_door_ons' && $finishedMethod==='bezorgen') $ratePer10 = 6.00;

/* ------------------ Cart-items (medewerkers DB) ------------------ */
if (!isset($mysqli_medewerkers) || !($mysqli_medewerkers instanceof mysqli)) {
  http_response_code(500); die('DB-verbinding medewerkers ontbreekt.');
}

$cartItems = [];
$sqlCart = "
  SELECT ci.id, ci.product_type, ci.product_id, ci.name AS product_name,
         ci.unit_price, ci.qty, ci.variant_meta
  FROM cart_items ci
  JOIN carts c ON ci.cart_id = c.id
  WHERE c.partner_id = ?
  ORDER BY ci.id ASC
";
if ($st = $mysqli_medewerkers->prepare($sqlCart)) {
  $st->bind_param('i', $partner_id);
  $st->execute();
  $res = $st->get_result();
  while ($row = $res->fetch_assoc()) {
    $cartItems[] = [
      'row_id'       => (int)$row['id'],
      'product_type' => (string)$row['product_type'],
      'product_id'   => (int)$row['product_id'],
      'product_name' => (string)$row['product_name'],
      'unit_price'   => (float)$row['unit_price'],
      'qty'          => (int)$row['qty'],
      'variant_meta' => $row['variant_meta']
    ];
  }
  $st->close();
}
if (empty($cartItems)) { http_response_code(400); die('Geen producten in de winkelwagen.'); }

/* ------------------ Transactie (orders DB) ------------------ */
if (!isset($mysqli) || !($mysqli instanceof mysqli)) { http_response_code(500); die('DB-verbinding ontbreekt.'); }

$mysqli->begin_transaction();
try{
  $orderNumber = generateOrderNumber($mysqli);

  // orders
  $stmt = $mysqli->prepare("INSERT INTO orders (funeral_partner_id, order_number, klantnummer_partner) VALUES (?,?,?)");
  $stmt->bind_param('iss', $partner_id, $orderNumber, $klantnr_part);
  $stmt->execute(); $order_id = $stmt->insert_id; $stmt->close();

  // order_private
  if (!isset($encryption_key) || !is_string($encryption_key) || strlen($encryption_key)<32) {
    throw new RuntimeException('encryption_key ontbreekt of is ongeldig');
  }
  $stmt = $mysqli->prepare("
    INSERT INTO order_private (order_id, klant_naam, klant_email, klant_telefoon, klant_adres, klantnummer_partner, partner_opmerking)
    VALUES (?,?,?,?,?,?,?)
  ");
  $naam_enc   = encryptField($klant_naam,   $encryption_key);
  $email_enc  = encryptField($klant_email,  $encryption_key);
  $tel_enc    = encryptField($klant_tel,    $encryption_key);
  $adres_enc  = encryptField($klant_adres,  $encryption_key);
  $kpnr_enc   = encryptField($klantnr_part, $encryption_key);
  $opm_enc    = encryptField($opmerking,    $encryption_key);
  $stmt->bind_param('issssss', $order_id, $naam_enc, $email_enc, $tel_enc, $adres_enc, $kpnr_enc, $opm_enc);
  $stmt->execute(); $stmt->close();

  // order_products
  $has_ptype = has_col($mysqli,'order_products','product_type');
  $has_pname = has_col($mysqli,'order_products','product_name');
  $has_price = has_col($mysqli,'order_products','unit_price');
  $has_vmeta = has_col($mysqli,'order_products','variant_meta');

  $cols=['order_id','product_id','quantity']; $types='iii';
  if($has_ptype){ $cols[]='product_type'; $types.='s'; }
  if($has_pname){ $cols[]='product_name'; $types.='s'; }
  if($has_price){ $cols[]='unit_price';   $types.='d'; }
  if($has_vmeta){ $cols[]='variant_meta'; $types.='s'; }

  $sql="INSERT INTO order_products(".implode(',',$cols).") VALUES(".implode(',',array_fill(0,count($cols),'?')).")";
  $stmt=$mysqli->prepare($sql);
  foreach($cartItems as $ci){
    $vals=[ $order_id, (int)$ci['product_id'], max(1,(int)$ci['qty']) ];
    if($has_ptype) $vals[]=(string)$ci['product_type'];
    if($has_pname) $vals[]=(string)$ci['product_name'];
    if($has_price) $vals[]=(float)$ci['unit_price'];
    if($has_vmeta) $vals[]=$ci['variant_meta'] ?? null;
    $stmt->bind_param($types, ...$vals);
    $stmt->execute();
  }
  $stmt->close();

  $mysqli->commit();

  // Winkelwagen legen
  if ($st = $mysqli_medewerkers->prepare("
      DELETE ci FROM cart_items ci
      JOIN carts c ON ci.cart_id = c.id
      WHERE c.partner_id = ?
  ")) {
    $st->bind_param('i', $partner_id);
    $st->execute();
    $st->close();
  }

} catch (Throwable $e){
  $mysqli->rollback();
  http_response_code(500);
  die('Fout bij opslaan van bestelling: '.$e->getMessage());
}

/* ------------------ Pakbon (PDF) ------------------ */
// kleur helpers
function normalizeHex($hex){ $h=trim($hex); if(!preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/',$h)) return null; if(strlen($h)===4){$h='#'.$h[1].$h[1].$h[2].$h[2].$h[3].$h[3];} return strtoupper($h); }
function colorNameToHex($name){
  static $map=['goud'=>'#D4AF37','zilver'=>'#C0C0C0','brons'=>'#CD7F32','rosegold'=>'#B76E79','roségoud'=>'#B76E79','zwart'=>'#000000','wit'=>'#FFFFFF','rood'=>'#FF0000','groen'=>'#008000','blauw'=>'#0000FF','geel'=>'#FFFF00','oranje'=>'#FFA500','paars'=>'#800080','roze'=>'#FFC0CB','turkoois'=>'#40E0D0','ivoor'=>'#FFFFF0','beige'=>'#F5F5DC','bruin'=>'#8B4513','grijs'=>'#808080','gray'=>'#808080','grey'=>'#808080','lightgray'=>'#D3D3D3','gold'=>'#D4AF37','silver'=>'#C0C0C0','bronze'=>'#CD7F32','rose gold'=>'#B76E79','black'=>'#000000','white'=>'#FFFFFF','red'=>'#FF0000','green'=>'#008000','blue'=>'#0000FF','yellow'=>'#FFFF00','orange'=>'#FFA500','purple'=>'#800080','pink'=>'#FFC0CB','turquoise'=>'#40E0D0','ivory'=>'#FFFFF0','brown'=>'#8B4513'];
  $k=strtolower(trim($name)); return $map[$k]??null;
}
function resolveColorHexAndLabel(?string $raw, ?string &$labelOut): ?string {
  $labelOut=''; if(!$raw) return null; $raw=trim($raw);
  $hex=normalizeHex($raw); if($hex) return $hex;
  $byName=colorNameToHex($raw); if($byName){ $labelOut=ucfirst($raw); return $byName; }
  $labelOut=$raw; return null;
}
function textColorForBg(string $hex): string {
  $hex=ltrim($hex,'#'); $r=hexdec(substr($hex,0,2)); $g=hexdec(substr($hex,2,2)); $b=hexdec(substr($hex,4,2));
  $yiq=(($r*299)+($g*587)+($b*114))/1000; return ($yiq>=128)?'#000000':'#FFFFFF';
}

$logoPath=$_SERVER['DOCUMENT_ROOT'].'/assets/logo/logo.png';
$logoSrc=file_exists($logoPath)?'file://'.$logoPath:'';
$today=date('d/m/Y');
$winkelAdres = "Beukenlaan 8, 3930 Hamont-Achel, België";

$klantblok =
  '<table class="partnerinfo">'
 .'<tr><td class="kop">Naam</td><td>'.h($klant_naam).'</td></tr>'
 .'<tr><td class="kop">E-mail</td><td>'.h($klant_email).'</td></tr>'
 .'<tr><td class="kop">Telefoon</td><td>'.h($klant_tel).'</td></tr>'
 .'<tr><td class="kop">Adres</td><td>'.nl2br(h($klant_adres)).'</td></tr>'
 .'</table>';

$baseCss='
<style>
body{font-family:sans-serif;font-size:11pt}
h1{font-size:18pt;margin-bottom:0}
h3{font-size:13pt;margin-top:24px}
.kop{background:#f2f2f2;padding:6px;font-weight:700}
table{width:100%;border-collapse:collapse;margin-top:10px}
td,th{border:1px solid #ccc;padding:8px;text-align:left;vertical-align:top}
.orderinfo,.partnerinfo{width:100%;border-collapse:collapse;margin-top:10px}
.orderinfo td,.partnerinfo td{padding:6px;border:1px solid #ccc}
.productentabel{width:100%;border-collapse:collapse;margin-top:20px}
.productentabel th,.productentabel td{border:1px solid #ccc;padding:8px;text-align:left}
.productentabel th{background:#e8e8e8}
.logo{max-width:150px;margin-bottom:5px;margin-top:-5px}
.note{border:1px solid #ddd;background:#fafafa;padding:10px;border-radius:8px}
.right{text-align:right}
.variant{color:#444;font-size:10pt}
.variant .swatch{display:inline-block;width:12px;height:12px;border:1px solid #999;margin:0 6px -2px 6px}
</style>';

$rowsHtml=''; $i=1; $asRows=[]; $totaalAsGram=0.0;

foreach($cartItems as $ci){
  $pname = h($ci['product_name'] ?? 'Product');
  $qty   = max(1,(int)$ci['qty']);
  $vm    = null;
  if (!empty($ci['variant_meta'])) {
    $vm = json_decode($ci['variant_meta'], true);
    if (!is_array($vm)) $vm=null;
  }

  $kleurLabel=''; $kleurHex=null;
  if (is_array($vm) && !empty($vm['color'])) {
    $kleurHex = resolveColorHexAndLabel((string)$vm['color'], $kleurLabel);
  }
  $variantExtra='';
  if(is_array($vm) && !empty($vm['options']) && is_array($vm['options'])){
    $variantExtra='<div class="variant">Opties: '.h(implode(', ',$vm['options'])).'</div>';
  }

  if($kleurHex){
    $txtColor=textColorForBg($kleurHex);
    $kleurTd='<td style="background-color:'.h($kleurHex).';color:'.h($txtColor).';text-align:center;font-weight:600">'.($kleurLabel!==''?h($kleurLabel):'&nbsp;').'</td>';
  }else{
    $kleurTd='<td>'.($kleurLabel!==''?h($kleurLabel):'—').'</td>';
  }

  $rowsHtml.='<tr><td>'.($i++).'</td><td>'.$pname.$variantExtra.'</td>'.$kleurTd.'<td>'.$qty.'</td></tr>';
}

// As-overzicht
if ($stmtGram = $mysqli_medewerkers->prepare("SELECT gram FROM product_as WHERE product_id=? LIMIT 1")) {
  $j=1;
  foreach($cartItems as $ci){
    $pid=(int)$ci['product_id']; $qty=max(1,(int)$ci['qty']);
    if($pid>0){
      $isEpoxy=false;
      $res=$mysqli_medewerkers->query("SELECT id FROM epoxy_products WHERE id={$pid} AND sub_category='uitvaart' LIMIT 1");
      if($res && $res->num_rows>0) $isEpoxy=true;
      if($res) $res->close();

      if($isEpoxy){
        $stmtGram->bind_param('i',$pid);
        if($stmtGram->execute()){
          $stmtGram->bind_result($gramPerStuk);
          if($stmtGram->fetch() && $gramPerStuk!==null){
            $totaalGram=(float)$gramPerStuk*$qty; $totaalAsGram+=$totaalGram;
            $asRows[]=['nr'=>$j,'product'=>$ci['product_name'],'gram_per_stuk'=>(float)$gramPerStuk,'aantal'=>$qty,'totaal'=>$totaalGram];
          }
        }
        $stmtGram->free_result();
      }
    }
    $j++;
  }
  $stmtGram->close();
}

// Logistiek
$asAanleverenTekst = '—';
if ($ashesMethod==='zelf_bezorgen') {
  $asAanleverenTekst = 'Zelf bezorgen – Winkeladres: '.h($winkelAdres);
} elseif ($ashesMethod==='afgehaald_door_ons') {
  $ct = ($collectDate ? date('d/m/Y', strtotime($collectDate)) : 'n.t.b.');
  $asAanleverenTekst = 'Wordt afgehaald door ons – Afspraak: '.h($ct.($collectTime ? ' '.$collectTime : ''));
  if ($deliveryFeeInc > 0) {
    $asAanleverenTekst .= '<br>Afstand: '.($distCapKm!==null?number_format($distCapKm,1,',','.').' km':'—');
    $asAanleverenTekst .= ' &nbsp;|&nbsp; Kost (incl. btw): <sup>€</sup>'.number_format($deliveryFeeInc,2,',','.');
    $asAanleverenTekst .= ' &nbsp;|&nbsp; Tarief: <sup>€</sup>'.number_format($ratePer10,2,',','.').'/10 km';
    if ($distMethod) { $asAanleverenTekst .= ' &nbsp;|&nbsp; bron: '.h($distMethod); }
  }
} elseif ($ashesMethod==='koerier') {
  $asAanleverenTekst = 'Verzenden via koerier (op eigen risico)';
}
$afgewerktProductTekst = ($finishedMethod==='afhalen_winkel' ? 'Afhalen in winkel' : ($finishedMethod==='bezorgen' ? 'Bezorgen aan huis' : '—'));
if ($finishedMethod==='bezorgen') {
  $afgewerktProductTekst .= '<br>Afstand: '.($distCapKm!==null?number_format($distCapKm,1,',','.').' km':'—');
  $afgewerktProductTekst .= ' &nbsp;|&nbsp; Kost (incl. btw): '.($deliveryFeeInc>0?('<sup>€</sup>'.number_format($deliveryFeeInc,2,',','.')):'<sup>€</sup>0,00');
  $afgewerktProductTekst .= ' &nbsp;|&nbsp; Tarief: <sup>€</sup>'.number_format($ratePer10,2,',','.').'/10 km';
  if ($distMethod) { $afgewerktProductTekst .= ' &nbsp;|&nbsp; bron: '.h($distMethod); }
}

// Render pakbon
$pakbonDir = $_SERVER['DOCUMENT_ROOT'].'/pages/account/orders/pdf';
if (!is_dir($pakbonDir)) { @mkdir($pakbonDir, 0775, true); }
$pakbonPath = $pakbonDir.'/pakbon_'.$order_id.'.pdf';

$pakbonHtml = $baseCss
  .($logoSrc?'<img src="'.$logoSrc.'" class="logo" alt="Logo">':'')
  .'<h1>Pakbon</h1>'
  .'<table class="partnerinfo"><tr><td class="kop">Ordernummer</td><td>'.h($orderNumber).'</td></tr><tr><td class="kop">Datum</td><td>'.$today.'</td></tr></table>'
  .'<h3>Klant</h3>'.$klantblok
  .(!empty($opmerking)?'<h3>Opmerking</h3><div class="note">'.nl2br(h($opmerking)).'</div>':'')
  .'<h3>Logistiek</h3>'
   .'<table class="partnerinfo">'
     .'<tr><td class="kop">As aanleveren</td><td>'.$asAanleverenTekst.'</td></tr>'
     .'<tr><td class="kop">Afgewerkt product</td><td>'.$afgewerktProductTekst.'</td></tr>'
   .'</table>'
  .'<h3>Bestelde Producten</h3>'
  .'<table class="productentabel"><tr><th>#</th><th>Product</th><th>Kleur</th><th>Aantal</th></tr>'.$rowsHtml.'</table>';

if (!empty($asRows)) {
  $pakbonHtml.='<h3>As-overzicht (epoxy)</h3><table class="productentabel"><tr><th>#</th><th>Product</th><th>Gram/stuk</th><th>Aantal</th><th>Totaal gram</th></tr>';
  foreach($asRows as $r){
    $pakbonHtml.='<tr><td>'.$r['nr'].'</td><td>'.h($r['product']).'</td><td>'.number_format($r['gram_per_stuk'],2,',','.').'</td><td>'.$r['aantal'].'</td><td>'.number_format($r['totaal'],2,',','.').'</td></tr>';
  }
  $pakbonHtml.='<tr><td colspan="4" class="right" style="font-weight:700">Totaal te leveren as</td><td style="font-weight:700">'.number_format($totaalAsGram,2,',','.').' g</td></tr></table>';
} else {
  $pakbonHtml.='<p><em>Er zijn geen as-producten gedetecteerd voor deze bestelling.</em></p>';
}

$mpdf = new Mpdf();
$mpdf->WriteHTML($pakbonHtml);
$mpdf->Output($pakbonPath, \Mpdf\Output\Destination::FILE);

/* ------------------ Algemene voorwaarden (los, in-memory) ------------------ */
$voorwaardenPdfBin = null;
$termsPathHtml = $_SERVER['DOCUMENT_ROOT'].'/legal/algemene-voorwaarden.html';
$termsPathTxt  = $_SERVER['DOCUMENT_ROOT'].'/legal/algemene-voorwaarden.txt';

$voorwaardenContent = '';
if (is_file($termsPathHtml)) {
  $voorwaardenContent = (string)file_get_contents($termsPathHtml);
} elseif (is_file($termsPathTxt)) {
  $voorwaardenContent = '<pre style="white-space:pre-wrap;margin:0">'.
                        nl2br(h((string)file_get_contents($termsPathTxt))).'</pre>';
}
if ($voorwaardenContent !== '') {
  $voorwaardenHtml = $baseCss.'<h3>Algemene voorwaarden</h3><div class="voorwaarden">'.$voorwaardenContent.'</div>';
  $mpdfTerms = new Mpdf();
  $mpdfTerms->WriteHTML($voorwaardenHtml);
  $voorwaardenPdfBin = $mpdfTerms->Output('', \Mpdf\Output\Destination::STRING_RETURN); // binaire string
}

/* ------------------ Pakbon-pad opslaan in DB ------------------ */
if (!is_file($pakbonPath)) { http_response_code(500); die('Pakbon genereren mislukt.'); }
upsertOrderFileRefs($mysqli, $order_id, $pakbonPath, null, null);

/* ------------------ Mail-ontvangers ------------------ */
function loadPartnerBillingRecipient(mysqli $db, int $partnerId): string {
  $stmt = $db->prepare("SELECT billing_recipient FROM funeral_partners WHERE id=? LIMIT 1");
  $stmt->bind_param('i',$partnerId);
  $stmt->execute();
  $stmt->bind_result($rec);
  $ok=$stmt->fetch();
  $stmt->close();
  return $ok ? strtolower((string)$rec) : 'partner'; // 'partner' of 'customer'
}
function loadPartnerEmail(mysqli $db, int $partnerId): ?string {
  $stmt = $db->prepare("SELECT email FROM funeral_partners WHERE id=? LIMIT 1");
  $stmt->bind_param('i', $partnerId);
  $stmt->execute();
  $stmt->bind_result($em);
  $ok = $stmt->fetch();
  $stmt->close();
  return $ok ? (string)$em : null;
}
function loadPrivateEmailDecrypted(mysqli $db, int $orderId, string $key): ?string {
  $stmt = $db->prepare("SELECT klant_email FROM order_private WHERE order_id=? LIMIT 1");
  $stmt->bind_param('i', $orderId);
  $stmt->execute();
  $stmt->bind_result($enc);
  $ok = $stmt->fetch();
  $stmt->close();
  if (!$ok || !$enc) return null;
  return decryptField((string)$enc, $key) ?: null;
}

$billingRecipient = loadPartnerBillingRecipient($mysqli, $partner_id);

/* ------------------ 1) Team-mail ------------------ */
try{
  $title = "Nieuwe bestelling (uitvaartpartner): $orderNumber";
  $factTxt = ($billingRecipient==='customer') ? 'Eindklant (nabestaanden)' : 'Uitvaartdienst (partner)';
  $body  = "Beste collega,<br><br>Er werd een nieuwe bestelling ingevoerd door een uitvaartdienst.<br><br>"
         . "Order: <strong>".h($orderNumber)."</strong>.<br>"
         . "Facturatie: <strong>".$factTxt."</strong>.<br><br>"
         . "Pakbon vindt u in bijlage.";

  $mail=new PHPMailer(true);
  $mail->setFrom('info@windelsgreen-decoresin.com','Windels Green & Deco Resin');
  $mail->addReplyTo('webshop@windelsgreen-decoresin.com','Webshop');
  if($dev == True){
    $mail->addAddress('admin@windelsgreen-decoresin.com', 'Andy Windels');
  }else{
    foreach (['webshop@windelsgreen-decoresin.com','windelsfranky@gmail.com'] as $rcpt) { $mail->addAddress($rcpt); }
  }
  $mail->CharSet='UTF-8';
  $mail->isHTML(true);
  $mail->Subject=$title;
  $mail->Body = str_replace(
    ['{titlemail}','{headermail}','{bodymail}'],
    [$title, 'Nieuwe Bestelling', $body],
    file_get_contents($_SERVER['DOCUMENT_ROOT'].'/emailtemplate.php')
  );

  // Bijlagen uit DB
  $files = getOrderFileRefs($mysqli,$order_id);
  if($files['pakbon'] && is_file($files['pakbon'])){ $mail->addAttachment($files['pakbon']); }
  // Algemene voorwaarden los (in-memory)
  if (!empty($voorwaardenPdfBin)) {
    $mail->addStringAttachment($voorwaardenPdfBin, 'algemene-voorwaarden.pdf', 'base64', 'application/pdf');
  }

  $mail->isSMTP();
  $mail->Host='smtp-auth.mailprotect.be';
  $mail->SMTPAuth=true;
  $mail->Username=$MAIL_USER;
  $mail->Password=$MAIL_PASS;
  $mail->SMTPSecure='ssl';
  $mail->Port=465;
  $mail->send();
}catch(MailException $e){ error_log("Team-mail fout: ".$e->getMessage()); }

/* ------------------ 2) Mail naar ontvanger ------------------ */
try{
  // Bepaal ontvanger obv billing setting
  if ($billingRecipient === 'customer') {
    $toEmail = loadPrivateEmailDecrypted($mysqli, $order_id, $encryption_key) ?: $klant_email;
    $toName  = $klant_naam;
  } else {
    $toEmail = loadPartnerEmail($mysqli, $partner_id) ?: ($_SESSION['email'] ?? null);
    $toName  = $_SESSION['bedrijf_naam'] ?? 'Uitvaartpartner';
  }

  if ($toEmail) {
    $title = "Bevestiging bestelling – $orderNumber";
    $head  = "Bevestiging bestelling";
    $body  = "Beste ".h($toName).",<br><br>"
           . "Hartelijk dank, we hebben uw bestelling ontvangen.<br>"
           . "Order: <strong>".h($orderNumber)."</strong>.<br><br>"
           . "<strong>Logistiek</strong><br>"
           . "As aanleveren: ".$asAanleverenTekst."<br>"
           . "Afgewerkt product: ".$afgewerktProductTekst."<br><br>"
           . "In de bijlage vindt u de <strong>pakbon</strong> als PDF.<br><br>"
           . "Met vriendelijke groet,<br>Windels Green & Deco Resin";

    $mail=new PHPMailer(true);
    $mail->setFrom('info@windelsgreen-decoresin.com','Windels Green & Deco Resin');
    $mail->addReplyTo('webshop@windelsgreen-decoresin.com','Webshop');
    if($dev == True){
      $mail->addAddress('andywindels5@gmail.com', 'Andy Windels');
    }else{
      $mail->addAddress($toEmail, $toName);
    }
    $mail->CharSet='UTF-8';
    $mail->isHTML(true);
    $mail->Subject=$title;
    $mail->Body = str_replace(
      ['{titlemail}','{headermail}','{bodymail}'],
      [$title,$head,$body],
      file_get_contents($_SERVER['DOCUMENT_ROOT'].'/emailtemplate.php')
    );

    // Bijlagen uit DB
    $files = getOrderFileRefs($mysqli,$order_id);
    if($files['pakbon'] && is_file($files['pakbon'])){ $mail->addAttachment($files['pakbon']); }
    // Algemene voorwaarden los (in-memory)
    if (!empty($voorwaardenPdfBin)) {
      $mail->addStringAttachment($voorwaardenPdfBin, 'algemene-voorwaarden.pdf', 'base64', 'application/pdf');
    }

    $mail->isSMTP();
    $mail->Host='smtp-auth.mailprotect.be';
    $mail->SMTPAuth=true;
    $mail->Username=$MAIL_USER;
    $mail->Password=$MAIL_PASS;
    $mail->SMTPSecure='ssl';
    $mail->Port=465;
    $mail->send();
  } else {
    error_log("Ontvangersmail ontbreekt voor order {$order_id}");
  }
}catch(MailException $e){ error_log("Ontvangersmail fout: ".$e->getMessage()); }

/* ------------------ Door naar bevestigingspagina ------------------ */
header("Location: /pages/account/orders/bevestiging.php?order_id=".$order_id);
exit;
