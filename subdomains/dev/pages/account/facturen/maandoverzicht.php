<?php

include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
session_start();
if(!isset($_SESSION['partner_id'])){header("Location: /pages/account/login.php");exit;}
$partner_id=(int)$_SESSION['partner_id'];

/* === Facturatie-instellingen ophalen voor defaults === */
$billing_preference = null; // 'per_order' | 'weekly' | 'monthly'
$billing_weekday    = null; // 1..7 (ma=1)
$billing_month_day  = null; // 1..31
$billing_recipient  = 'customer'; // 'partner' of 'customer'

if ($stmt = $mysqli->prepare("
    SELECT billing_preference, billing_weekday, billing_month_day, billing_recipient
    FROM funeral_partners
    WHERE id=? LIMIT 1
")) {
    $stmt->bind_param("i", $partner_id);
    if ($stmt->execute()) {
        $stmt->bind_result($bp, $bw, $bm, $br);
        if ($stmt->fetch()) {
            $billing_preference = $bp ?: null;
            $billing_weekday    = $bw !== null ? (int)$bw : null;
            $billing_month_day  = $bm !== null ? (int)$bm : null;
            $billing_recipient  = $br ?: 'customer';
        }
    }
    $stmt->close();
}

$partner_vat = null;
$stmt = $mysqli->prepare("SELECT btw_nummer FROM funeral_partners WHERE id=? LIMIT 1");
$stmt->bind_param("i", $partner_id);
$stmt->execute();
$stmt->bind_result($partner_vat);
$stmt->fetch();
$stmt->close();

/* Map DB -> view (paginaweergave) */
$prefToView = [
    'per_order' => 'order',
    'weekly'    => 'week',
    'monthly'   => 'month',
];
$defaultViewFromDb = $prefToView[$billing_preference] ?? 'order'; // fallback: 'order'

/** instellingen **/
$BTW_PERCENT=21; // pas aan indien ander btw-tarief
$BTW_FACTOR=1+($BTW_PERCENT/100);

/** helpers **/
function to_excl($incl,$factor){return $incl>0?($incl/$factor):0.0;}
function nf($n){return number_format($n,2,',','.');}

/** filters (defaults volgen DB) **/
$allowedViews = ['order','week','month', 'year'];
$view  = isset($_GET['view']) ? $_GET['view'] : $defaultViewFromDb;
if (!in_array($view, $allowedViews, true)) {
    $view = $defaultViewFromDb; // ongeldige view -> terug naar DB-keuze
}

$year  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$week  = isset($_GET['week'])  ? (int)$_GET['week']  : (int)date('W');
$from  = isset($_GET['from'])  ? $_GET['from']  : '';
$to    = isset($_GET['to'])    ? $_GET['to']    : '';

/* eenvoudige sanering op grenzen */
if ($month < 1 || $month > 12) $month = (int)date('m');
if ($week  < 1 || $week  > 53) $week  = (int)date('W');

$factuurregels=[];$totaal_excl=0.0;$totaal_incl=0.0;$totaal_btw=0.0;

/** basisquery **/
$where="o.funeral_partner_id=?"; // evt.: AND o.status!='cancelled'
$pt='i';$pv=[ $partner_id ];

if($view==='order'){
  // datumrange; default = huidige maand
  if(!$from||!$to){$start=sprintf('%04d-%02d-01',$year,$month);$end=date('Y-m-d',strtotime("$start +1 month"));}
  else{$start=date('Y-m-d',strtotime($from));$end=date('Y-m-d',strtotime($to.' +1 day'));}
  $where.=" AND o.created_at>=? AND o.created_at<?";$pt.='ss';$pv[]=$start;$pv[]=$end;
  $heading="Overzicht per order";
}elseif($view==='week'){
  // heel jaar ophalen, later groeperen op ISO-week
  $start=sprintf('%04d-01-01',$year);$end=sprintf('%04d-01-01',$year+1);
  $where.=" AND o.created_at>=? AND o.created_at<?";$pt.='ss';$pv[]=$start;$pv[]=$end;
  $heading="Overzicht per week – $year";
}elseif($view==='year'){
  $where.=" AND YEAR(o.created_at)=?"; 
  $pt.='i'; 
  $pv[]=$year;
  $heading="Jaaroverzicht – $year";
}else{
  $where.=" AND YEAR(o.created_at)=? AND MONTH(o.created_at)=?";$pt.='ii';$pv[]=$year;$pv[]=$month;
  $heading="Maandoverzicht – ".date('F Y',strtotime(sprintf('%04d-%02d-01',$year,$month)));
}

$sql="SELECT o.id AS order_id,o.order_number,o.created_at,op.product_id,op.quantity
FROM orders o
INNER JOIN order_products op ON o.id=op.order_id
WHERE $where
ORDER BY o.created_at DESC";
$stmt=$mysqli->prepare($sql);$stmt->bind_param($pt,...$pv);$stmt->execute();$result=$stmt->get_result();

/** ophalen + prijsberekening (db-prijs is incl. btw) **/
$rows=[];
while($r=$result->fetch_assoc()){
  $pid   = (int)$r['product_id'];
  $qty   = (int)$r['quantity'];
  $prijs_incl = 0.0;
  $titel = null;
  $margin = null; // marge uit producttabel

  // 1) Epoxy-producten
  $res = $mysqli_medewerkers->query("
    SELECT title, total_product_price, margin
    FROM epoxy_products
    WHERE id={$pid} AND sub_category='uitvaart'
    LIMIT 1
  ");
  if($res && $res->num_rows>0){
    $d          = $res->fetch_assoc();
    $titel      = $d['title'];
    $prijs_incl = (float)$d['total_product_price'];
    $margin     = isset($d['margin']) ? (float)$d['margin'] : null;
  }

  //* 2) Kaarsen (alleen als nog niets gevonden)
  if(!$titel){
    $res = $mysqli_medewerkers->query("
      SELECT title, total_product_price, margin
      FROM kaarsen_products
      WHERE id={$pid}
      LIMIT 1
    ");
    if($res && $res->num_rows>0){
      $d          = $res->fetch_assoc();
      $titel      = $d['title'];
      $prijs_incl = (float)$d['total_product_price'];
      $margin     = isset($d['margin']) ? (float)$d['margin'] : null;
    }
  }

  // 3) Geen geldig product -> overslaan
  if(!$titel) continue;

  // 4) Alleen als funeral_partners.billing_recipient = 'partner' → partnerprijs berekenen
  if($billing_recipient === 'partner' && $margin !== null){
    // consumentenprijs incl. btw -> excl. btw
    $priceExVat = to_excl($prijs_incl, $BTW_FACTOR);
    $markup     = $margin / 100;

    if($markup > -1){
      // inkoopprijs uitrekenen
      $productCost  = $priceExVat / (1 + $markup);
      $marginAmount = $priceExVat - $productCost;
      $halfMargin   = $marginAmount /$percentageuitvaart;

      // partnerprijs excl. btw = inkoop + halve marge
      $partnerExVat = $productCost + $halfMargin;
      // terug naar incl. btw
      $prijs_incl   = $partnerExVat * $BTW_FACTOR;
    }
  }

  // 5) Vanaf hier is $prijs_incl ofwel:
  //    - consumentenprijs (billing_recipient != 'partner')
  //    - of partnerprijs (billing_recipient == 'partner')
  $price_excl = to_excl($prijs_incl, $BTW_FACTOR);
  $sub_excl   = $price_excl * $qty;
  $sub_incl   = $prijs_incl * $qty;

  $rows[] = [
    'datum'       => $r['created_at'],
    'ordernummer' => $r['order_number'],
    'product'     => $titel,
    'aantal'      => $qty,
    'prijs_excl'  => $price_excl,
    'sub_excl'    => $sub_excl,
    'sub_incl'    => $sub_incl
  ];
}
$stmt->close();

/* Samenvatting (aantal orders, lijnen, stuks) */
$orderIds   = [];
$total_qty  = 0;
foreach($rows as $it){
    $orderIds[$it['ordernummer']] = true;
    $total_qty += (int)$it['aantal'];
}
$orderCount  = count($orderIds);
$lineCount   = count($rows);

/* CSV-export: detail per orderregel in geselecteerde periode */
if(isset($_GET['export']) && $_GET['export']==='csv' && !empty($rows)){
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=maandoverzicht.csv');
    $out = fopen('php://output','w');
    fputcsv($out, ['Datum','Ordernummer','Product','Aantal','Prijs_excl','Subtotaal_excl','Subtotaal_incl']);
    foreach($rows as $it){
        fputcsv($out, [
            $it['datum'],
            $it['ordernummer'],
            $it['product'],
            $it['aantal'],
            number_format($it['prijs_excl'],2,'.',''),
            number_format($it['sub_excl'],2,'.',''),
            number_format($it['sub_incl'],2,'.','')
        ]);
    }
    fclose($out);
    exit;
}

/** groeperen volgens view **/
if($view==='order'){
  foreach($rows as $it){
    $totaal_excl+=$it['sub_excl'];$totaal_incl+=$it['sub_incl'];
    $factuurregels[]=[
      'datum'=>date('d/m/Y',strtotime($it['datum'])),
      'ordernummer'=>$it['ordernummer'],
      'product'=>$it['product'],
      'aantal'=>$it['aantal'],
      'prijs_excl'=>nf($it['prijs_excl']),
      'sub_excl'=>nf($it['sub_excl'])
    ];
  }
}elseif($view==='week'){
  $byWeek=[];
  foreach($rows as $it){
    $w=(int)date('W',strtotime($it['datum']));$y=(int)date('o',strtotime($it['datum']));
    $key=$y.'-W'.str_pad($w,2,'0',STR_PAD_LEFT);
    if(!isset($byWeek[$key]))$byWeek[$key]=['sum_excl'=>0.0,'sum_incl'=>0.0];
    $byWeek[$key]['sum_excl']+=$it['sub_excl'];$byWeek[$key]['sum_incl']+=$it['sub_incl'];
  }
  if(isset($_GET['week'])&&$_GET['week']!==''){
    $selKey=$year.'-W'.str_pad((int)$week,2,'0',STR_PAD_LEFT);
    $byWeek=array_filter($byWeek,function($k)use($selKey){return $k===$selKey;},ARRAY_FILTER_USE_KEY);
  }
  krsort($byWeek);
  foreach($byWeek as $wk=>$agg){
    $totaal_excl+=$agg['sum_excl'];$totaal_incl+=$agg['sum_incl'];
    $dt=new DateTime();$dt->setISODate((int)substr($wk,0,4),(int)substr($wk,6,2));$start=$dt->format('d/m/Y');$dt->modify('+6 days');$end=$dt->format('d/m/Y');
    $factuurregels[]=['week'=>$wk,'periode'=>$start.' – '.$end,'sub_excl'=>nf($agg['sum_excl'])];
  }
}elseif($view==='year'){
  $byYear=[];
  foreach($rows as $it){
    $key=date('Y',strtotime($it['datum']));
    if(!isset($byYear[$key]))$byYear[$key]=['sum_excl'=>0.0,'sum_incl'=>0.0];
    $byYear[$key]['sum_excl']+=$it['sub_excl'];
    $byYear[$key]['sum_incl']+=$it['sub_incl'];
  }
  krsort($byYear);
  foreach($byYear as $yr=>$agg){
    $totaal_excl+=$agg['sum_excl'];$totaal_incl+=$agg['sum_incl'];
    $factuurregels[]=['jaar'=>$yr,'sub_excl'=>nf($agg['sum_excl'])];
  }
}else{
  $byMonth=[];
  foreach($rows as $it){
    $key=date('Y-m',strtotime($it['datum']));
    if(!isset($byMonth[$key]))$byMonth[$key]=['sum_excl'=>0.0,'sum_incl'=>0.0];
    $byMonth[$key]['sum_excl']+=$it['sub_excl'];$byMonth[$key]['sum_incl']+=$it['sub_incl'];
  }
  krsort($byMonth);
  foreach($byMonth as $ym=>$agg){
    $totaal_excl+=$agg['sum_excl'];$totaal_incl+=$agg['sum_incl'];
    $factuurregels[]=['maand'=>date('F Y',strtotime($ym.'-01')),'sub_excl'=>nf($agg['sum_excl'])];
  }
}
$totaal_btw=$totaal_incl-$totaal_excl;

if(!function_exists('e')){function e(string $v):string{return htmlspecialchars($v,ENT_QUOTES,'UTF-8');}}

$tz='Europe/Brussels';
$now = new DateTime('now', new DateTimeZone($tz));
$firstOfThisMonth = (clone $now)->modify('first day of this month')->setTime(0,0,0);
$firstOfNextMonth = (clone $firstOfThisMonth)->modify('first day of next month');
$lastMonthEndLabel = (clone $firstOfThisMonth)->modify('-1 day'); 
$bill_pref=$bill_recipient=null; $bill_weekday=null; $bill_month_day=null;

$stmt=$mysqli->prepare("
  SELECT billing_preference,billing_recipient,billing_weekday,billing_month_day
  FROM funeral_partners
  WHERE id=?
  LIMIT 1
");
$stmt->bind_param('i',$partner_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($bill_pref,$bill_recipient,$bill_weekday,$bill_month_day);
$stmt->fetch();
$stmt->free_result();
$stmt->close();

/* Labels & weergave */
$prefLabels=['per_order'=>'Per bestelling','weekly'=>'Wekelijks','monthly'=>'Maandelijks'];
$recpLabels=['partner'=>'Uitvaartpartner','customer'=>'Klant'];
$weekdayNames=[1=>'maandag',2=>'dinsdag',3=>'woensdag',4=>'donderdag',5=>'vrijdag',6=>'zaterdag',7=>'zondag'];

$prefText = $prefLabels[$bill_pref] ?? '—';
$recpText = $recpLabels[$bill_recipient] ?? '—';

$detailText=''; 
if($bill_pref==='weekly'){
  $wd = (int)$bill_weekday;
  $detailText = ($wd>=1 && $wd<=7) ? (' • op '. $weekdayNames[$wd].' (Europe/Brussels)') : '';
}elseif($bill_pref==='monthly'){
  $md = is_null($bill_month_day)? null : (int)$bill_month_day;
  if($md!==null && $md>=1 && $md<=31){ $detailText=' • op dag '.$md.' van de maand'; }
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

/* Bepalen of btw-regel verbergen (zelfde logica als voordien) */
$hideVatLines = (
    $billing_recipient === 'partner' &&
    !empty($partner_vat) &&
    str_starts_with($partner_vat, 'NL')
);
?>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>
<style>
body{background:url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;background-size:cover;}
.dashboard-page{background-color:rgba(255,255,255,.9);padding:3rem 2rem;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);margin:3rem auto 2rem;}
.info-box{background:#f5f5f5;border-left:4px solid #5a7d5a;padding:1rem 1.5rem;margin:1.5rem 0;font-size:1rem;color:#333;border-radius:.5rem;box-shadow:0 1px 3px rgba(0,0,0,.05);}
/* filter card */
.filter-card{background:#fff;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.06);padding:1rem 1rem 1.25rem;margin:1rem 0 1.5rem;border:1px solid #f0f0f0}
.filter-title{font-size:1.1rem;font-weight:700;color:#2e2e2e;margin:.25rem 0 1rem}
.filter-grid{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:.75rem}
@media(max-width:1100px){.filter-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media(max-width:680px){.filter-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
.ctrl{display:flex;flex-direction:column}
.ctrl label{font-weight:600;color:#2f3b2f;margin-bottom:.35rem}
.ctrl input,.ctrl select{appearance:none;border:1px solid #e6e6e6;border-radius:.65rem;padding:.6rem .75rem;background:#fafafa;color:#222;transition:border-color .15s,box-shadow .15s,background .15s}
.ctrl input:focus,.ctrl select:focus{outline:0;border-color:#5a7d5a;box-shadow:0 0 0 3px rgba(90,125,90,.18);background:#fff}
.ctrl small{color:#666;margin-top:.25rem}
.btn{display:inline-block;background:#5a7d5a;color:#fff;padding:.6rem 1rem;border-radius:.5rem;text-decoration:none}
.btn-primary{display:inline-flex;align-items:center;justify-content:center;background:#5a7d5a;color:#fff;border:0;border-radius:.65rem;padding:.7rem 1rem;font-weight:700;cursor:pointer;transition:transform .05s ease,box-shadow .15s}
.btn-primary:hover{box-shadow:0 6px 16px rgba(90,125,90,.18)}
.btn-primary:active{transform:translateY(1px)}
.btn-ghost{background:transparent;border:1px solid #dcdcdc;color:#2f3b2f}
.btn-row{display:flex;gap:.6rem;align-items:end}
.badge{display:inline-block;background:#eef4ee;color:#2f3b2f;border:1px solid #dbe7db;border-radius:999px;padding:.2rem .6rem;font-size:.8rem;margin-left:.5rem}
.hint{display:flex;align-items:center;gap:.5rem;margin:.25rem 0 0;color:#4a5a4a;font-size:.9rem}
.hint:before{content:"ⓘ";font-weight:700}
.is-disabled{opacity:.5;pointer-events:none}
/* table card */
.table-card{background:#fff;border:1px solid #f0f0f0;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.06);overflow:hidden;margin:1rem 0}
.table-scroll{overflow:auto;position:relative}
.simple-table{width:100%;border-collapse:separate;border-spacing:0;background:#fff;font-size:.98rem}
.simple-table thead th{position:sticky;top:0;background:#f9f9f9;z-index:2;border-bottom:1px solid #ececec}
.simple-table th{color:#2a5934;}
.simple-table th,.simple-table td{ padding:.75rem 1rem;white-space:nowrap;border-bottom:1px solid #f2f2f2}
.simple-table tbody tr:nth-child(even){background:#fcfcfc}
.simple-table tbody tr:hover{background:#f5faf5}
.simple-table tfoot th{background:#f9f9f9;  border-top:2px solid #e7e7e7}
.th-right{text-align:right}.num{text-align:right}.ctr{text-align:center}
.table-shadow:before{content:"";position:absolute;top:0;left:0;width:16px;height:100%;box-shadow:inset 10px 0 8px -10px rgba(0,0,0,.2);opacity:0;transition:opacity .15s;pointer-events:none}
.table-shadow:after{content:"";position:absolute;top:0;right:0;width:16px;height:100%;box-shadow:inset -10px 0 8px -10px rgba(0,0,0,.2);opacity:0;transition:opacity .15s;pointer-events:none}
.table-shadow.has-left:before{opacity:1}.table-shadow.has-right:after{opacity:1}
@media(max-width:760px){.simple-table{font-size:.95rem}.simple-table th,.simple-table td{padding:.6rem .75rem}}
.info-card{border:1px solid #dcdcdc;border-radius:12px;padding:12px;background:#fff;margin:0 0 12px 0}
.info-title{font-weight:700;margin:0 0 6px 0}
.info-row{display:flex;gap:8px;flex-wrap:wrap}
.info-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;background:#f1f5f4;border:1px solid #dcdcdc;font-size:13px}
.info-muted{color:#777}
.dashboard-welcome{margin-bottom:1.5rem;text-align:left}
.dashboard-welcome h1{font-size:1.6rem;color:#2a5934;margin-bottom:.3rem}
.dashboard-welcome p{color:#555;margin:0}
@media(max-width:768px){.dashboard-page{padding:2rem 1rem;margin:2rem 1rem}}
</style>

<main class="dashboard-page container">
  <div class="dashboard-welcome">
    <h1><?= htmlspecialchars($heading) ?></h1>
    <p>Kies je weergave en periode. Prijzen hieronder zijn exclusief btw (<?= (int)$BTW_PERCENT ?>%).</p>
  </div>
  <div class="info-card">
      <div class="filter-title">Ingestelde facturatie-instellingen</div>

      <div class="info-row">
        <span class="info-badge">Voorkeur: <?= e($prefText) ?></span>
        <span class="info-badge">Ontvanger: <?= e($recpText) ?></span>
        <?php if($detailText !== ''): ?>
          <span class="info-badge"><?= e($detailText) ?></span>
        <?php endif; ?>
        <span class="info-badge">Land: <?= e($partner_country) ?></span>
      </div>

      <?php if($prefText === '—' && $recpText === '—'): ?>
        <div class="info-muted" style="margin-top:6px">Geen facturatievoorkeur(en) ingesteld.</div>
      <?php endif; ?>

      <div class="info-muted" style="margin-top:8px">
        Deze instellingen kan je beheren via<br>
        <strong>Mijn Dashboard → Mijn Facturen → Factuur Instellingen</strong>.<br>
        Land wordt automatisch bepaald op basis van je btw-nummer.
      </div>
    </div>
 

  <!-- Filter + facturatie-info -->
  <div class="filter-card">
  <?php if(!empty($factuurregels)): ?>
    <div class="info-card" style="margin-bottom:1rem;">
      <div class="info-title">Samenvatting geselecteerde periode</div>
      <div class="info-row">
        <span class="info-badge">Aantal orders: <?= (int)$orderCount ?></span>
        <span class="info-badge">Aantal lijnen: <?= (int)$lineCount ?></span>
        <span class="info-badge">Bestelde stuks: <?= (int)$total_qty ?></span>
        <span class="info-badge">Omzet excl.: &euro; <?= nf($totaal_excl) ?></span>
        <?php if(!$hideVatLines): ?>
          <span class="info-badge">BTW: &euro; <?= nf($totaal_btw) ?></span>
          <span class="info-badge">Omzet incl.: &euro; <?= nf($totaal_incl) ?></span>
        <?php endif; ?>
      </div>
      <div class="info-muted" style="margin-top:6px">
        Dit is een indicatief overzicht. Officiële facturen vind je onder <strong>Mijn facturen</strong>.
      </div>
    </div>
  <?php endif; ?>

    <div class="info-card">    
      <div class="filter-title">Filter <span class="badge">excl. btw</span></div>
      <form method="get" id="filterForm">
        <div class="filter-grid">
          <div class="ctrl">
            <label for="view">Weergave</label>
            <select name="view" id="view">
              <option value="order" <?= $view==='order'?'selected':''; ?>>Per order</option>
              <option value="week" <?= $view==='week'?'selected':''; ?>>Per week</option>
              <option value="month" <?= $view==='month'?'selected':''; ?>>Per maand</option>
              <option value="year" <?= $view==='year'?'selected':''; ?>>Per jaar</option>
            </select>
          </div>
          <div class="ctrl">
            <label for="year">Jaar</label>
            <input type="number" id="year" name="year" value="<?= htmlspecialchars($year) ?>" min="2023" max="<?= date('Y') ?>">
          </div>
          <div class="ctrl" id="wrapMonth">
            <label for="month">Maand</label>
            <input type="number" id="month" name="month" value="<?= htmlspecialchars($month) ?>" min="1" max="12">
            <small>Relevant bij “per maand”.</small>
          </div>
          <div class="ctrl" id="wrapWeek">
            <label for="week">Week (ISO)</label>
            <input type="number" id="week" name="week" value="<?= htmlspecialchars($week) ?>" min="1" max="53">
            <small>Kies specifieke week in “per week”.</small>
          </div>
          <div class="ctrl" id="wrapFrom">
            <label for="from">Van (optioneel)</label>
            <input type="date" id="from" name="from" value="<?= htmlspecialchars($from) ?>">
          </div>
          <div class="ctrl" id="wrapTo">
            <label for="to">Tot (optioneel)</label>
            <input type="date" id="to" name="to" value="<?= htmlspecialchars($to) ?>">
          </div>
        </div>
        <div class="btn-row" style="margin-top:.9rem">
          <button class="btn-primary" type="submit">Toepassen</button>
          <a class="btn-primary btn-ghost" href="?view=<?= e($view) ?>">Reset</a>
          <a class="btn-primary btn-ghost" href="?view=<?= e($view) ?>&year=<?= (int)$year ?>&month=<?= (int)$month ?>&week=<?= (int)$week ?>&from=<?= e($from) ?>&to=<?= e($to) ?>&export=csv">
            Export (CSV)
          </a>
          <div class="hint">Totalen tonen excl., btw en incl.</div>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabel -->
  <?php if(empty($factuurregels)): ?>
    <div class="info-box"><p>Geen resultaten voor de huidige selectie.</p></div>
  <?php else: ?>
  <div class="table-card">
    <div class="table-scroll table-shadow" id="tblScroll">
      <table class="simple-table">
        <thead>
          <tr>
            <?php if($view==='order'): ?>
              <th>Datum</th><th>Ordernummer</th><th>Product</th><th class="ctr">Aantal</th><th class="th-right">Prijs (excl.)</th><th class="th-right">Subtotaal (excl.)</th>
            <?php elseif($view==='week'): ?>
              <th>ISO-week</th><th>Periode</th><th class="th-right">Som (excl.)</th>
            <?php elseif($view==='month'): ?>
              <th>Maand</th><th class="th-right">Som (excl.)</th>
            <?php elseif($view==='year'): ?>
              <th>Jaar</th><th class="th-right">Som (excl.)</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php if($view==='order'): foreach($factuurregels as $r): ?>
            <tr>
              <td><?= $r['datum'] ?></td>
              <td><?= htmlspecialchars($r['ordernummer']) ?></td>
              <td><?= htmlspecialchars($r['product']) ?></td>
              <td class="ctr"><?= (int)$r['aantal'] ?></td>
              <td class="num"><sup>€</sup><?= $r['prijs_excl'] ?></td>
              <td class="num"><sup>€</sup><?= $r['sub_excl'] ?></td>
            </tr>
          <?php endforeach; elseif($view==='week'): foreach($factuurregels as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['week']) ?></td>
              <td><?= htmlspecialchars($r['periode']) ?></td>
              <td class="num"><sup>€</sup><?= $r['sub_excl'] ?></td>
            </tr>
          <?php endforeach; elseif($view==='month'): foreach($factuurregels as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['maand']) ?></td>
              <td class="num"><sup>€</sup><?= $r['sub_excl'] ?></td>
            </tr>
          <?php endforeach; elseif($view==='year'): foreach($factuurregels as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r['jaar']) ?></td>
              <td class="num"><sup>€</sup><?= $r['sub_excl'] ?></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
        <tfoot>
          <?php if($view==='order'): ?>
            <tr><th colspan="5" class="th-right">Totaal excl.:</th><th class="num"><sup>€</sup><?= nf($totaal_excl) ?></th></tr>
            <?php if(!$hideVatLines): ?>
            <tr><th colspan="5" class="th-right">BTW (<?= (int)$BTW_PERCENT ?>%):</th><th class="num"><sup>€</sup><?= nf($totaal_incl-$totaal_excl) ?></th></tr>
            <tr><th colspan="5" class="th-right">Totaal incl.:</th><th class="num"><sup>€</sup><?= nf($totaal_incl) ?></th></tr>
            <?php endif; ?>
          <?php elseif($view==='week'): ?>
            <tr><th colspan="2" class="th-right">Totaal excl.:</th><th class="num"><sup>€</sup><?= nf($totaal_excl) ?></th></tr>
            <?php if(!$hideVatLines): ?>
            <tr><th colspan="2" class="th-right">BTW (<?= (int)$BTW_PERCENT ?>%):</th><th class="num"><sup>€</sup><?= nf($totaal_incl-$totaal_excl) ?></th></tr>
            <tr><th colspan="2" class="th-right">Totaal incl.:</th><th class="num"><sup>€</sup><?= nf($totaal_incl) ?></th></tr>
            <?php endif; ?>
          <?php else: ?>
            <tr><th class="th-right">Totaal excl.:</th><th class="num"><sup>€</sup><?= nf($totaal_excl) ?></th></tr>
            <?php if(!$hideVatLines): ?>
            <tr><th class="th-right">BTW (<?= (int)$BTW_PERCENT ?>%):</th><th class="num"><sup>€</sup><?= nf($totaal_incl-$totaal_excl) ?></th></tr>
            <tr><th class="th-right">Totaal incl.:</th><th class="num"><sup>€</sup><?= nf($totaal_incl) ?></th></tr>
            <?php endif; ?>
          <?php endif; ?>
        </tfoot>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <div class="dashboard-actions" style="margin-top:1.5rem">
    <div class="dashboard-card">
      <h2>Terug naar Facturen</h2>
      <p>Keer terug naar het factuuroverzicht van je account.</p>
      <a href="index.php" class="btn">← Terug</a>
    </div>
  </div>
</main>

<!-- JS -->
<script>
(function(){
  // filter velden netjes schakelen
  const v=document.getElementById('view'),
        w=document.getElementById('wrapWeek'),
        m=document.getElementById('wrapMonth'),
        f=document.getElementById('wrapFrom'),
        t=document.getElementById('wrapTo');
  function sync(){
    w.classList.remove('is-disabled');m.classList.remove('is-disabled');
    f.classList.remove('is-disabled');t.classList.remove('is-disabled');
    if(v.value==='week'){
      m.classList.add('is-disabled');f.classList.add('is-disabled');t.classList.add('is-disabled');
    }else if(v.value==='month'){
      w.classList.add('is-disabled');f.classList.add('is-disabled');t.classList.add('is-disabled');
    }else{
      w.classList.add('is-disabled');m.classList.add('is-disabled');
    }
  }
  v.addEventListener('change',sync);sync();
  // tabel schaduw bij horizontaal scrollen
  const sc=document.getElementById('tblScroll');if(!sc)return;
  function sh(){
    const l=sc.scrollLeft>0;
    const r=sc.scrollLeft+sc.clientWidth<sc.scrollWidth;
    sc.classList.toggle('has-left',l);
    sc.classList.toggle('has-right',r);
  }
  sc.addEventListener('scroll',sh);window.addEventListener('resize',sh);sh();
})();
</script>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
