<?php
/* ============================================================
   Orderverwerking (NA succesvolle betaling)
   - maakt order, factuur (PDF + UBL), pakbon en mails
   - slaat paden (pakbon/factuur/ubl) per order op in DB
   ============================================================ */
include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
@ini_set('session.cookie_secure','1');
@ini_set('session.cookie_httponly','1');
@ini_set('session.cookie_samesite','Lax');
session_start();

require_once dirname($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php';

use Dotenv\Dotenv;
use Mollie\Api\MollieApiClient;
use Mpdf\Mpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

$dotenv = Dotenv::createImmutable(dirname($_SERVER['DOCUMENT_ROOT']));
$dotenv->load();

$MAIL_USER  = $_ENV['MAIL_USERNAME'] ?? getenv('MAIL_USERNAME');
$MAIL_PASS  = $_ENV['MAIL_PASSWORD'] ?? getenv('MAIL_PASSWORD');
$MOLLIE_KEY = $_ENV['MOLLIE_API_KEY'] ?? getenv('MOLLIE_API_KEY');

$HOST        = 'https://'.$_SERVER['HTTP_HOST'];
$CONFIRM_URL = '/pages/orders/bevestiging.php';
$CART_URL    = '/pages/orders/cart.php';
$DEBUG       = isset($_GET['debug']) && $_GET['debug'] === '1';
$REQUEST_ID  = bin2hex(random_bytes(6));

function goCart($reason){
  global $CART_URL,$DEBUG,$REQUEST_ID;
  error_log("[VERWERK][$REQUEST_ID] -> cart: ".$reason);
  $url = $CART_URL.'?why='.rawurlencode($reason).($DEBUG?'&debug=1':'');
  header('Location: '.$url); exit;
}
function h($s){ return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8'); }

/* ---------- sanity ---------- */
if(!isset($mysqli) || !($mysqli instanceof mysqli)){ http_response_code(500); die('DB-verbinding ontbreekt.'); }
if(empty($MOLLIE_KEY)){ http_response_code(500); die('Mollie API key ontbreekt.'); }
if(!isset($encryption_key) || !is_string($encryption_key) || strlen($encryption_key) < 32){
  goCart('encryption_key ontbreekt');
}

/* ---------- input ---------- */
$paymentId = isset($_GET['pid']) ? trim($_GET['pid']) : '';
$returnTok = isset($_GET['rt'])  ? trim($_GET['rt'])  : '';

/* pid=return → resolve via rt -> redirect met echte pid */
if ($paymentId === 'return') {
  if ($returnTok !== '') {
    $stmt=$mysqli->prepare("SELECT payment_id FROM mollie_payments WHERE return_token=? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param('s',$returnTok);
    $stmt->execute();
    $stmt->bind_result($foundPid);
    $ok=$stmt->fetch(); $stmt->close();
    if ($ok && $foundPid) {
      $url = $_SERVER['PHP_SELF'].'?pid='.rawurlencode($foundPid).'&rt='.rawurlencode($returnTok).($DEBUG?'&debug=1':'');
      header('Location: '.$url); exit;
    }
    if($DEBUG){ header('Content-Type: text/plain; charset=utf-8'); echo "[DEBUG] return-token niet gevonden\n"; exit; }
  }
  goCart('return zonder geldige payment');
}
if ($paymentId === '' || $returnTok === '') goCart('pid/rt ontbreekt');

/* token hoort bij payment? */
$stmt=$mysqli->prepare("SELECT metadata FROM mollie_payments WHERE payment_id=? AND return_token=? LIMIT 1");
$stmt->bind_param('ss',$paymentId,$returnTok);
$stmt->execute();
$stmt->bind_result($metaJsonDb);
$match=$stmt->fetch();
$stmt->close();
if(!$match) goCart('token/pid mismatch');

/* ---------- Mollie ophalen ---------- */
$mollie = new MollieApiClient();
$mollie->setApiKey($MOLLIE_KEY);
try{ $payment = $mollie->payments->get($paymentId); }
catch(Throwable $e){
  if($DEBUG){ header('Content-Type: text/plain; charset=utf-8'); echo "Mollie fout:\n\n".$e->getMessage(); exit; }
  goCart('mollie get error');
}
if (!($payment->isPaid() || (method_exists($payment,'isAuthorized') && $payment->isAuthorized()))) {
  goCart('betaling niet voltooid');
}

/* ---------- helpers ---------- */
function encryptField(string $data, string $key): string {
  if($data==='') return '';
  $iv = random_bytes(12); $tag='';
  $enc = openssl_encrypt($data,'AES-256-GCM',$key,OPENSSL_RAW_DATA,$iv,$tag,'');
  return base64_encode($iv.$tag.$enc);
}
function has_col(mysqli $db,string $table,string $column): bool{
  $t=$db->real_escape_string($table); $c=$db->real_escape_string($column);
  $q=$db->query("SHOW COLUMNS FROM `$t` LIKE '$c'"); $ok=$q && $q->num_rows>0; if($q) $q->close(); return $ok;
}
function generateOrderNumber(mysqli $db){
  $year=date('Y');
  do{
    $rand=str_pad((string)random_int(1,9999),4,'0',STR_PAD_LEFT);
    $orderNumber="ORD-$year-$rand";
    $res=$db->query("SELECT id FROM orders WHERE order_number='".$db->real_escape_string($orderNumber)."'");
  }while($res && $res->num_rows>0);
  return $orderNumber;
}
function ensureInvoiceTable(mysqli $db){
  $db->query("CREATE TABLE IF NOT EXISTS `order_invoices`(
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `invoice_number` VARCHAR(32) NOT NULL UNIQUE,
    `invoice_date` DATETIME NOT NULL,
    `total_ex` DECIMAL(12,2) NOT NULL,
    `total_vat` DECIMAL(12,2) NOT NULL,
    `total_inc` DECIMAL(12,2) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
}
function generateInvoiceNumber(mysqli $db){
  $y=date('Y');
  $res=$db->query("SELECT COUNT(*) c FROM order_invoices WHERE YEAR(invoice_date)=".intval($y));
  $row=$res?$res->fetch_assoc():['c'=>0];
  $seq=((int)$row['c'])+1;
  return 'F'.$y.'-'.str_pad((string)$seq,5,'0',STR_PAD_LEFT);
}
function computeVat(array $items): array{
  $lines=[]; $sumEx=0.0; $sumVat=0.0; $sumInc=0.0; $perRate=[];
  foreach($items as $it){
    $qty=max(1,(int)$it['qty']); $unitInc=(float)$it['price']; $rate=(float)($it['vat'] ?? 21.0);
    $inc=$unitInc*$qty; $ex=$inc/(1+$rate/100); $vat=$inc-$ex;
    $lines[]=['title'=>$it['title'],'qty'=>$qty,'unit_price_inc'=>$unitInc,'rate'=>$rate,'ex'=>$ex,'vat'=>$vat,'inc'=>$inc];
    $sumEx+=$ex; $sumVat+=$vat; $sumInc+=$inc;
    $k=(string)$rate; if(!isset($perRate[$k])) $perRate[$k]=['ex'=>0,'vat'=>0,'inc'=>0,'rate'=>$rate];
    $perRate[$k]['ex']+=$ex; $perRate[$k]['vat']+=$vat; $perRate[$k]['inc']+=$inc;
  }
  $sumEx=round($sumEx,2); $sumVat=round($sumVat,2); $sumInc=round($sumInc,2);
  foreach($lines as &$L){ $L['ex']=round($L['ex'],2); $L['vat']=round($L['vat'],2); $L['inc']=round($L['inc'],2); }
  foreach($perRate as &$R){ $R['ex']=round($R['ex'],2); $R['vat']=round($R['vat'],2); $R['inc']=round($R['inc'],2); }
  return ['lines'=>$lines,'sumEx'=>$sumEx,'sumVat'=>$sumVat,'sumInc'=>$sumInc,'perRate'=>$perRate];
}
function buildUBL(array $supplier, array $customer, string $invNumber, string $invDate, array $vatCalc, string $currency='EUR'): string{
  $xml=new DOMDocument('1.0','UTF-8'); $xml->formatOutput=false;
  $Invoice=$xml->createElementNS('urn:oasis:names:specification:ubl:schema:xsd:Invoice-2','Invoice'); $xml->appendChild($Invoice);
  $Invoice->setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:cac','urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
  $Invoice->setAttributeNS('http://www.w3.org/2000/xmlns/','xmlns:cbc','urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
  $cbc=function($n,$v)use($xml){$e=$xml->createElement('cbc:'.$n);$e->appendChild($xml->createTextNode($v));return $e;};
  $cac=function($n)use($xml){return $xml->createElement('cac:'.$n);};

  $Invoice->appendChild($cbc('CustomizationID','urn:cen.eu:en16931:2017'));
  $Invoice->appendChild($cbc('ProfileID','urn:fdc:peppol.eu:2017:poacc:billing:01:1.0'));
  $Invoice->appendChild($cbc('ID',$invNumber));
  $Invoice->appendChild($cbc('IssueDate',substr($invDate,0,10)));
  $Invoice->appendChild($cbc('InvoiceTypeCode','380'));
  $Invoice->appendChild($cbc('DocumentCurrencyCode',$currency));

  $acc=$cac('AccountingSupplierParty'); $party=$cac('Party');
  $pn=$cac('PartyName'); $pn->appendChild($cbc('Name',$supplier['name'])); $party->appendChild($pn);
  if(!empty($supplier['vat'])){ $tx=$cac('PartyTaxScheme'); $tx->appendChild($cbc('CompanyID',$supplier['vat'])); $ts=$cac('TaxScheme'); $ts->appendChild($cbc('ID','VAT')); $tx->appendChild($ts); $party->appendChild($tx); }
  if(!empty($supplier['street'])){ $ad=$cac('PostalAddress'); $ad->appendChild($cbc('StreetName',$supplier['street'])); if(!empty($supplier['city']))$ad->appendChild($cbc('CityName',$supplier['city'])); if(!empty($supplier['zip']))$ad->appendChild($cbc('PostalZone',$supplier['zip'])); $ct=$cac('Country'); $ct->appendChild($cbc('IdentificationCode','BE')); $ad->appendChild($ct); $party->appendChild($ad); }
  $acc->appendChild($party); $Invoice->appendChild($acc);

  $acc=$cac('AccountingCustomerParty'); $party=$cac('Party'); $pn=$cac('PartyName'); $pn->appendChild($cbc('Name',$customer['name'])); $party->appendChild($pn);
  $ad=$cac('PostalAddress'); $ad->appendChild($cbc('StreetName',$customer['street']??'')); if(!empty($customer['city']))$ad->appendChild($cbc('CityName',$customer['city'])); if(!empty($customer['zip']))$ad->appendChild($cbc('PostalZone',$customer['zip'])); $ct=$cac('Country'); $ct->appendChild($cbc('IdentificationCode','BE')); $ad->appendChild($ct); $party->appendChild($ad);
  $acc->appendChild($party); $Invoice->appendChild($acc);

  $i=1; foreach($vatCalc['lines'] as $L){ $ln=$cac('InvoiceLine'); $ln->appendChild($cbc('ID',(string)$i++)); $ln->appendChild($cbc('InvoicedQuantity',(string)$L['qty'])); $ln->appendChild($cbc('LineExtensionAmount',number_format($L['ex'],2,'.',''))); $item=$cac('Item'); $item->appendChild($cbc('Name',$L['title'])); $tc=$cac('ClassifiedTaxCategory'); $tc->appendChild($cbc('ID','S')); $tc->appendChild($cbc('Percent',(string)$L['rate'])); $ts=$cac('TaxScheme'); $ts->appendChild($cbc('ID','VAT')); $tc->appendChild($ts); $item->appendChild($tc); $ln->appendChild($item); $pr=$cac('Price'); $unitEx=$L['unit_price_inc']/(1+$L['rate']/100); $pr->appendChild($cbc('PriceAmount',number_format($unitEx,2,'.',''))); $ln->appendChild($pr); $Invoice->appendChild($ln); }
  foreach($vatCalc['perRate'] as $r=>$tot){ $tt=$cac('TaxTotal'); $tt->appendChild($cbc('TaxAmount',number_format($tot['vat'],2,'.',''))); $ts=$cac('TaxSubtotal'); $ts->appendChild($cbc('TaxableAmount',number_format($tot['ex'],2,'.',''))); $ts->appendChild($cbc('TaxAmount',number_format($tot['vat'],2,'.',''))); $tc=$cac('TaxCategory'); $tc->appendChild($cbc('ID','S')); $tc->appendChild($cbc('Percent',(string)$tot['rate'])); $sch=$cac('TaxScheme'); $sch->appendChild($cbc('ID','VAT')); $tc->appendChild($sch); $ts->appendChild($tc); $tt->appendChild($ts); $Invoice->appendChild($tt); }
  $lm=$cac('LegalMonetaryTotal'); $lm->appendChild($cbc('LineExtensionAmount',number_format($vatCalc['sumEx'],2,'.',''))); $lm->appendChild($cbc('TaxExclusiveAmount',number_format($vatCalc['sumEx'],2,'.',''))); $lm->appendChild($cbc('TaxInclusiveAmount',number_format($vatCalc['sumInc'],2,'.',''))); $lm->appendChild($cbc('PayableAmount',number_format($vatCalc['sumInc'],2,'.',''))); $Invoice->appendChild($lm);

  return $xml->saveXML();
}

/* ---------- BESTANDS-REFERENTIES (pakbon/factuur/ubl) ---------- */
function ensureOrderFileRefsTable(mysqli $db){
  $db->query("CREATE TABLE IF NOT EXISTS `order_file_refs`(
    `order_id` INT PRIMARY KEY,
    `pakbon_path`  VARCHAR(512) NOT NULL,
    `factuur_path` VARCHAR(512) NULL,
    `ubl_path`     VARCHAR(512) NULL,
    `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_order_file_refs_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
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

/* ---------- kleur-helpers voor pakbon (hele kleurcel) ---------- */
function normalizeHex(string $hex): ?string {
  $h = trim($hex);
  if (!preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $h)) return null;
  if (strlen($h) === 4) { $r=$h[1].$h[1]; $g=$h[2].$h[2]; $b=$h[3].$h[3]; return strtoupper("#$r$g$b"); }
  return strtoupper($h);
}
function colorNameToHex(string $name): ?string {
  static $map=['goud'=>'#D4AF37','zilver'=>'#C0C0C0','brons'=>'#CD7F32','rosegold'=>'#B76E79','roségoud'=>'#B76E79','zwart'=>'#000000','wit'=>'#FFFFFF','rood'=>'#FF0000','groen'=>'#008000','blauw'=>'#0000FF','geel'=>'#FFFF00','oranje'=>'#FFA500','paars'=>'#800080','roze'=>'#FFC0CB','turkoois'=>'#40E0D0','ivoor'=>'#FFFFF0','beige'=>'#F5F5DC','bruin'=>'#8B4513','grijs'=>'#808080','grijs (licht)'=>'#D3D3D3','gold'=>'#D4AF37','silver'=>'#C0C0C0','bronze'=>'#CD7F32','rose gold'=>'#B76E79','white'=>'#FFFFFF','black'=>'#000000','red'=>'#FF0000','green'=>'#008000','blue'=>'#0000FF','yellow'=>'#FFFF00','orange'=>'#FFA500','purple'=>'#800080','pink'=>'#FFC0CB','turquoise'=>'#40E0D0','ivory'=>'#FFFFF0','beige'=>'#F5F5DC','brown'=>'#8B4513','gray'=>'#808080','grey'=>'#808080','lightgray'=>'#D3D3D3']; $k=strtolower(trim($name)); return $map[$k] ?? null;
}
function resolveColorHexAndLabel(?string $raw, ?string &$labelOut): ?string {
  $labelOut=''; if(!$raw) return null; $raw=trim((string)$raw);
  $hex=normalizeHex($raw); if($hex) return $hex;
  $hexByName=colorNameToHex($raw); if($hexByName){ $labelOut=ucfirst($raw); return $hexByName; }
  $labelOut=$raw; return null;
}
function textColorForBg(string $hex): string {
  $hex=ltrim($hex,'#'); $r=hexdec(substr($hex,0,2)); $g=hexdec(substr($hex,2,2)); $b=hexdec(substr($hex,4,2));
  $yiq=(($r*299)+($g*587)+($b*114))/1000; return ($yiq>=128)?'#000000':'#FFFFFF';
}

/* ---------- cart herstellen ---------- */
$SNAP_DIR = $_SERVER['DOCUMENT_ROOT'].'/storage/checkout';
$items = [];
$metaDb = $metaJsonDb ? (json_decode($metaJsonDb, true) ?: []) : [];

// --- Logistiek/levering uit metadata (ingevuld in betaal.php) ---
$deliveryMeta   = is_array($metaDb['delivery'] ?? null) ? $metaDb['delivery'] : [];
$ashesMethod    = (string)($deliveryMeta['ashes_method']    ?? '');
$finishedMethod = (string)($deliveryMeta['finished_method'] ?? '');
$deliveryFeeInc = (float)  ($deliveryMeta['fee_incl']       ?? 0);
$distRawKm      = isset($deliveryMeta['distance_raw_km']) ? (float)$deliveryMeta['distance_raw_km'] : null;
$distCapKm      = isset($deliveryMeta['distance_cap_km']) ? (float)$deliveryMeta['distance_cap_km'] : null;
$distMethod     = (string)($deliveryMeta['distance_method'] ?? '');
$collectDate    = (string)($deliveryMeta['collect_date']    ?? '');
$collectTime    = (string)($deliveryMeta['collect_time']    ?? '');
$ratePer10      = isset($deliveryMeta['rate_per_10km']) ? (float)$deliveryMeta['rate_per_10km'] : null;

if (!empty($_SESSION['cart']['items']) && is_array($_SESSION['cart']['items'])) {
  foreach (array_values($_SESSION['cart']['items']) as $it) {
    $items[] = [
      'product_id'=>(int)($it['product_id']??0),
      'product_type'=>(string)($it['product_type']??''),
      'title'=>(string)($it['title']??$it['name']??'Product'),
      'price'=>(float)($it['price']??0),
      'qty'=>max(1,(int)($it['qty']??1)),
      'variant_meta'=>isset($it['variant_meta']) ? (is_array($it['variant_meta']) ? $it['variant_meta'] : json_decode((string)$it['variant_meta'],true)) : null,
      'vat'=>isset($it['vat']) ? (float)$it['vat'] : 21.0,
    ];
  }
}
if (empty($items) && !empty($metaDb['cart'])) {
  foreach ($metaDb['cart'] as $it) {
    $items[] = [
      'product_id'=>(int)($it['product_id']??0),
      'product_type'=>(string)($it['product_type']??''),
      'title'=>(string)($it['title']??'Product'),
      'price'=>(float)($it['price']??0),
      'qty'=>max(1,(int)($it['qty']??1)),
      'variant_meta'=>(isset($it['variant_meta']) && is_array($it['variant_meta'])) ? $it['variant_meta'] : null,
      'vat'=>isset($it['vat']) ? (float)$it['vat'] : 21.0,
    ];
  }
}
if (empty($items)) {
  $file=$SNAP_DIR.'/'.$paymentId.'.json';
  if(is_file($file)){
    $blob=json_decode((string)file_get_contents($file),true);
    if(is_array($blob) && !empty($blob['cart'])){
      foreach($blob['cart'] as $it){
        $items[]=[
          'product_id'=>(int)($it['product_id']??0),
          'product_type'=>(string)($it['product_type']??''),
          'title'=>(string)($it['title']??'Product'),
          'price'=>(float)($it['price']??0),
          'qty'=>max(1,(int)($it['qty']??1)),
          'variant_meta'=>(isset($it['variant_meta'])&&is_array($it['variant_meta']))?$it['variant_meta']:null,
          'vat'=>isset($it['vat'])?(float)$it['vat']:21.0,
        ];
      }
      if (empty($_SESSION['checkout_customer']) && !empty($blob['customer'])) {
        $_SESSION['checkout_customer']=[
          'naam'=>(string)($blob['customer']['name']??''),
          'email'=>(string)($blob['customer']['email']??''),
          'tel'=>(string)($blob['customer']['tel']??''),
          'adres'=>(string)($blob['customer']['adres']??''),
          'opm'=>(string)($blob['customer']['opm']??''),
          'expected_amount'=>(string)($blob['amount']??''),
        ];
      }
    }
  }
}
if (empty($items)) goCart('geen items (sessie + snapshot leeg)');

/* ---------- bedrag check (incl. levering) ---------- */
$products_total=0.0; foreach($items as $it){ $products_total += (float)$it['price'] * (int)$it['qty']; } $products_total=round($products_total,2);
$paidAmount = (float)$payment->amount->value;

$grand_incl_meta = isset($metaDb['totals']['grand_incl']) ? (float)$metaDb['totals']['grand_incl'] : null;
if ($grand_incl_meta !== null) {
  if (abs($paidAmount - $grand_incl_meta) > 0.01) goCart('bedrag mismatch (grand_incl)');
} else {
  if (abs($paidAmount - $products_total) > 0.01) goCart('bedrag mismatch');
}

/* ---------- klantdata ---------- */
$klSrc = $_SESSION['checkout_customer'] ?? ($metaDb['customer'] ?? []);
if (empty($klSrc)) goCart('klantdata ontbreekt');
$klant = [
  'naam'  => (string)($klSrc['naam']  ?? $klSrc['name']  ?? ''),
  'email' => (string)($klSrc['email'] ?? ''),
  'tel'   => (string)($klSrc['tel']   ?? ''),
  'adres' => (string)($klSrc['adres'] ?? ''),
  'opm'   => (string)($klSrc['opm']   ?? ''),
];
if($klant['naam']==='' || $klant['email']==='') goCart('klantdata ontbreekt');

/* ---------- BTW berekening (incl. bezorgkosten als lijn) ---------- */
$itemsForInvoice = $items;
if ($deliveryFeeInc > 0) {
  $deliveryTitle = ($finishedMethod === 'bezorgen') ? 'Bezorgkosten' : (($ashesMethod === 'afgehaald_door_ons') ? 'Ritkost afhaling as' : 'Kilometerkost');
  $itemsForInvoice[] = ['product_id'=>0,'product_type'=>'service','title'=>$deliveryTitle,'price'=>$deliveryFeeInc,'qty'=>1,'variant_meta'=>null,'vat'=>21.0];
}
$vat = computeVat($itemsForInvoice);

/* ---------- leverancier/klant UBL blok ---------- */
$supplier = ['name'=>'Windels Green & Deco Resin','vat'=>'BE0803859883','street'=>'Beukenlaan 8','city'=>'Hamont-Achel','zip'=>'3930'];
$customer = ['name'=>$klant['naam'],'street'=>$klant['adres'],'city'=>'','zip'=>''];

/* ---------- transactie: orders + invoice + koppeling ---------- */
$mysqli->begin_transaction();
try{
  // dubbelverwerking voorkomen
  $stmt=$mysqli->prepare("SELECT order_id FROM mollie_payments WHERE payment_id=?");
  $stmt->bind_param('s',$paymentId); $stmt->execute(); $stmt->bind_result($existingOrderId);
  $stmt->fetch(); $stmt->close();
  if(!is_null($existingOrderId)){
    $mysqli->commit();
    header('Location: '.$CONFIRM_URL.'?order_id='.$existingOrderId.($DEBUG?'&debug=1':'')); exit;
  }

  // orders
  $orderNumber = generateOrderNumber($mysqli);
  $partner_id = null; $klantnummer_partner='';
  $stmt=$mysqli->prepare("INSERT INTO orders (funeral_partner_id, order_number, klantnummer_partner, payment_id) VALUES (?,?,?,?)");
  $stmt->bind_param('isss',$partner_id,$orderNumber,$klantnummer_partner,$paymentId);
  $stmt->execute(); $order_id=$stmt->insert_id; $stmt->close();

  // order_private
  $stmt=$mysqli->prepare("INSERT INTO order_private (order_id, klant_naam, klant_email, klant_telefoon, klant_adres, klantnummer_partner, partner_opmerking) VALUES (?,?,?,?,?,?,?)");
  $naam_enc=encryptField($klant['naam'],$encryption_key);
  $email_enc=encryptField($klant['email'],$encryption_key);
  $tel_enc=encryptField($klant['tel'],$encryption_key);
  $adres_enc=encryptField($klant['adres'],$encryption_key);
  $klantnr_enc=encryptField('',$encryption_key);
  $opm_enc=encryptField($klant['opm'],$encryption_key);
  $stmt->bind_param('issssss',$order_id,$naam_enc,$email_enc,$tel_enc,$adres_enc,$klantnr_enc,$opm_enc);
  $stmt->execute(); $stmt->close();

  // order_products
  $has_ptype=has_col($mysqli,'order_products','product_type');
  $has_pname=has_col($mysqli,'order_products','product_name');
  $has_price=has_col($mysqli,'order_products','unit_price');
  $has_vmeta=has_col($mysqli,'order_products','variant_meta');
  $has_vat=has_col($mysqli,'order_products','vat_percent');

  $cols=['order_id','product_id','quantity']; $types='iii';
  if($has_ptype){ $cols[]='product_type'; $types.='s'; }
  if($has_pname){ $cols[]='product_name'; $types.='s'; }
  if($has_price){ $cols[]='unit_price'; $types.='d'; }
  if($has_vmeta){ $cols[]='variant_meta'; $types.='s'; }
  if($has_vat){   $cols[]='vat_percent'; $types.='d'; }
  $sql="INSERT INTO order_products(".implode(',',$cols).") VALUES(".implode(',',array_fill(0,count($cols),'?')).")";
  $stmt=$mysqli->prepare($sql);
  foreach($items as $it){
    $vals=[ $order_id,(int)$it['product_id'], max(1,(int)$it['qty']) ];
    if($has_ptype) $vals[]=(string)($it['product_type']??'');
    if($has_pname) $vals[]=(string)($it['title']??'Product');
    if($has_price) $vals[]=(float)($it['price']??0);
    if($has_vmeta) $vals[]= isset($it['variant_meta']) ? json_encode($it['variant_meta'],JSON_UNESCAPED_UNICODE) : null;
    if($has_vat)   $vals[]=(float)($it['vat']??21.0);
    $stmt->bind_param($types, ...$vals);
    $stmt->execute();
  }
  $stmt->close();

  // invoice (nummer + totals)
  ensureInvoiceTable($mysqli);
  $invoiceNumber=generateInvoiceNumber($mysqli);
  $now=date('Y-m-d H:i:s');
  $stmt=$mysqli->prepare("INSERT INTO order_invoices (order_id, invoice_number, invoice_date, total_ex, total_vat, total_inc) VALUES (?,?,?,?,?,?)");
  $stmt->bind_param('issddd',$order_id,$invoiceNumber,$now,$vat['sumEx'],$vat['sumVat'],$vat['sumInc']);
  $stmt->execute(); $stmt->close();

  // koppel payment → order
  $status=$payment->status ?? 'paid';
  $method=$payment->method ?? null;
  $paidAt=date('Y-m-d H:i:s');
  $stmt=$mysqli->prepare("UPDATE mollie_payments SET order_id=?, status=?, method=?, paid_at=? WHERE payment_id=? AND (order_id IS NULL OR order_id=0)");
  $stmt->bind_param('issss',$order_id,$status,$method,$paidAt,$paymentId);
  $stmt->execute(); $stmt->close();

  $mysqli->commit();
}catch(Throwable $e){
  $mysqli->rollback();
  error_log("[VERWERK][$REQUEST_ID] transactie fout: ".$e->getMessage());
  goCart('order transactiefout');
}

/* ---------- PDF/UBL ---------- */
$dir=$_SERVER['DOCUMENT_ROOT'].'/pages/orders/pdf';
if(!is_dir($dir)) @mkdir($dir,0775,true);
$logoPath=$_SERVER['DOCUMENT_ROOT'].'/assets/logo/logo.png';
$logoSrc=file_exists($logoPath)?'file://'.$logoPath:'';
$today=date('d/m/Y');

/* Winkeladres voor vermelding */
$winkelAdres = "Beukenlaan 8, 3930 Hamont-Achel, België";

/* Basis CSS */
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
.swatch{display:inline-block;width:16px;height:16px;border:1px solid #999;vertical-align:middle;margin-right:6px}
.voorwaarden{font-size:9pt; line-height:1.45; color:#333}
</style>';

/* Klantblok */
$klantblok=
  '<table class="partnerinfo">'
 .'<tr><td class="kop">Naam</td><td>'.h($klant['naam']).'</td></tr>'
 .'<tr><td class="kop">E-mail</td><td>'.h($klant['email']).'</td></tr>'
 .'<tr><td class="kop">Telefoon</td><td>'.h($klant['tel']).'</td></tr>'
 .'<tr><td class="kop">Adres</td><td>'.nl2br(h($klant['adres'])).'</td></tr>'
 .'</table>';

/* Pakbon tabel (kleur-cel = hele td ingekleurd, label alleen bij naam) */
$rowsHtml=''; $i=1;
$asRows=[]; $totaalAsGram=0.0;

foreach($items as $it){
  $pname=h($it['title']??'Product'); $qty=max(1,(int)$it['qty']); $vm=$it['variant_meta']??null;
  $kleurLabel=''; $kleurHex=null;
  if(is_array($vm) && isset($vm['color']) && $vm['color']!==''){ $kleurHex=resolveColorHexAndLabel((string)$vm['color'],$kleurLabel); }
  $variantExtra=''; if(is_array($vm) && !empty($vm['options']) && is_array($vm['options'])){ $variantExtra='<div class="variant">Opties: '.h(implode(', ',$vm['options'])).'</div>'; }
  if($kleurHex){ $txtColor=textColorForBg($kleurHex); $kleurTd='<td style="background-color:'.h($kleurHex).';color:'.h($txtColor).';text-align:center;font-weight:600">'.($kleurLabel!==''?h($kleurLabel):'&nbsp;').'</td>'; }
  else { $kleurTd='<td>'.($kleurLabel!==''?h($kleurLabel):'—').'</td>'; }
  $rowsHtml.='<tr><td>'.($i++).'</td><td>'.$pname.$variantExtra.'</td>'.$kleurTd.'<td>'.$qty.'</td></tr>';
}

/* Optioneel: as-grammen (alleen als $mysqli_medewerkers bestaat) */
if(isset($mysqli_medewerkers) && $mysqli_medewerkers instanceof mysqli){
  $stmtGram=$mysqli_medewerkers->prepare("SELECT gram FROM product_as WHERE product_id=? LIMIT 1");
  $i=1;
  foreach($items as $it){
    $pid=(int)($it['product_id']??0); $qty=max(1,(int)($it['qty']??1));
    if($pid>0){
      $res=$mysqli_medewerkers->query("SELECT id FROM epoxy_products WHERE id={$pid} AND sub_category='uitvaart' LIMIT 1");
      $isEpoxy=$res && $res->num_rows>0; if($res){$res->close();}
      if($isEpoxy && $stmtGram){
        $stmtGram->bind_param('i',$pid);
        if($stmtGram->execute()){
          $stmtGram->bind_result($gramPerStuk);
          if($stmtGram->fetch() && $gramPerStuk!==null){
            $gram=(float)$gramPerStuk*$qty; $totaalAsGram+=$gram;
            $asRows[]=['nr'=>$i,'product'=>$it['title'],'gram_per_stuk'=>(float)$gramPerStuk,'aantal'=>$qty,'totaal'=>$gram];
          }
        }
        $stmtGram->free_result();
      }
    }
    $i++;
  }
  if($stmtGram){ $stmtGram->close(); }
}

/* Logistiek-blok op pakbon */
$asAanleverenTekst='—';
if ($ashesMethod==='zelf_bezorgen') {
  $asAanleverenTekst='Zelf bezorgen – Winkeladres: '.h($winkelAdres);
} elseif ($ashesMethod==='afgehaald_door_ons') {
  $ct=($collectDate?date('d/m/Y',strtotime($collectDate)):'n.t.b.');
  $asAanleverenTekst='Wordt afgehaald door ons – Afspraak: '.h($ct.($collectTime?' '.$collectTime:''));
  if ($deliveryFeeInc>0) {
    $asAanleverenTekst.='<br>Afstand: '.($distCapKm!==null?number_format($distCapKm,1,',','.').' km':'—');
    $asAanleverenTekst.=' &nbsp;|&nbsp; Kost (incl. btw): <sup>€</sup>'.number_format($deliveryFeeInc,2,',','.');
    if ($ratePer10!==null) { $asAanleverenTekst.=' &nbsp;|&nbsp; Tarief: <sup>€</sup>'.number_format($ratePer10,2,',','.').' / 10 km'; }
    if ($distMethod) { $asAanleverenTekst.=' &nbsp;|&nbsp; bron: '.h($distMethod); }
  }
} elseif ($ashesMethod==='koerier') { $asAanleverenTekst='Verzenden via koerier (op eigen risico)'; }

$afgewerktProductTekst = ($finishedMethod==='afhalen_winkel' ? 'Afhalen in winkel' : ($finishedMethod==='bezorgen' ? 'Bezorgen aan huis' : '—'));
if ($finishedMethod==='bezorgen') {
  $afgewerktProductTekst.='<br>Afstand: '.($distCapKm!==null?number_format($distCapKm,1,',','.').' km':'—');
  $afgewerktProductTekst.=' &nbsp;|&nbsp; Kost (incl. btw): '.($deliveryFeeInc>0?('<sup>€</sup>'.number_format($deliveryFeeInc,2,',','.')):'<sup>€</sup>0,00');
  if ($ratePer10!==null) { $afgewerktProductTekst.=' &nbsp;|&nbsp; Tarief: <sup>€</sup>'.number_format($ratePer10,2,',','.').'/ 10 km'; }
  if ($distMethod) { $afgewerktProductTekst.=' &nbsp;|&nbsp; bron: '.h($distMethod); }
}

/* Pakbon PDF */
$pakbonPath=$_SERVER['DOCUMENT_ROOT'].'/pages/orders/pdf/pakbon_'.$order_id.'.pdf';
$pakbonHtml=$baseCss.($logoSrc?'<img src="'.$logoSrc.'" class="logo" alt="Logo">':'')
 .'<h1>Pakbon</h1>'
 .'<table class="partnerinfo"><tr><td class="kop">Ordernummer</td><td>'.h($orderNumber).'</td></tr><tr><td class="kop">Datum</td><td>'.$today.'</td></tr></table>'
 .'<h3>Klant</h3>'.$klantblok
.(!empty($klant['opm'])?'<h3>Opmerking</h3><div class="note">'.nl2br(h($klant['opm'])).'</div>':'')
.'<h3>Logistiek</h3>'
.'<table class="partnerinfo">'
  .'<tr><td class="kop">As aanleveren</td><td>'.$asAanleverenTekst.'</td></tr>'
  .'<tr><td class="kop">Afgewerkt product</td><td>'.$afgewerktProductTekst.'</td></tr>'
.'</table>'
.'<h3>Bestelde Producten</h3>'
 .'<table class="productentabel"><tr><th>#</th><th>Product</th><th>Kleur</th><th>Aantal</th></tr>'.$rowsHtml.'</table>';

if(!empty($asRows)){
  $pakbonHtml.='<h3>As-overzicht (epoxy)</h3><table class="productentabel"><tr><th>#</th><th>Product</th><th>Gram/stuk</th><th>Aantal</th><th>Totaal gram</th></tr>';
  foreach($asRows as $r){
    $pakbonHtml.='<tr><td>'.$r['nr'].'</td><td>'.h($r['product']).'</td><td>'.number_format($r['gram_per_stuk'],2,',','.').'</td><td>'.$r['aantal'].'</td><td>'.number_format($r['totaal'],2,',','.').'</td></tr>';
  }
  $pakbonHtml.='<tr><td colspan="4" class="right" style="font-weight:700">Totaal te leveren as</td><td style="font-weight:700">'.number_format($totaalAsGram,2,',','.').' g</td></tr></table>';
}
$mpdf=new Mpdf(); $mpdf->WriteHTML($pakbonHtml); $mpdf->Output($pakbonPath,\Mpdf\Output\Destination::FILE);

/* Factuur PDF */
/* Factuur PDF (zonder voorwaarden embedden) */
$factuurPath = $_SERVER['DOCUMENT_ROOT'].'/pages/orders/pdf/factuur_'.$order_id.'.pdf';
$factRows = '';
foreach ($vat['lines'] as $idx=>$L){
  $unitInc=(float)$L['unit_price_inc']; $rate=(float)$L['rate']; $qty=(int)$L['qty'];
  $unitEx=round($unitInc/(1+$rate/100),4); $lineEx=round($unitEx*$qty,2);
  $lineVat=round($lineEx*($rate/100),2); $lineInc=round($lineEx+$lineVat,2);
  $factRows.='<tr><td>'.($idx+1).'</td><td>'.h($L['title']).'</td><td class="right">'.$qty
    .'</td><td class="right">'.number_format($unitInc,2,',','.').'</td><td class="right">'
    .number_format($rate,2,',','.').' %</td><td class="right">'.number_format($lineInc,2,',','.').'</td></tr>';
}
$btwTabel='';
foreach($vat['perRate'] as $r=>$tot){
  $btwTabel.='<tr><td class="right">'.number_format($tot['rate'],2,',','.').' %</td>'
            .'<td class="right">'.number_format($tot['ex'],2,',','.').'</td>'
            .'<td class="right">'.number_format($tot['vat'],2,',','.').'</td></tr>';
}
$supplierInfo='<table class="partnerinfo"><tr><td class="kop">Leverancier</td><td>'.h($supplier['name']).'</td></tr>'
             .'<tr><td class="kop">BTW-nummer</td><td>'.h($supplier['vat']).'</td></tr>'
             .'<tr><td class="kop">Adres</td><td>'.h(trim(($supplier['street']??'').' '.($supplier['zip']??'').' '.($supplier['city']??''))).'</td></tr></table>';

$factuurHtml = $baseCss.($logoSrc?'<img src="'.$logoSrc.'" class="logo" alt="Logo">':'').'
  <h1>Factuur</h1>
  <table class="partnerinfo">
    <tr><td class="kop">Factuurnummer</td><td>'.h($invoiceNumber).'</td></tr>
    <tr><td class="kop">Factuurdatum</td><td>'.$today.'</td></tr>
    <tr><td class="kop">Ordernummer</td><td>'.h($orderNumber).'</td></tr>
  </table>
  <h3>Leverancier</h3>'.$supplierInfo.'
  <h3>Klant</h3>'.$klantblok.'
  <h3>Factuurlijnen</h3>
  <table class="productentabel">
    <tr><th>#</th><th>Omschrijving</th><th class="right">Aantal</th><th class="right">Eenheidsprijs</th><th class="right">BTW</th><th class="right">Totaal incl&nbsp;<sup>€</sup></th></tr>'
    .$factRows.
    '<tr><td colspan="5" class="right" style="font-weight:700">Subtotaal (excl.)</td><td class="right" style="font-weight:700">'.number_format($vat['sumEx'],2,',','.').'</td></tr>
     <tr><td colspan="5" class="right" style="font-weight:700">BTW totaal</td><td class="right" style="font-weight:700">'.number_format($vat['sumVat'],2,',','.').'</td></tr>
     <tr><td colspan="5" class="right" style="font-weight:700">Totaal (incl.)</td><td class="right" style="font-weight:700">'.number_format($vat['sumInc'],2,',','.').'</td></tr>
  </table>
  <h3>BTW-overzicht</h3>
  <table class="productentabel"><tr><th>Tarief</th><th>Belastbaar&nbsp;<sup>€</sup></th><th>BTW&nbsp;<sup>€</sup></th></tr>'.$btwTabel.'</table>
  <div style="margin-top:12px;font-size:10pt;color:#444">Betaling ontvangen via Mollie ('.h($paymentId).').</div>';

$mpdfInv = new Mpdf();
$mpdfInv->WriteHTML($factuurHtml);
$mpdfInv->Output($factuurPath, \Mpdf\Output\Destination::FILE);

/* Algemene Voorwaarden – los PDF in-memory (NIET opslaan) */
$termsPathHtml = $_SERVER['DOCUMENT_ROOT'].'/legal/algemene-voorwaarden.html';
$termsPathTxt  = $_SERVER['DOCUMENT_ROOT'].'/legal/algemene-voorwaarden.txt';

$voorwaardenContent = '';
if (is_file($termsPathHtml)) {
  $voorwaardenContent = (string)file_get_contents($termsPathHtml);
} elseif (is_file($termsPathTxt)) {
  $voorwaardenContent = '<pre style="white-space:pre-wrap;margin:0">'.
                        nl2br(h((string)file_get_contents($termsPathTxt))).'</pre>';
}

$voorwaardenPdfBin = null; // ← dit houden we in-memory
if ($voorwaardenContent !== '') {
  $voorwaardenHtml = $baseCss.'<h3>Algemene voorwaarden</h3><div class="voorwaarden">'.$voorwaardenContent.'</div>';
  $mpdfTerms = new Mpdf();
  $mpdfTerms->WriteHTML($voorwaardenHtml);
  $voorwaardenPdfBin = $mpdfTerms->Output('', \Mpdf\Output\Destination::STRING_RETURN); // binaire string
}

/* UBL 2.1 (EN 16931) */
$ublXml  = buildUBL($supplier,$customer,$invoiceNumber,date('Y-m-d'),$vat,'EUR');
$ublPath = $_SERVER['DOCUMENT_ROOT'].'/pages/orders/pdf/factuur_'.$order_id.'.xml';
file_put_contents($ublPath,$ublXml);

/* Paden naar DB (alleen pakbon/factuur/ubl) */
$pakbonPathDb  = is_file($pakbonPath)  ? $pakbonPath  : null;
$factuurPathDb = is_file($factuurPath) ? $factuurPath : null;
$ublPathDb     = is_file($ublPath)     ? $ublPath     : null;
if ($pakbonPathDb===null){ error_log("[VERWERK][$REQUEST_ID] Pakbon ontbreekt: $pakbonPath"); goCart('pakbon genereren mislukt'); }
upsertOrderFileRefs($mysqli,$order_id,$pakbonPathDb,$factuurPathDb,$ublPathDb);


/* ---------- mails ---------- */
try{
  $titlemail = "Nieuwe bestelling (particulier): $orderNumber";
  $bodymail  = "Beste Medewerker,<br><br>Er werd een nieuwe bestelling geplaatst en betaald.<br><br>Order: <strong>".h($orderNumber)."</strong>.<br><br>Pakbon, factuur en UBL in bijlage (indien beschikbaar).";
  $headermail = "Nieuwe Bestelling";

  $mail=new PHPMailer(true);
  $mail->setFrom('info@windelsgreen-decoresin.com','Windels Green & Deco Resin');
  $mail->addReplyTo('webshop@windelsgreen-decoresin.com','Webshop');
  $mail->CharSet='UTF-8';
  if($dev == True){ $mail->addAddress('admin@windelsgreen-decoresin.com','Andy Windels'); }
  else{ foreach(['webshop@windelsgreen-decoresin.com','windelsfranky@gmail.com'] as $rcpt){ $mail->addAddress($rcpt); } }
  $mail->isHTML(true);
  $mail->Subject=$titlemail;
  $mail->Body = str_replace(['{titlemail}','{headermail}','{bodymail}'],[$titlemail,$headermail,$bodymail],file_get_contents($_SERVER['DOCUMENT_ROOT'].'/emailtemplate.php'));
  $mail->AltBody="Nieuwe bestelling – $orderNumber";

  // ▼ Bijlagen OBV DB (voorkomt verkeerde koppeling)
  $files = getOrderFileRefs($mysqli,$order_id);
  if($files['factuur'] && is_file($files['factuur'])){ $mail->addAttachment($files['factuur']); }
  if($files['ubl'] && is_file($files['ubl'])){ $mail->addAttachment($files['ubl'],'factuur_'.$order_id.'.xml','base64','application/xml'); }
  if($files['pakbon'] && is_file($files['pakbon'])){ $mail->addAttachment($files['pakbon']); } // verplicht
// Losse voorwaarden (indien beschikbaar) als in-memory bijlage
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
}catch(MailException $e){ error_log("[VERWERK][$REQUEST_ID] teammail fout: ".$e->getMessage()); }

try{
  $titlemail = "Bevestiging bestelling $orderNumber";
  $headermail = "Bedankt voor uw bestelling";
  $statusUrl   = $HOST.'/pages/orders/order-status.php?order='.urlencode($orderNumber).'&email='.urlencode($klant['email']);

  // Logistiekblok voor klantmail
  $logiKlant  = "<br><strong>Logistiek</strong><br>";
  $logiKlant .= "As aanleveren: ";
  if ($ashesMethod==='zelf_bezorgen') { $logiKlant .= 'Zelf bezorgen – Winkeladres: '.h($winkelAdres);
  } elseif ($ashesMethod==='afgehaald_door_ons') {
    $ct=($collectDate?date('d/m/Y',strtotime($collectDate)):'n.t.b.');
    $logiKlant.='Wordt afgehaald door ons – Afspraak: '.h($ct.($collectTime?' '.$collectTime:''));
    if ($deliveryFeeInc>0) {
      $logiKlant.=" (".($distCapKm!==null?number_format($distCapKm,1,',','.').' km':'—').", kost: <sup>€</sup>".number_format($deliveryFeeInc,2,',','.');
      if ($ratePer10!==null) { $logiKlant.=", tarief: <sup>€</sup>".number_format($ratePer10,2,',','.')."/10 km"; }
      $logiKlant.=($distMethod ? ", bron: ".h($distMethod) : "").")";
    }
  } elseif ($ashesMethod==='koerier') { $logiKlant .= 'Koerier (op eigen risico)'; } else { $logiKlant .= '—'; }
  $logiKlant .= "<br>Afgewerkt product: ";
  if ($finishedMethod==='afhalen_winkel') { $logiKlant .= 'Afhalen in winkel';
  } elseif ($finishedMethod==='bezorgen') {
    $logiKlant .= 'Bezorgen aan huis';
    $logiKlant .= " (".($distCapKm!==null?number_format($distCapKm,1,',','.').' km':'—').", ";
    $logiKlant .= "kost: ".($deliveryFeeInc>0?('<sup>€</sup>'.number_format($deliveryFeeInc,2,',','.')):'<sup>€</sup>0,00');
    if ($ratePer10!==null) { $logiKlant .= ", tarief: <sup>€</sup>".number_format($ratePer10,2,',','.')."/10 km"; }
    if ($distMethod) { $logiKlant .= ", bron: ".h($distMethod); }
    $logiKlant .= ")";
  } else { $logiKlant .= '—'; }
  $logiKlant .= "<br><br>";

  $bodymail  = "Beste ".h($klant['naam']).",<br><br>";
  $bodymail .= "Hartelijk dank voor uw bestelling. In de bijlage vindt u uw <strong>factuur (PDF + UBL, indien beschikbaar)</strong> en de <strong>pakbon</strong>.<br><br>";
  $bodymail .= "Order: <strong>".h($orderNumber)."</strong>.<br><br>";
  $bodymail .= "U kan de as op de volgende manieren aan ons bezorgen:<br><br><ul>";
  $bodymail .= "<li><strong>Zelf afgeven</strong> tijdens onze openingsuren: <strong>".h($winkelAdres)."</strong></li>";
  $bodymail .= "<li><strong>Verzenden via koerier:</strong> kan, maar gebeurt <u>op eigen risico</u>.</li>";
  $bodymail .= "<li><strong>Afhaling door ons:</strong> mogelijk binnen <u>50 km</u> rond de winkel.</li>";
  $bodymail .= "</ul><br>".$logiKlant;
  $bodymail .= "Status van uw bestelling wijzigen kan via:<br><br>";
  $bodymail .= "<a href='".h($statusUrl)."' style='display:inline-block;padding:10px 20px;background:#2d4739;color:#fff;text-decoration:none;border-radius:6px;font-weight:600;'>Status aanpassen</a><br><br>";
  $bodymail .= "Werkt de knop niet? Plak deze link in uw browser:<br>";
  $bodymail .= "<a href='".h($statusUrl)."'>".h($statusUrl)."</a><br><br>";
  $bodymail .= "Met vriendelijke groet,<br>Windels Green & Deco Resin";

  $mail=new PHPMailer(true);
  $mail->setFrom('info@windelsgreen-decoresin.com','Windels Green & Deco Resin');
  $mail->addReplyTo('webshop@windelsgreen-decoresin.com','Webshop');
  $mail->CharSet='UTF-8';
  if($dev == True){ $mail->addAddress('andywindels5@gmail.com','Andy Windels'); }
  else{ $mail->addAddress($klant['email'],$klant['naam']); }
  $mail->isHTML(true);
  $mail->Subject="Factuur & bevestiging – $invoiceNumber / $orderNumber";
  $mail->Body = str_replace(['{titlemail}','{headermail}','{bodymail}'],[$titlemail,$headermail,$bodymail],file_get_contents($_SERVER['DOCUMENT_ROOT'].'/emailtemplate.php'));
  $mail->AltBody="Bevestiging bestelling – Order: $orderNumber – Factuur: $invoiceNumber";

  // ▼ Bijlagen OBV DB
  $files = getOrderFileRefs($mysqli,$order_id);
  if($files['factuur'] && is_file($files['factuur'])){ $mail->addAttachment($files['factuur']); }
  if($files['ubl'] && is_file($files['ubl'])){ $mail->addAttachment($files['ubl'],'factuur_'.$order_id.'.xml','base64','application/xml'); }
  if($files['pakbon'] && is_file($files['pakbon'])){ $mail->addAttachment($files['pakbon']); } // verplicht

  // Losse voorwaarden (indien beschikbaar) als in-memory bijlage
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
}catch(MailException $e){ error_log("[VERWERK][$REQUEST_ID] klantmail fout: ".$e->getMessage()); }

/* ---------- opruimen & door ---------- */
$_SESSION['cart']['items'] = [];
unset($_SESSION['last_payment_id']);
@unlink($_SERVER['DOCUMENT_ROOT'].'/storage/checkout/'.$paymentId.'.json');

header('Location: '.$CONFIRM_URL.'?order_id='.$order_id.($DEBUG?'&debug=1':''));
exit;
