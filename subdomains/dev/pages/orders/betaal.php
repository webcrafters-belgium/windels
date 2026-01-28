<?php
/* ============================================================
   Betaalcontroller (Mollie) – create & handle return/webhook
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

$dotenv = Dotenv::createImmutable(dirname($_SERVER['DOCUMENT_ROOT']));
$dotenv->load();

$MOLLIE_KEY = $_ENV['MOLLIE_API_KEY'] ?? getenv('MOLLIE_API_KEY');

$HOST        = 'https://'.$_SERVER['HTTP_HOST'];
$CART_URL    = '/pages/orders/cart.php';
$RETURN_SELF = $HOST.'/pages/orders/betaal.php';
$PROCESS_URL = $HOST.'/pages/orders/bestel_verwerk.php';
$DEBUG       = isset($_GET['debug']) && $_GET['debug'] === '1';
$REQUEST_ID  = bin2hex(random_bytes(6));

function goCart($reason){
  global $CART_URL,$DEBUG,$REQUEST_ID;
  error_log("[BETAAL][$REQUEST_ID] -> cart: ".$reason);
  $url = $CART_URL.'?why='.rawurlencode($reason).($DEBUG?'&debug=1':'');
  if($DEBUG){
    header('Content-Type: text/plain; charset=utf-8');
    echo "DEBUG: zou redirecten naar $url\nReden: $reason\n"; exit;
  }
  header('Location: '.$url); exit;
}
function h($s){ return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8'); }

if(!isset($mysqli) || !($mysqli instanceof mysqli)){ http_response_code(500); die('DB-verbinding ontbreekt.'); }
if(empty($MOLLIE_KEY)){ http_response_code(500); die('Mollie API key ontbreekt.'); }

$mollie = new MollieApiClient();
$mollie->setApiKey($MOLLIE_KEY);

/* ----------------------------------------------------------
   OPENINGSUREN + HELPERS (server-side validaties & tarieven)
   ---------------------------------------------------------- */
// Zelfde tabellen als in de checkout (0=Zon..6=Zat)
$SUMMER_HOURS = [
  0=>'',
  1=>'19:00–21:00', 2=>'19:00–21:00', 3=>'19:00–21:00',
  4=>'10:00–21:00', 5=>'10:00–21:00', 6=>'10:00–18:00'
];
$WINTER_HOURS = [
  0=>'',
  1=>'19:00–21:00', 2=>'19:00–21:00', 3=>'19:00–21:00',
  4=>'10:00–18:00', 5=>'10:00–18:00', 6=>'10:00–18:00'
];

/** Geef urenstring "HH:MM–HH:MM" of lege string als gesloten */
function hoursForDate(string $ymd, array $SUMMER, array $WINTER): string {
  $t = strtotime($ymd.' 00:00:00');
  if($t===false) return '';
  $m = (int)date('n',$t); // 1..12
  $w = (int)date('w',$t); // 0..6 (0=Zon)
  $table = ($m>=6 && $m<=10) ? $SUMMER : $WINTER;
  return $table[$w] ?? '';
}

/** Parse "HH:MM–HH:MM" naar ['start'=>'HH:MM','end'=>'HH:MM'] of null */
function parseHoursRange(?string $s): ?array {
  if(!$s || strpos($s,'–')===false) return null;
  [$a,$b] = array_map('trim', explode('–',$s,2));
  if(!preg_match('/^\d{2}:\d{2}$/',$a) || !preg_match('/^\d{2}:\d{2}$/',$b)) return null;
  return ['start'=>$a,'end'=>$b];
}

/** Check of tijd binnen [start,end] ligt (gesloten intervallen) */
function timeInRange(string $hhmm, array $range): bool {
  return ($hhmm >= $range['start'] && $hhmm <= $range['end']);
}

/** Reken leveringskost: ceil(km/10)*tarief, met cap 50 km */
function deliveryFeeFromKm(float $km, float $ratePer10): float {
  if($km<=0) return 0.0;
  $capped = min($km, 50.0);
  $blocks = (int)ceil($capped / 10.0);
  return $blocks * $ratePer10;
}

/** BTW uit incl. bedrag (21/121) */
function vatFromGross(float $g, float $rate=0.21): float {
  return $g * ($rate/(1+$rate));
}

/* ---------------------------
   Webhook endpoint (same file)
   ---------------------------*/
