<?php
session_start();
if (!isset($_SESSION['partner_id'])) {
  header("Location: /pages/account/login.php");
  exit;
}

require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';
require dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/onfact_config.inc.php'; // $config + $mysqli

$partner_id = (int)$_SESSION['partner_id'];

/* =========================
   1) Partner-e-mail + billing settings + BTW ophalen
   ========================= */
$email = '';
$billing_recipient = null;        // 'partner' | 'customer'
$billing_preference = null;       // 'per_order' | 'weekly' | 'monthly'
$isNL = false;

if ($stmt = $mysqli->prepare("SELECT email, billing_recipient, billing_preference, btw_nummer FROM funeral_partners WHERE id = ? LIMIT 1")) {
    $stmt->bind_param("i", $partner_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $email = isset($row['email']) ? trim((string)$row['email']) : '';
        $billing_recipient  = isset($row['billing_recipient']) ? trim((string)$row['billing_recipient']) : null;
        $billing_preference = isset($row['billing_preference']) ? trim((string)$row['billing_preference']) : null;

        // NL-detectie op BTW-prefix 'NL'
        $vat = strtoupper(preg_replace('/\s+/', '', (string)($row['btw_nummer'] ?? '')));
        $isNL = (substr($vat, 0, 2) === 'NL');
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

$allowedRecipients   = ['partner','customer'];
$allowedPreferences  = ['per_order','weekly','monthly'];

// Nog geen keuze gemaakt?
$needsSetup =
    ($billing_recipient === null || $billing_recipient === '' || !in_array($billing_recipient, $allowedRecipients, true)) ||
    ($billing_preference === null || $billing_preference === '' || !in_array($billing_preference, $allowedPreferences, true));

// Facturen blokkeren wanneer naar eindklant gestuurd wordt, of bij NL-regel
$blockInvoices = (!$needsSetup && $billing_recipient === 'customer');

/* =========================
   2) Helpers (API + parsing)
   ========================= */
function apiGet(string $url, array $headers): array {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL=>$url, CURLOPT_RETURNTRANSFER=>true, CURLOPT_TIMEOUT=>25, CURLOPT_CONNECTTIMEOUT=>10,
        CURLOPT_HTTPHEADER=>$headers, CURLOPT_SSL_VERIFYPEER=>true, CURLOPT_SSL_VERIFYHOST=>2,
    ]);
    $response = curl_exec($ch); $curlErr = curl_error($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($curlErr || $response === false) return ['ok'=>false,'error'=>$curlErr,'http'=>$httpCode];
    if ($httpCode >= 400) return ['ok'=>false,'error'=>'HTTP '.$httpCode,'http'=>$httpCode,'raw'=>$response];
    return ['ok'=>true,'json'=>json_decode($response,true),'http'=>$httpCode];
}
function pick(array $arr, array $paths){
    foreach($paths as $p){
        $cur=$arr;$ok=true;
        foreach(explode('.',$p) as $seg){
            if(is_array($cur)&&array_key_exists($seg,$cur)){$cur=$cur[$seg];}
            else{$ok=false;break;}
        }
        if($ok&&$cur!==null&&$cur!=='')return $cur;
    }
    return null;
}
function moneyValue($v, ?string $c=null): ?float {
    if ($v===null) return null;
    if (is_numeric($v)) return (float)$v;
    if (is_array($v)){
        if(isset($v['_'])&&is_numeric($v['_'])) return (float)$v['_'];
        if($c&&isset($v[$c])&&is_numeric($v[$c])) return (float)$v[$c];
        foreach($v as $val) if (is_numeric($val)) return (float)$val;
    }
    return null;
}
function moneyFormat(?float $n): string {
    if($n===null) return '—';
    if ($n>99999 && fmod($n,1.0)===0.0) $n/=100.0;
    return number_format($n,2,',','.');
}
function extractTotalIncl(array $inv): ?float {
    $cur = (string)(pick($inv,['currency_id']) ?? 'EUR');
    $paths = [
        'total_amount_incl','base_total_amount_incl','totals.total_incl',
        'totals.total_amount_incl','totals.gross_incl','total_incl',
        'grand_total','amount_total','total'
    ];
    foreach($paths as $p){
        $val = moneyValue(pick($inv,[$p]), $cur);
        if($val!==null) return $val;
    }
    $lines = pick($inv,['invoice_lines','lines','items']) ?? [];
    if (is_array($lines) && $lines){
        $sum=0.0; $found=false;
        foreach($lines as $ln){
            $v=moneyValue(pick($ln,['total_amount_incl','base_total_amount_incl','total_incl']),$cur);
            if($v!==null){$sum+=$v;$found=true;continue;}
            $qty=pick($ln,['quantity','qty']);
            $unit=moneyValue(pick($ln,['price_incl','unit_price_incl','price','base_price_incl']),$cur);
            if(is_numeric($qty)&&$unit!==null){$sum+=((float)$qty)*$unit;$found=true;continue;}
            $v2=moneyValue(pick($ln,['total_price_incl','base_total_amount_incl','price_incl']),$cur);
            if($v2!==null){$sum+=$v2;$found=true;}
        }
        if($found) return $sum;
    }
    return null;
}
function amountDueFromInvoice(array $inv): ?float {
    $paths=[
        'amount_due','balance','open_amount','outstanding','due_amount',
        'totals.amount_due','totals.balance','payment_balance'
    ];
    foreach($paths as $p){
        $num=moneyValue(pick($inv,[$p]), (string)(pick($inv,['currency_id'])??'EUR'));
        if($num!==null) return $num;
    }
    return null;
}
function statusDisplay(array $inv): array {
    $raw=strtolower((string)(pick($inv,['state','status'])??''));
    $dueDate=pick($inv,['due_date','expiration_date','expiry_date','payment_due_date']);
    $dueTs=$dueDate?strtotime($dueDate):null;
    $total=extractTotalIncl($inv);
    $due=amountDueFromInvoice($inv);

    $map=[
        'paid'           =>['Betaald','bg-success text-white p-2'],
        'settled'        =>['Betaald','bg-success text-white p-2'],
        'unpaid'         =>['Openstaand','bg-warning text-dark p-2'],
        'open'           =>['Openstaand','bg-warning text-dark p-2'],
        'sent'           =>['Verzonden','bg-info text-dark p-2'],
        'overdue'        =>['Achterstallig','bg-danger text-white p-2'],
        'reminded'       =>['Herinnering verstuurd','bg-info text-dark p-2'],
        'draft'          =>['Concept','bg-secondary text-white p-2'],
        'cancelled'      =>['Geannuleerd','bg-dark text-white p-2'],
        'void'           =>['Geannuleerd','bg-dark text-white p-2'],
        'partially'      =>['Gedeeltelijk betaald','bg-primary text-white p-2'],
        'partially_paid' =>['Gedeeltelijk betaald','bg-primary text-white p-2'],
    ];
    if(isset($map[$raw])) return $map[$raw];

    if($total!==null && $due!==null){
        if($due<=0.009) return ['Betaald','bg-success text-white p-2'];
        if($dueTs && $dueTs < strtotime('today')) return ['Achterstallig','bg-danger text-white p-2'];
        if($due < $total) return ['Gedeeltelijk betaald','bg-primary text-white p-2'];
        return ['Openstaand','bg-warning text-dark p-2'];
    }
    $pretty=trim(str_replace('_',' ',$raw));
    if($pretty==='') $pretty='Onbekend';
    return [$pretty,'bg-secondary text-white p-2'];
}
function isInvoiceUnpaid(array $inv): bool {
    $raw=strtolower((string)(pick($inv,['state','status'])??''));
    if(in_array($raw,['paid','settled','betaald'],true)) return false;
    $due=moneyValue(
        pick($inv,[
            'amount_due','balance','open_amount','outstanding','due_amount',
            'totals.amount_due','totals.balance'
        ]),
        (string)(pick($inv,['currency_id'])??'EUR')
    );
    return ($due===null ? $raw!=='' && $raw!=='paid' : $due>0.009);
}
function invoiceDateTs(array $inv): ?int{
    $d=pick($inv,['document_date','invoice_date','date','created']);
    if(!$d) return null;
    $ts=strtotime((string)$d);
    return $ts?:null;
}
function withinQuarter(int $m,int $q):bool{
    if($q<1||$q>4) return true;
    $qm=[1=>[1,2,3],2=>[4,5,6],3=>[7,8,9],4=>[10,11,12]];
    return in_array($m,$qm[$q],true);
}

/* =========================
   3) UI-render helper
   ========================= */
function printInvoicesTable(array $invoices, string $baseUrl=''): void {
    if (empty($invoices)) {
        echo "<div class='info-box'><p>Er zijn geen facturen beschikbaar voor jouw account met deze zoekinstellingen.</p></div>";
        return;
    }
    echo "<div class='table-responsive'><table class='table table-bordered align-middle'>";
    echo "<thead><tr><th>Factuurnummer</th><th>Datum</th><th>Vervaldatum</th><th>Status</th><th class='text-end'>Totaal</th><th></th></tr></thead><tbody>";
    foreach ($invoices as $inv) {
        $nr=pick($inv,['document_number','number_formatted','number','code','reference']) ?? '—';
        $dateRaw=pick($inv,['document_date','invoice_date','date','created']) ?? '—';
        $dueRaw =pick($inv,['due_date','expiration_date','expiry_date','payment_due_date']) ?? '—';
        $dateTs=$dateRaw?strtotime((string)$dateRaw):null;
        $dueTs=$dueRaw?strtotime((string)$dueRaw):null;
        $date=$dateTs?date('d-m-Y',$dateTs):$dateRaw;
        $due=$dueTs?date('d-m-Y',$dueTs):$dueRaw;
        $total=moneyFormat($inv['__total'] ?? extractTotalIncl($inv));
        [$status,$badge]=statusDisplay($inv);
        $id=pick($inv,['id','invoice_id','eid','uuid']);
        $idParam=$id!==null?urlencode((string)$id):'';
        $payUrl=null;
        if(isInvoiceUnpaid($inv)){
            $u=$inv['__pay_url'] ?? null;
            if(is_string($u)&&$u!=='') $payUrl=$u;
        }
        echo "<tr><td>".htmlspecialchars((string)$nr)."</td><td>".htmlspecialchars((string)$date)."</td><td>".htmlspecialchars((string)$due)."</td>";
        echo "<td><span class='badge {$badge}'>".htmlspecialchars($status)."</span></td>";
        echo "<td class='text-end'><sup>€</sup> {$total}</td><td class='text-end'>"
           . ($idParam ? "<a class='btn btn-sm btn-outline-danger me-2' href='invoice_pdf.php?id={$idParam}' target='_blank' rel='noopener'><i class='fas fa-file-pdf'></i></a>" : '')
           . ($payUrl ? "<a class='btn btn-sm btn-success' style='margin-left:10px;' href='".htmlspecialchars($payUrl,ENT_QUOTES)."' target='_blank' rel='noopener'><i class='fas fa-credit-card me-1'></i>&nbsp;Betaal&nbsp;nu</a>" : '')
           . "</td></tr>";
    }
    echo "</tbody></table></div>";
}

/* =========================
   4) Onfact API-calls (alleen als niet geblokt en setup ok)
   ========================= */
$headers = [
    'Accept: application/json',
    'X-SESSION-KEY: ' . $config['onfact_api_key'],
    'X-COMPANY-UUID: ' . $config['onfact_company_id'],
];
$contact=null; $invoices=[]; $pagedInvoices=[]; $displayInvoices=[]; $totalPages=1; $page=1; $total=0; $allowedYears=[]; $year=null; $quarter=0;
$show = isset($_GET['show']) ? $_GET['show'] : 'all'; // 'all' | 'open'

if (!$needsSetup && !$blockInvoices && $_SERVER['REQUEST_METHOD']==='GET' && $email!=='') {
    $emailNorm=strtolower($email);

    // Contact zoeken
    $q='email:"'.$emailNorm.'"';
    $contactsUrl=rtrim($config['base_url'],'/').'/contacts.json?q='.urlencode($q).'&per_page=100';
    $cRes=apiGet($contactsUrl,$headers);
    if($cRes['ok']){
        $payload=$cRes['json'] ?? [];
        $contacts=$payload['data'] ?? ($payload['items'] ?? []);
        $contacts=is_array($contacts)?$contacts:[];
        foreach($contacts as $c){
            if(strtolower($c['email'] ?? '')===$emailNorm){ $contact=$c; break; }
        }
        if(!$contact && !empty($contacts)) $contact=$contacts[0];
    }

    // Facturen ophalen
    if($contact && !empty($contact['id'])){
        $contactId=(string)$contact['id'];
        $contactEid=(string)($contact['eid'] ?? '');
        $contactEmail=strtolower($contact['email'] ?? '');
        $invUrl=rtrim($config['base_url'],'/').'/invoices.json?contact_id='.rawurlencode($contactId).'&per_page=100';
        $iRes=apiGet($invUrl,$headers); $all=[];
        if($iRes['ok']){
            $invPayload=$iRes['json'] ?? [];
            $all=$invPayload['data'] ?? ($invPayload['items'] ?? []);
        }

        $filtered=array_values(array_filter($all,function($inv) use($contactId,$contactEid,$contactEmail){
            $id=(string)(pick($inv,['contact_id','customer_id','contact.id','customer.id']) ?? '');
            $eid=(string)(pick($inv,['contact_eid','customer_eid','contact.eid','customer.eid']) ?? '');
            $mail=strtolower((string)(pick($inv,['customer.email','contact.email','email']) ?? ''));
            if($id!=='' && $id===$contactId) return true;
            if($eid!=='' && $eid===$contactEid) return true;
            if($mail!=='' && $contactEmail!=='' && $mail===$contactEmail) return true;
            return false;
        }));

        // Detailverrijking
        foreach($filtered as &$inv){
            $id=pick($inv,['id','invoice_id','eid','uuid']);
            if($id!==null){
                $detailUrl=rtrim($config['base_url'],'/').'/invoices/'.rawurlencode((string)$id).'.json';
                $dRes=apiGet($detailUrl,$headers);
                if($dRes['ok']){
                    $pl=$dRes['json'] ?? [];
                    $detail=$pl['data'] ?? ($pl['item'] ?? $pl);
                    $inv['__total']=extractTotalIncl(is_array($detail)?$detail:$inv);
                    $inv['__pay_url']=pick($detail,[
                        'payment_url','pay_url','public_url','html_url',
                        'customer_portal_url','links.payment','_links.payment',
                        'links.public','_links.public'
                    ]);
                    if(empty($inv['__pay_url'])){
                        $tok=pick($detail,['public_token','token','access_token']);
                        if($tok){
                            $inv['__pay_url']='https://app.onfact.be/invoices/'.rawurlencode((string)$id).'?t='.urlencode((string)$tok);
                        }
                    }
                } else {
                    $inv['__total']=extractTotalIncl($inv);
                }
            }
        } unset($inv);

        $invoices=$filtered;
        date_default_timezone_set('Europe/Brussels');
        $nowYear=(int)date('Y'); $startYear=2023; $startMonth=7;
        $allowedYears=range($nowYear,$startYear);

        $year = isset($_GET['year']) && $_GET['year']!=='' ? (int)$_GET['year'] : null;
        if ($year!==null && !in_array($year,$allowedYears,true)) $year=$nowYear;

        $quarter = isset($_GET['q']) ? (int)$_GET['q'] : 0;

        // Filter op jaar / kwartaal
        $invoices=array_values(array_filter($invoices,function($inv) use($year,$quarter,$startYear,$startMonth){
            $ts=invoiceDateTs($inv); if(!$ts) return false;
            $y=(int)date('Y',$ts); $m=(int)date('n',$ts);
            if($y<$startYear) return false;
            if($y===$startYear && $m<$startMonth) return false;
            if($year!==null && $y!==$year) return false;
            if($quarter>0 && !withinQuarter($m,$quarter)) return false;
            return true;
        }));

        // Samenvatting (op basis van gefilterde lijst)
        $total_open_amount = 0.0;
        $count_open        = 0;
        $count_paid        = 0;
        foreach($invoices as $inv){
            $due   = amountDueFromInvoice($inv);
            $totalAmount = extractTotalIncl($inv);
            if(isInvoiceUnpaid($inv)){
                $count_open++;
                if($due !== null){
                    $total_open_amount += $due;
                } elseif($totalAmount !== null){
                    $total_open_amount += $totalAmount;
                }
            } else {
                $count_paid++;
            }
        }

        // Weergavefilter (alle / enkel openstaand)
        if($show === 'open'){
            $displayInvoices = array_values(array_filter($invoices, 'isInvoiceUnpaid'));
        } else {
            $displayInvoices = $invoices;
        }

        // CSV-export (op basis van displayInvoices)
        if(isset($_GET['export']) && $_GET['export']==='csv' && !empty($displayInvoices)){
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=facturen.csv');
            $out = fopen('php://output','w');
            fputcsv($out, ['Factuurnr','Datum','Vervaldatum','Status','Totaal','Openstaand']);
            foreach($displayInvoices as $inv){
                $nr  = pick($inv,['document_number','number_formatted','number','code','reference']) ?? '';
                $dt  = pick($inv,['document_date','invoice_date','date','created']) ?? '';
                $due = pick($inv,['due_date','expiration_date','expiry_date','payment_due_date']) ?? '';
                $st  = statusDisplay($inv)[0] ?? '';
                $tot = extractTotalIncl($inv);
                $dueAmt = amountDueFromInvoice($inv);
                fputcsv($out, [$nr,$dt,$due,$st,$tot,$dueAmt]);
            }
            fclose($out);
            exit;
        }

        // Sorteren & paginering op basis van displayInvoices
        usort($displayInvoices,function($a,$b){
            $ta=invoiceDateTs($a) ?? 0; $tb=invoiceDateTs($b) ?? 0;
            return $tb <=> $ta;
        });

        $perPage=12;
        $total=count($displayInvoices);
        $totalPages=max(1,(int)ceil($total/$perPage));
        $page=isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        if($page>$totalPages) $page=$totalPages;
        $offset=($page-1)*$perPage;
        $pagedInvoices=array_slice($displayInvoices,$offset,$perPage);

        // helper voor URL
        function buildUrl(array $extra): string{
            $p=$_GET;
            foreach($extra as $k=>$v){
                if($v===null) unset($p[$k]);
                else $p[$k]=$v;
            }
            return '?'.http_build_query($p);
        }
        $qOptions=[0=>'Alle kwartalen',1=>'Q1 (jan–mrt)',2=>'Q2 (apr–jun)',3=>'Q3 (jul–sep)',4=>'Q4 (okt–dec)'];

        // Samenvattingswaarden beschikbaar maken buiten blok
        $GLOBALS['total_open_amount'] = $total_open_amount;
        $GLOBALS['count_open']        = $count_open;
        $GLOBALS['count_paid']        = $count_paid;
    }
}

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

// Samenvatting-variabelen uit globals (als set)
$total_open_amount = $GLOBALS['total_open_amount'] ?? 0.0;
$count_open        = $GLOBALS['count_open'] ?? 0;
$count_paid        = $GLOBALS['count_paid'] ?? 0;

?>
<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; ?>
<style>
body{background:url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;background-size:cover;}
.dashboard-page{background-color:rgba(255,255,255,.9);padding:3rem 2rem;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);margin:3rem auto 2rem auto;}
.info-box{background:#f5f5f5;border-left:4px solid #5a7d5a;padding:1rem 1.5rem;margin:1.5rem 0;font-size:1rem;color:#333;border-radius:.5rem;box-shadow:0 1px 3px rgba(0,0,0,.05);}
td{text-align:center;}
.badge{display:inline-block;padding:.6rem .8rem;border-radius:8px;font-size:.85rem;line-height:1;font-weight:600}
.bg-success{background:#5a7d5a;color:#fff}.bg-warning{background:#f4e3b2;color:#5a4b2e}.bg-danger{background:#c96a6a;color:#fff}
.bg-secondary{background:#a5a5a5;color:#fff}.bg-dark{background:#4b4b4b;color:#fff}.bg-info{background:#b2d3c2;color:#2f4f4f}.bg-primary{background:#6a8f6a;color:#fff}
form.row.g-2.mb-3{background:rgba(255,255,255,.85);padding:1rem 1.5rem;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,.05);margin:1rem 0 2rem}
form.row.g-2.mb-3 .form-label{font-weight:600;color:#2f4f4f;font-size:.95rem;margin-bottom:.3rem}
form.row.g-2.mb-3 .form-select{border:1px solid #ccc;border-radius:8px;padding:.4rem .6rem;font-size:.95rem;color:#2f4f4f;background:#fff;transition:border-color .2s,box-shadow .2s}
form.row.g-2.mb-3 .btn-primary{background:#5a7d5a;border:none;padding:.6rem;font-size:1rem;font-weight:600;border-radius:8px}
.pagination{display:flex;gap:.5rem;list-style:none;padding-left:0;margin:1rem 0;flex-wrap:wrap}
.page-item.disabled .page-link{pointer-events:none;opacity:.5}
.page-item.active .page-link{background:#5a7d5a;color:#fff;border-color:#5a7d5a}
.page-link{display:inline-flex;align-items:center;justify-content:center;min-width:2.25rem;height:2.25rem;padding:.25rem .75rem;border:1px solid #d0d0d0;border-radius:10px;background:#fff;color:#2f4f4f;text-decoration:none;font-weight:600}
.info-card{border:1px solid #dcdcdc;border-radius:12px;padding:12px;background:#fff;margin:0 0 12px 0}
.info-title{font-weight:700;margin:0 0 6px 0}
.info-row{display:flex;gap:8px;flex-wrap:wrap}
.info-badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:999px;background:#f1f5f4;border:1px solid #dcdcdc;font-size:13px}
.info-muted{color:#777}
.filter-card{background:#fff;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.06);padding:1rem 1rem 1.25rem;margin:1rem 0 1.5rem;border:1px solid #f0f0f0}
.filter-title{font-size:1.1rem;font-weight:700;color:#2e2e2e;margin:.25rem 0 1rem}
.dashboard-welcome{margin-bottom:1.5rem;text-align:left}
.dashboard-welcome h1{font-size:1.6rem;color:#2a5934;margin-bottom:.3rem}
.dashboard-welcome p{color:#555;margin:0}
@media(max-width:768px){
  .dashboard-page{padding:2rem 1rem;margin:2rem 1rem}
}
</style>

<main class="dashboard-page container">
  <div class="dashboard-welcome">
    <h1>Mijn Facturen</h1>
    <p>Bekijk hieronder jouw facturen. Download de PDF of betaal openstaande facturen meteen online.</p>
  </div>

  <!-- Samenvatting -->
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
        Als uitvaartpartner kan je in je dashboard onder<br>
        <strong>“Mijn facturen” → “Facturatie-instellingen”</strong> zelf aanduiden <strong>wanneer</strong>
        en <strong>hoe</strong> je gefactureerd wilt worden.<br>
        Land wordt automatisch bepaald op basis van je btw-nummer.<br>
        <a class="btn btn-primary btn-sm" href="/pages/account/facturen/FacturatieSetup.php" style="margin-top:6px;">
          Facturatie-instellingen openen
        </a>
      </div>
    </div>
  </div>

  <div class="filter-card">
    <?php if(!$needsSetup && !$blockInvoices && $email !== '' && isset($contact)): ?>
    <div class="info-card">
      <div class="info-title">Samenvatting</div>
      <div class="info-row">
        <span class="info-badge">Openstaande facturen: <?= (int)$count_open ?></span>
        <span class="info-badge">Betaalde facturen: <?= (int)$count_paid ?></span>
        <span class="info-badge">Openstaand bedrag: &euro; <?= moneyFormat($total_open_amount) ?></span>
      </div>
    </div>
  <?php endif; ?>
    

  <?php if ($needsSetup): ?>
    <div class="info-box">
      <strong>Instelling vereist:</strong> er is nog geen facturatie-instelling gekozen voor jouw account.
      Kies eerst wie de factuur ontvangt en hoe je gefactureerd wilt worden.
      <br><br>
      <a class="btn btn-primary" href="/pages/account/facturen/FacturatieSetup.php">Facturatie-instellingen kiezen</a>
    </div>

  <?php elseif ($blockInvoices): ?>
    <div class="info-box">
        <strong>Let op:</strong> jouw facturatie staat ingesteld op <em>eindklant</em>.
        Op deze pagina kunnen daarom geen facturen bekeken, gedownload of betaald worden.
        Facturen worden nu rechtstreeks naar de eindklant verstuurd.
        <br><br>
        <a class="btn btn-primary" href="/pages/account/facturen/FacturatieSetup.php">Facturatie-instellingen aanpassen</a>
    </div>

  <?php else: ?>

    <?php if (!$contact): ?>
      <div class="info-box"><p>Er zijn nog geen facturen beschikbaar voor jouw account.</p></div>
    <?php endif; ?>

    <?php if ($contact): ?>
      <div class="card mb-3" style="background:rgba(255,255,255,.85);border-radius:10px;padding:1rem 1.25rem;box-shadow:0 2px 6px rgba(0,0,0,.05);border:1px solid #eee;">
        <h4 class="card-title mb-1" style="margin:0;"><?= htmlspecialchars($contact['name'] ?? 'Onbekende klant') ?></h4>
        <div class="text-muted">
          <?= htmlspecialchars($contact['email'] ?? '') ?>
          <?php if (!empty($contact['vat'])): ?><br>BTW: <?= htmlspecialchars($contact['vat']) ?><?php endif; ?>
        </div>
      </div>

      <form method="get" class="row g-2 mb-3" style="align-items:end;">
        <div class="col-12 col-md-3">
          <label class="form-label">Jaar</label>
          <select name="year" class="form-select">
            <option value="">Alle jaren</option>
            <?php if (!empty($allowedYears)): foreach ($allowedYears as $y): ?>
              <?php if ($y == 2023): ?>
                <option value="2023" <?= ($year === 2023 ? 'selected' : '') ?>>2023</option>
              <?php else: ?>
                <option value="<?= (int)$y ?>" <?= ($year === $y ? 'selected' : '') ?>><?= (int)$y ?></option>
              <?php endif; ?>
            <?php endforeach; endif; ?>
          </select>
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label">Kwartaal</label>
          <select name="q" class="form-select">
            <?php
              $qLabels=[0=>'Alle kwartalen',1=>'Q1 (jan–mrt)',2=>'Q2 (apr–jun)',3=>'Q3 (jul–sep)',4=>'Q4 (okt–dec)'];
              foreach($qLabels as $k=>$label){
                if ($year === 2023 && $k !== 0 && $k < 3) continue;
                $sel = ($k === $quarter) ? 'selected' : '';
                echo "<option value='{$k}' {$sel}>".htmlspecialchars($label)."</option>";
              }
            ?>
          </select>
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label">Weergave</label>
          <select name="show" class="form-select">
            <option value="all"  <?= $show === 'all'  ? 'selected' : '' ?>>Alle facturen</option>
            <option value="open" <?= $show === 'open' ? 'selected' : '' ?>>Alleen openstaande</option>
          </select>
        </div>
        <div class="col-12 col-md-3">
          <button type="submit" class="btn btn-primary w-100">Toon</button>
        </div>
      </form>

      <?php if(!empty($pagedInvoices)): ?>
        <a href="<?= htmlspecialchars(buildUrl(['export'=>'csv','page'=>null])) ?>" class="btn btn-secondary btn-sm" style="margin-bottom:10px;">
          Exporteren als CSV
        </a>
      <?php endif; ?>

      <?php printInvoicesTable(is_array($pagedInvoices)?$pagedInvoices:[], (string)($config['base_url']??'')); ?>

      <?php if($totalPages>1): ?>
        <nav aria-label="Paginering" class="mt-3">
          <ul class="pagination">
            <li class="page-item <?= $page<=1?'disabled':''; ?>">
              <a class="page-link" href="<?= htmlspecialchars(buildUrl(['page'=>max(1,$page-1)])) ?>">« Vorige</a>
            </li>
            <?php for($i=1;$i<=$totalPages;$i++): ?>
              <li class="page-item <?= $i===$page?'active':''; ?>">
                <a class="page-link" href="<?= htmlspecialchars(buildUrl(['page'=>$i])) ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item <?= $page>=$totalPages?'disabled':''; ?>">
              <a class="page-link" href="<?= htmlspecialchars(buildUrl(['page'=>min($totalPages,$page+1)])) ?>">Volgende »</a>
            </li>
          </ul>
          <div class="text-muted" style="margin-top:.5rem;">Totaal: <?= (int)$total ?> &nbsp;|&nbsp; Pagina <?= (int)$page ?> / <?= (int)$totalPages ?></div>
        </nav>
      <?php endif; ?>

      <?php if(!empty($pagedInvoices)): ?>
        <div class="info-muted" style="margin-top:10px;font-size:.85rem;">
          <strong>Legenda:</strong>
          <span class="badge bg-success">Betaald</span>
          <span class="badge bg-warning">Openstaand</span>
          <span class="badge bg-danger">Achterstallig</span>
          <span class="badge bg-primary">Gedeeltelijk betaald</span>
        </div>
      <?php endif; ?>

    <?php endif; // $contact ?>
  <?php endif; // $needsSetup / $blockInvoices ?>
  
  <div class="dashboard-actions mt-4">
    <div class="dashboard-card">
      <h2>Terug naar Overzicht</h2>
      <p>Keer terug naar het facturatiegedeelte van je account.</p>
      <a href="/pages/account/facturen/index.php" class="btn btn-outline-secondary">&larr; Terug naar factuur dashboard</a>
    </div>
  </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