if (isset($_GET['webhook']) && $_GET['webhook'] === '1') {
  $paymentId = $_POST['id'] ?? null;
  if (!$paymentId) { http_response_code(400); exit; }
  try {
    $payment = $mollie->payments->get($paymentId);
    $status  = $payment->status ?? '';
    $method  = $payment->method ?? null;
    $paidAt  = ($payment->isPaid() || (method_exists($payment,'isAuthorized') && $payment->isAuthorized())) ? date('Y-m-d H:i:s') : null;

    $stmt=$mysqli->prepare("UPDATE mollie_payments SET status=?, method=?, paid_at=? WHERE payment_id=?");
    $stmt->bind_param('ssss',$status,$method,$paidAt,$paymentId);
    $stmt->execute(); $stmt->close();
  } catch (Throwable $e) {
    error_log("[BETAAL][$REQUEST_ID] webhook error: ".$e->getMessage());
  }
  http_response_code(200); exit;
}

/* ---------------------------
   Return vanuit Mollie (optioneel)
   ---------------------------*/
if (isset($_GET['return']) && $_GET['return']=='1') {
  $rt  = isset($_GET['rt'])  ? trim($_GET['rt'])  : '';
  $pid = isset($_GET['pid']) ? trim($_GET['pid']) : '';
  if ($rt==='' || $pid==='') { goCart('return zonder pid/rt'); }

  try {
    $payment = $mollie->payments->get($pid);
  } catch (Throwable $e){
    header('Content-Type: text/plain; charset=utf-8');
    echo "Mollie foutmelding:\n\n".$e->getMessage();
    exit;
  }

  if (!($payment->isPaid() || (method_exists($payment,'isAuthorized') && $payment->isAuthorized()))) {
    goCart('betaling niet voltooid');
  }

  // update status
  $status = $payment->status ?? '';
  $method = $payment->method ?? null;
  $paidAt = date('Y-m-d H:i:s');
  $stmt=$mysqli->prepare("UPDATE mollie_payments SET status=?, method=?, paid_at=? WHERE payment_id=?");
  $stmt->bind_param('ssss',$status,$method,$paidAt,$pid);
  $stmt->execute(); $stmt->close();

  // succes → ga naar orderverwerking
  $url = $PROCESS_URL.'?pid='.rawurlencode($pid).'&rt='.rawurlencode($rt).($DEBUG?'&debug=1':'');
  header('Location: '.$url); exit;
}

/* ---------------------------
   Nieuwe betaling starten
   ---------------------------*/
if (empty($_SESSION['cart']['items']) || !is_array($_SESSION['cart']['items'])) {
  goCart('sessiecart leeg bij start');
}

/* CSRF */
if (empty($_POST['csrf']) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string)$_POST['csrf'])) {
  goCart('csrf_token ongeldig');
}

/* Klant- & checkoutvelden uit POST */
$klant_naam   = trim($_POST['klant_naam'] ?? '');
$klant_email  = trim($_POST['klant_email'] ?? '');
$klant_tel    = trim($_POST['klant_telefoon'] ?? '');
$klant_adres  = trim($_POST['klant_adres'] ?? '');
$klant_land   = strtoupper(trim($_POST['klant_land'] ?? 'BE'));
$opmerking    = trim($_POST['partner_opmerking'] ?? '');
if (strlen($opmerking) > 5000) $opmerking = substr($opmerking, 0, 5000);

/* Validatie klant */
if ($klant_naam==='' || !filter_var($klant_email, FILTER_VALIDATE_EMAIL)) {
  goCart('validering klantgegevens');
}

/* Keuzes as/afgewerkt product + afhaalmoment */
$ashes_method    = $_POST['ashes_delivery_method'] ?? '';
$finished_method = $_POST['finished_delivery_method'] ?? '';

$collect_date = $_POST['ashes_collect_date'] ?? null; // YYYY-MM-DD
$collect_time = $_POST['ashes_collect_time'] ?? null; // HH:MM

if ($ashes_method === 'afgehaald_door_ons') {
  // verplichte velden
  if (empty($collect_date) || empty($collect_time)) {
    goCart('afhaalmoment ontbreekt (datum/uur)');
  }
  // datum niet in verleden
  if (strtotime($collect_date) === false || $collect_date < date('Y-m-d')) {
    goCart('afhaaldatum ongeldig');
  }
  // openingsuren check
  $hoursStr = hoursForDate($collect_date, $SUMMER_HOURS, $WINTER_HOURS);
  $range    = parseHoursRange($hoursStr);
  if (!$range) {
    goCart('afhaaldag is gesloten');
  }
  if (!preg_match('/^\d{2}:\d{2}$/', (string)$collect_time) || !timeInRange($collect_time, $range)) {
    goCart('afhaaluur buiten openingsuren');
  }
}

/* Levering & afstand (van checkout hidden fields – worden herberekend server-side) */
$posted_fee        = (float)($_POST['finished_delivery_fee'] ?? $_POST['delivery_cost_total'] ?? 0);
$distance_raw_km   = isset($_POST['distance_km_raw'])    ? (float)$_POST['distance_km_raw']    : 0.0;
$distance_cap_km   = isset($_POST['distance_km_capped']) ? (float)$_POST['distance_km_capped'] : 0.0;
$distance_method   = $_POST['distance_method'] ?? null;

/* Som producten uit sessie */
$items = array_values($_SESSION['cart']['items']);
$products_total = 0.0;
$cartSnapshot   = [];
foreach ($items as $it) {
  $qty   = max(1,(int)($it['qty'] ?? 1));
  $price = (float)($it['price'] ?? 0);
  $line  = $price * $qty;
  $products_total += $line;
  $cartSnapshot[] = [
    'product_id'   => (int)($it['product_id'] ?? 0),
    'product_type' => (string)($it['product_type'] ?? ''),
    'title'        => (string)($it['title'] ?? $it['name'] ?? 'Product'),
    'price'        => (float)$price,
    'qty'          => (int)$qty,
    'variant_meta' => isset($it['variant_meta']) ? (is_array($it['variant_meta']) ? $it['variant_meta'] : json_decode((string)$it['variant_meta'], true)) : null,
    'vat'          => 21.0,
  ];
}

/* ---------- SERVER-SIDE VALIDATIE & HERBEREKENING LEVERINGSKOST ---------- */
// Tarief: default <sup>€</sup>3/10km, maar combo "as afgehaald door ons" + "finished = bezorgen" => <sup>€</sup>6/10km
$ratePer10 = ($ashes_method === 'afgehaald_door_ons' && $finished_method === 'bezorgen') ? 6.00 : 3.00;

// Km-kost toepassen wanneer óf finished=bezorgen óf as door ons wordt afgehaald
$needsDistance = ($finished_method === 'bezorgen') || ($ashes_method === 'afgehaald_door_ons');
$server_fee    = $needsDistance ? deliveryFeeFromKm($distance_cap_km, $ratePer10) : 0.0;
$delivery_fee  = round($server_fee, 2);

// (optioneel) harde validatie wanneer afstand verplicht is
if ($needsDistance && $distance_cap_km <= 0) {
  goCart('afstand vereist voor levering/afhalen');
}


/* BTW en totalen opnieuw opbouwen – server is bron van waarheid */
$vat_products    = vatFromGross((float)$products_total, 0.21);
$vat_delivery    = vatFromGross((float)$delivery_fee,   0.21);
$vat_order_total = $vat_products + $vat_delivery;

$grand_total    = $products_total + $delivery_fee;
$net_order_total= $grand_total - $vat_order_total;

/* Sanity-check totaal */
if ($grand_total <= 0) { goCart('totaal <= 0'); }

/* Beschrijving & return token */
$description = 'Bestelling herinneringsproducten';
$returnToken = bin2hex(random_bytes(8));

/* Metadata naar DB (rijke data) */
$dbMeta = [
  'flow'        => 'public_checkout',
  'tmp_session' => session_id(),
  'cart'        => $cartSnapshot,
  'customer'    => [
    'name'=>$klant_naam, 'email'=>$klant_email, 'tel'=>$klant_tel,
    'adres'=>$klant_adres, 'land'=>$klant_land, 'opm'=>$opmerking
  ],
  'delivery' => [
    'ashes_method'     => $ashes_method,
    'finished_method'  => $finished_method,
    'fee_incl'         => $delivery_fee,
    'distance_raw_km'  => $distance_raw_km,
    'distance_cap_km'  => $distance_cap_km,
    'distance_method'  => $distance_method,
    'collect_date'     => $collect_date,    // indien van toepassing
    'collect_time'     => $collect_time,    // indien van toepassing
    'rate_per_10km'    => $ratePer10,       // 3.00 of 6.00
  ],
  'totals' => [
    'products_incl' => $products_total,
    'delivery_incl' => $delivery_fee,
    'grand_incl'    => $grand_total,
    'vat_products'  => $vat_products,
    'vat_delivery'  => $vat_delivery,
    'vat_total'     => $vat_order_total,
    'net_total'     => $net_order_total,
  ],
];

/* Compacte & GDPR-veilige metadata naar Mollie */
$customerHash = substr(hash('sha256', strtolower(trim($klant_email))), 0, 10);
$firstTitle   = isset($cartSnapshot[0]['title']) ? (string)$cartSnapshot[0]['title'] : 'Bestelling';
$orderHint    = $firstTitle . (count($cartSnapshot) > 1 ? ' +'.(count($cartSnapshot)-1) : '');

$metaMollie = [
  'flow'         => 'public_checkout',
  'tmp_session'  => session_id(),
  'return_token' => $returnToken,
  'order_hint'   => mb_strimwidth($orderHint, 0, 60, '…', 'UTF-8'),
  'customer_hint'=> $customerHash,
  'delivery'     => [
    'ashes'    => (string)$ashes_method,
    'finished' => (string)$finished_method,
    'dist_km'  => ($distance_cap_km>0 ? round($distance_cap_km, 1) : null),
    'fee_inc'  => round($delivery_fee, 2),
    'rate'     => $ratePer10,
    'cdate'    => $collect_date,
    'ctime'    => $collect_time,
  ],
  'totals'       => [
    'grand_incl' => round((float)$grand_total, 2),
  ],
];

$metaMollieJson = json_encode($metaMollie, JSON_UNESCAPED_UNICODE);
if (strlen($metaMollieJson) > 1500) {
  // ultra-compact fallback
  $metaMollie = [
    'flow' => 'public_checkout',
    'rt'   => $returnToken,
    'oh'   => mb_strimwidth($orderHint, 0, 40, '…', 'UTF-8'),
    'cust' => $customerHash,
    'dlv'  => [
      'f' => (string)$finished_method,
      'k' => ($distance_cap_km>0 ? round($distance_cap_km,1) : null),
      'c' => round($delivery_fee,2),
      'r' => $ratePer10,
      'd' => $collect_date,
      't' => $collect_time,
    ],
    'tot'  => round((float)$grand_total,2),
  ];
}

try {
  $amountStr = number_format($grand_total, 2, '.', '');
  $payment = $mollie->payments->create([
    "amount"      => ["currency"=>"EUR", "value"=>$amountStr],
    "description" => $description,
    // We sturen direct naar bestel_verwerk.php met token-resolutie (pid=return)
    "redirectUrl" => $PROCESS_URL.'?pid=return&rt='.$returnToken,
    // Webhook naar ditzelfde bestand
    "webhookUrl"  => $RETURN_SELF.'?webhook=1',
    "metadata"    => $metaMollie,                                // ← juist niveau
    "locale"      => ($klant_land==='NL' ? "nl_NL" : "nl_BE"),   // ← niet in metadata!
  ]);

  // Sla payment op in DB met rijke metadata
  $stmt = $mysqli->prepare("
    INSERT INTO mollie_payments
    (payment_id, return_token, status, amount, currency, method, description, customer_name, customer_email, metadata)
    VALUES (?,?,?,?,?,?,?,?,?,?)
  ");
  $pid       = (string)$payment->id;
  $pstatus   = (string)$payment->status;
  $pcurrency = (string)$payment->amount->currency;
  $pmethod   = null;
  $pdesc     = $description;
  $cname     = (string)$klant_naam;
  $cmail     = (string)$klant_email;
  $metaJson  = json_encode($dbMeta, JSON_UNESCAPED_UNICODE);
  $stmt->bind_param('ssssssssss',$pid,$returnToken,$pstatus,$amountStr,$pcurrency,$pmethod,$pdesc,$cname,$cmail,$metaJson);
  $stmt->execute(); $stmt->close();

} catch (Throwable $e) {
  error_log("[BETAAL][$REQUEST_ID] create error: ".$e->getMessage());
  goCart('mollie create error');
}

/* Fallback snapshot op disk (handig bij debugging) */
$SNAP_DIR = $_SERVER['DOCUMENT_ROOT'].'/storage/checkout';
if (!is_dir($SNAP_DIR)) { @mkdir($SNAP_DIR, 0775, true); }
@file_put_contents($SNAP_DIR.'/'.$payment->id.'.json', json_encode([
  'cart'          => $cartSnapshot,
  'customer'      => $dbMeta['customer'],
  'delivery'      => $dbMeta['delivery'],
  'totals'        => $dbMeta['totals'],
  'amount'        => $amountStr,
  'created'       => date('c'),
  'session'       => session_id(),
  'return_token'  => $returnToken,
], JSON_UNESCAPED_UNICODE));

/* Sessies voor gemak */
$_SESSION['checkout_customer'] = [
  'naam'=>$klant_naam,'email'=>$klant_email,'tel'=>$klant_tel,'adres'=>$klant_adres,'opm'=>$opmerking,
  'expected_amount'=>$amountStr
];
$_SESSION['last_payment_id'] = $payment->id;

/* Stuur klant naar Mollie */
$checkoutUrl = $payment->getCheckoutUrl();
if ($DEBUG) {
  header('Content-Type: text/plain; charset=utf-8');
  echo "DEBUG MODE\n\nGa naar Mollie: ".($checkoutUrl ?? '(null)')."\n\nPayment ID: ".$payment->id."\nAmount: ".$amountStr."\n";
  exit;
}
if (!$checkoutUrl) {
  error_log("[BETAAL][$REQUEST_ID] geen checkoutUrl ontvangen van Mollie voor payment ".$payment->id);
  goCart('geen checkout url');
}
header('Location: '.$checkoutUrl);
exit;
