<?php
include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
session_start();
if(!empty($_SESSION['partner_id'])){ header('Location:/pages/account/orders/cart_to_order.php'); exit; }

if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32));
$csrf=$_SESSION['csrf'];

if(empty($_SESSION['cart'])||!is_array($_SESSION['cart'])) $_SESSION['cart']=['items'=>[],'currency'=>'<sup>€</sup>'];
if(empty($_SESSION['cart']['items'])||!is_array($_SESSION['cart']['items'])) $_SESSION['cart']['items']=[];
$currency = $_SESSION['cart']['currency'] ?? '<sup>€</sup>';

$VAT_RATE=0.21;
function vat_from_gross($g,$r=0.21){ return $g*($r/(1+$r)); } // 21/121

$items=[]; $total=0.0;
foreach(array_values($_SESSION['cart']['items']) as $idx=>$it){
  $name=(string)($it['title']??$it['name']??'Product');
  $ptype=(string)($it['product_type']??'');
  $pid=(int)($it['product_id']??0);
  $price=(float)($it['price']??0);
  $qty=max(1,(int)($it['qty']??1));
  $line=$price*$qty; $total+=$line;

  $vm=null;
  if(isset($it['variant_meta']) && $it['variant_meta']!==''){
    $dec=is_array($it['variant_meta'])?$it['variant_meta']:json_decode((string)$it['variant_meta'],true);
    if(is_array($dec)) $vm=$dec;
  }
  $items[]=['idx'=>$idx,'pid'=>$pid,'ptype'=>$ptype,'name'=>$name,'price'=>$price,'qty'=>$qty,'line'=>$line,'line_vat'=>vat_from_gross($line,$VAT_RATE),'variant_meta'=>$vm];
}
if(empty($items)){ header('Location:/pages/orders/cart.php'); exit; }

$vat_total_products=vat_from_gross($total,$VAT_RATE);
function h($s){ return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8'); }

/** ▼▼ INSTELLINGEN (PAS AAN) ▼▼ **/
$SHOP_ADDRESS='Beukenlaan 8, 3930 Hamont-Achel, BE';
$GOOGLE_MAPS_API_KEY = GOOGLE_MAPS_API_KEY;

// Zomer (juni–okt) en winter (nov–mei) openingsuren per weekdag (0=Zon..6=Zat)
$SUMMER_HOURS=[0=>'',
  1=>'19:00–21:00',2=>'19:00–21:00',3=>'19:00–21:00',
  4=>'10:00–21:00',5=>'10:00–21:00',6=>'10:00–18:00'
];
$WINTER_HOURS=[0=>'',
  1=>'19:00–21:00',2=>'19:00–21:00',3=>'19:00–21:00',
  4=>'10:00–18:00',5=>'10:00–18:00',6=>'10:00–18:00'
];
/** ▲▲ INSTELLINGEN ▲▲ **/

/** ▼▼ Uitzonderlijke dagen includen vanaf medewerkers.* (filesystem, geen JSON) ▼▼ **/
$EXCEPTIONS = [];
(function() use (&$EXCEPTIONS){
  $docroot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
  $candidates = [
    // vaak staan subdomeinen als siblings onder dezelfde parent
    dirname($docroot).'/medewerkers.windelsgreen-decoresin.com/status_uitzonderlijk_winkel.php',
    // generieke locaties
    '/var/www/medewerkers.windelsgreen-decoresin.com/public_html/status_uitzonderlijk_winkel.php',
  ];
  $found = null;
  foreach($candidates as $p){
    if(is_readable($p)){ $found = $p; break; }
  }
  if(!$found){
    // laatste poging: 1 niveau hoger zoeken op naam
    $maybe = glob(dirname($docroot).'/*/status_uitzonderlijk_winkel.php');
    if(is_array($maybe)){
      foreach($maybe as $p){ if(is_readable($p)){ $found=$p; break; } }
    }
  }
  if($found){
    // include in geïsoleerde scope om alleen $uitzonderlijkeDagen te pakken
    $uitzonderlijkeDagen = [];
    (function($file,&$uitzonderlijkeDagen){
      include $file; // verwacht dat dit $uitzonderlijkeDagen definieert
      if(!isset($uitzonderlijkeDagen) || !is_array($uitzonderlijkeDagen)) $uitzonderlijkeDagen=[];
    })($found,$uitzonderlijkeDagen);

    // normaliseren naar EXCEPTIONS voor JS
    foreach($uitzonderlijkeDagen as $d=>$arr){
      $EXCEPTIONS[$d]=[
        'status'=>isset($arr[0])?strtolower(trim($arr[0])):'gesloten',
        'reason'=>$arr[1]??'',
        'start'=>$arr[2]??'',
        'end'=>$arr[3]??'',
      ];
    }
  }
})();
/** ▲▲ Einde uitzonderlijke dagen ▲▲ **/

include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';
?>
<link rel="stylesheet" href="/css/orders/cart_to_order.css">

<main class="checkout-wrap">
  <h2 class="h2">Afrekenen</h2>
  <div class="grid">
    <section>
      <form action="/pages/orders/betaal.php" method="post" id="order-form" novalidate>
        <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
        <?php foreach($items as $i=>$it){
          echo '<input type="hidden" name="producten['.$i.'][id]" value="'.(int)$it['pid'].'">';
          echo '<input type="hidden" name="producten['.$i.'][qty]" value="'.(int)$it['qty'].'">';
        } ?>

        <fieldset>
          <legend>Klantgegevens</legend>
          <label for="klant_naam">Naam*</label>
          <input type="text" name="klant_naam" id="klant_naam" required>

          <label for="klant_email">E-mail*</label>
          <input type="email" name="klant_email" id="klant_email" required>

          <label for="klant_telefoon">Telefoon*</label>
          <input type="text" name="klant_telefoon" id="klant_telefoon" required>

          <label for="klant_adres">Adres*</label>
          <textarea name="klant_adres" id="klant_adres" rows="3" required placeholder="Straat + nr, postcode, gemeente, land"></textarea>

          <label for="klant_land">Land*</label>
          <select name="klant_land" id="klant_land" required>
            <option value="BE" selected>België</option>
            <option value="NL">Nederland</option>
          </select>

          <div class="small muted">Afstand wordt berekend met Google Distance Matrix (rijafstand). Indien geen resultaat: handmatige km-invoer beschikbaar.</div>
          <div class="row" style="margin-top:6px">
            <button type="button" class="btn" id="btn_calc_distance">Bereken afstand</button>
            <span class="small muted" id="distance_status">Nog niet berekend</span>
          </div>

          <label for="partner_opmerking">Opmerking (optioneel)</label>
          <textarea name="partner_opmerking" id="partner_opmerking" rows="4" maxlength="5000" placeholder="Bijv. gravure, leveropmerking…"></textarea>
        </fieldset>

        <!-- Sectie 1 - As aanleveren -->
        <fieldset>
          <legend>As aanleveren</legend>

          <label class="inline">
            <input type="radio" name="ashes_delivery_method" value="zelf_bezorgen" required>
            <span>Zelf bezorgen</span>
          </label>
          <div id="ashes_self_info" class="small muted hidden" style="margin-top:6px">
            Adres: <span class="badge"><?= h($SHOP_ADDRESS) ?></span>
            <div style="margin-top:6px">
              <div class="small muted" style="margin-bottom:4px">Openingsuren</div>
              <ul id="self_opening_hours" class="small" style="margin:0;padding-left:16px"></ul>
            </div>
          </div>

          <label class="inline" style="margin-top:8px">
            <input type="radio" name="ashes_delivery_method" value="afgehaald_door_ons">
            <span>Wordt afgehaald door ons  (tegen tarief)</span>
          </label>
          <div id="ashes_collect_box" class="hidden" style="margin-top:6px">
            <div class="row">
              <div style="flex:1;min-width:220px">
                <label for="ashes_collect_date">Datum</label>
                <input type="date" id="ashes_collect_date" name="ashes_collect_date" min="<?= date('Y-m-d') ?>">
              </div>
              <div style="flex:1;min-width:220px">
                <label for="ashes_collect_time">Uur</label>
                <input type="time" id="ashes_collect_time" name="ashes_collect_time" step="900">
              </div>
            </div>
            <div id="exception_msg" class="note hidden" style="margin-top:6px"></div>
            <div class="note" style="margin-top:8px">Kies wanneer wij bij u mogen langskomen om de as op te halen.</div>
            <div class="row">
              <div style="flex:1;min-width:220px">
                <label>Afstand vanaf winkel</label>: 
                <span class="badge" id="distance_display_ashes">0 km</span>
                <div class="small muted">
                  Tarief: <span id="delivery_tariff_text"><sup>€</sup>3,80 per 10 km</span> (naar boven afgerond), max 50 km
                </div>
                <div class="small warn hidden" id="distance_warning_ashes" style="margin-top:6px"></div>
              </div>
              <div style="flex:1;min-width:220px">
                <div class="small muted">
                  <label>Kost enkel rit (incl. btw)</label>
                  <div class="badge" id="fee_badge_ashes"><sup>€</sup>0,00</div>
                  <div class="small muted">Waarvan btw (21%): <span id="fee_vat_ashes"><sup>€</sup>0,00</span></div>
                </div>
              </div>
            </div> 
          </div>
         
          <label class="inline" style="margin-top:8px">
            <input type="radio" name="ashes_delivery_method" value="koerier">
            <span>Verzenden via koerier</span>
          </label>
          <div id="ashes_courier_note" class="small note hidden" style="margin-top:6px">
            Verzending via koerier gebeurt <strong>op eigen risico</strong>. Verpak de as veilig volgens de instructies die je per e-mail ontvangt.
          </div>
        </fieldset>

        <!-- Sectie 2 - Afgewerkt product ontvangen -->
        <fieldset>
          <legend>Afgewerkt product ontvangen</legend>

          <label class="inline"><input type="radio" name="finished_delivery_method" value="afhalen_winkel" required> <span>Afhalen in winkel</span></label>
          <div id="finished_pickup_info" class="small muted hidden">
            Adres: <span class="badge"><?= h($SHOP_ADDRESS) ?></span>
            <div style="margin-top:6px">
              <div class="small muted" style="margin-bottom:4px">Openingsuren</div>
              <ul id="finished_opening_hours" class="small" style="margin:0;padding-left:16px"></ul>
            </div>
          </div>

          <label class="inline" style="margin-top:8px"><input type="radio" name="finished_delivery_method" value="bezorgen"> <span>Bezorgen (tegen tarief)</span></label>
          <div id="finished_delivery_box" class="hidden" style="margin-top:6px">
            <div class="row">
              <div style="flex:1;min-width:220px">
                <label>Afstand vanaf winkel</label>
                <div class="badge" id="distance_display">0 km</div>
                <div class="small muted">
                  Tarief: <span id="delivery_tariff_text"><sup>€</sup>3,80 per 10 km</span> (naar boven afgerond), max 50 km
                </div>

                <div class="small warn" id="distance_warning" style="display:none">
                  Afstand kon niet automatisch berekend worden. Vul onderstaande <b>handmatige afstand</b> in.
                </div>
                <div id="manual_distance_wrap" class="hidden" style="margin-top:6px">
                  <label for="manual_distance_km">Handmatige afstand (km)</label>
                  <input type="number" id="manual_distance_km" min="1" max="50" step="0.1" placeholder="bijv. 12.5">
                  <div class="small muted">We hanteren een maximumafstand van 50 km.</div>
                </div>
              </div>
              <div style="flex:1;min-width:220px">
                <!-- B: enkel rit bedrag (bezorgen) -->
                <label>Kost enkel rit (incl. btw)</label>
                <div class="badge" id="fee_badge_finished"><sup>€</sup>0,00</div>
                <div class="small muted">Waarvan btw (21%): <span id="fee_vat_finished"><sup>€</sup>0,00</span></div>

                <!-- C: totaal 2 ritten (alleen bij beide opties actief) -->
                <div class="small note hidden" id="finished_total_row" style="margin-top:6px">
                  Totaal leveringskosten (2 ritten): <span class="badge" id="finished_total_fee"><sup>€</sup>0,00</span>
                </div>
              </div>
          </div>

          <!-- Verborgen velden voor backend -->
          <input type="hidden" name="finished_delivery_fee" id="finished_delivery_fee" value="0">
          <input type="hidden" name="distance_km_raw" id="distance_km_raw" value="">
          <input type="hidden" name="distance_km_capped" id="distance_km_capped" value="">
          <input type="hidden" name="distance_method" id="distance_method" value="">
        </fieldset>

        <div style="display:flex;gap:10px;flex-wrap:wrap">
          <a class="btn" href="/pages/orders/cart.php">← Terug naar winkelwagen</a>
          <button type="submit" class="btn btn-primary">Ga naar betalen</button>
        </div>

        <!-- Hidden totals -->
        <input type="hidden" id="order_products_total" value="<?= number_format($total,2,'.','') ?>">
        <input type="hidden" name="delivery_cost_total" id="delivery_cost_total" value="0">
        <input type="hidden" name="vat_products_total" id="vat_products_total" value="<?= number_format($vat_total_products,2,'.','') ?>">
        <input type="hidden" name="vat_delivery_total" id="vat_delivery_total" value="0">
        <input type="hidden" id="out_of_zone_flag" value="0">
        <input type="hidden" name="vat_order_total" id="vat_order_total" value="<?= number_format($vat_total_products,2,'.','') ?>">
        <input type="hidden" name="net_order_total" id="net_order_total" value="<?= number_format($total-$vat_total_products,2,'.','') ?>">
      </form>
    </section>

    <!-- Samenvatting -->
    <aside>
      <div class="summary">
        <h3 class="h2" style="margin-top:0">Overzicht</h3>
        <table class="table">
          <thead>
            <tr>
              <th>Product</th><th>Type</th><th><sup>€</sup>/st</th><th>Aantal</th>
              <th>Lijn (incl.)</th><th>BTW (21%)</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($items as $it):
            $name=h($it['name']); $ptype=h($it['ptype']);
            $price=number_format($it['price'],2,',','.');
            $qty=(int)$it['qty']; $lineGross=$it['line'];
            $line=number_format($lineGross,2,',','.');
            $lineVat=number_format($it['line_vat'],2,',','.');
            $vm=$it['variant_meta'] ?? null;

            $variantHtml='';
            if(is_array($vm) && (!empty($vm['color']) || (!empty($vm['options']) && is_array($vm['options'])))){
              $parts=[];
              if(!empty($vm['color'])){ $hex=h($vm['color']); $parts[]='<span class="variant-color"><span class="swatch" style="background:'.$hex.'"></span>'.$hex.'</span>'; }
              if(!empty($vm['options'])){ $chips=''; foreach($vm['options'] as $op){ $chips.='<span class="variant-chip">'.h($op).'</span>'; } $parts[]=$chips; }
              if($parts){ $variantHtml='<div class="variant-mini">'.implode(' ',$parts).'</div>'; }
            }
          ?>
            <tr>
              <td><?= $name ?><?= $variantHtml ?></td>
              <td><?= $ptype ?></td>
              <td><?= $currency ?><?= $price ?></td>
              <td><?= $qty ?></td>
              <td><?= $currency ?><?= $line ?></td>
              <td><?= $currency ?><?= $lineVat ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>

        <div class="hr"></div>
        <div class="total-sub"><div>BTW op producten (21%):</div><div id="summary_vat_products"><?= $currency ?><?= number_format($vat_total_products,2,',','.') ?></div></div>
        <div id="summary_delivery_lines" class="total-sub hidden"><div>Leveringskosten (incl.):</div><div id="summary_delivery_amount"><?= $currency ?>0,00</div></div>
        <div id="summary_delivery_vat_line" class="total-sub hidden"><div>BTW op levering (21%):</div><div id="summary_vat_delivery"><?= $currency ?>0,00</div></div>

        <div class="hr"></div>
        <div class="total-sub"><div>Netto (excl. btw):</div><div id="summary_net_total"><?= $currency ?><?= number_format($total-$vat_total_products,2,',','.') ?></div></div>
        <div class="total-sub"><div>BTW totaal (21%):</div><div id="summary_vat_total"><?= $currency ?><?= number_format($vat_total_products,2,',','.') ?></div></div>
        <div class="total-line"><div>Totaal te betalen (incl.): <?= $currency ?><span id="grand_total"><?= number_format($total,2,',','.') ?></span></div></div>
      </div>
    </aside>
  </div>
</main>

<script>
(function(){
  // ===== Helpers eerst (zodat we ze meteen kunnen gebruiken) =====
  const byId  = id => document.getElementById(id);
  const qSel  = (s, r=document) => r.querySelector(s);
  const setHidden = (el,flag) => el && el.classList.toggle('hidden', !!flag);
  const fmtEuro = v => new Intl.NumberFormat('nl-BE',{style:'currency',currency:'EUR'}).format(v);

  // ===== Config =====
  const GOOGLE_KEY    = <?= json_encode($GOOGLE_MAPS_API_KEY) ?>;
  const SHOP_ADDRESS  = <?= json_encode($SHOP_ADDRESS,JSON_UNESCAPED_UNICODE) ?>;
  const SUMMER_HOURS  = <?= json_encode($SUMMER_HOURS,JSON_UNESCAPED_UNICODE) ?>;
  const WINTER_HOURS  = <?= json_encode($WINTER_HOURS,JSON_UNESCAPED_UNICODE) ?>;
  const EXCEPTIONS    = <?= json_encode($EXCEPTIONS,JSON_UNESCAPED_UNICODE) ?>;
  const VAT_RATE      = 0.21;
  const MAX_KM        = 50;

  // ===== DOM refs =====
  const klantAdres = byId('klant_adres');
  const landSel    = byId('klant_land');
  const btnCalc    = byId('btn_calc_distance');
  const distanceStatus = byId('distance_status');
  const distanceDisplayAshes = byId('distance_display_ashes');
  const distanceWarningAshes = byId('distance_warning_ashes');

  const ashesSelfInfo   = byId('ashes_self_info');
  const ashesCollectBox = byId('ashes_collect_box');
  const ashesCollectDate= byId('ashes_collect_date');
  const ashesCollectTime= byId('ashes_collect_time');
  const ashesCourierNote= byId('ashes_courier_note');

  const selfOpeningHoursList     = byId('self_opening_hours');
  const finishedOpeningHoursList = byId('finished_opening_hours');

  const finishedPickupInfo = byId('finished_pickup_info');
  const finishedDeliveryBox= byId('finished_delivery_box');

  const distanceDisplay = byId('distance_display');
  const distanceWarning = byId('distance_warning');
  const manualWrap      = byId('manual_distance_wrap');
  const manualKmEl      = byId('manual_distance_km');

  const deliveryTariffText = byId('delivery_tariff_text');

  const finishedFeeBadge = byId('finished_delivery_fee_badge');
  const finishedFeeVatEl = byId('finished_delivery_fee_vat');
  const finishedFeeHidden= byId('finished_delivery_fee');
  // Nieuwe badges/rijen (A/B/C)
  const feeBadgeAshes     = byId('fee_badge_ashes');
  const feeVatAshes       = byId('fee_vat_ashes');
  const feeBadgeFinished  = byId('fee_badge_finished');
  const feeVatFinished    = byId('fee_vat_finished');
  const finishedTotalRow  = byId('finished_total_row');
  const finishedTotalFee  = byId('finished_total_fee');


  // Overzicht (1 lijn)
  const summaryDeliveryLines   = byId('summary_delivery_lines');
  const summaryDeliveryAmount  = byId('summary_delivery_amount');
  const summaryDeliveryVatLine = byId('summary_delivery_vat_line');
  const summaryVatDelivery     = byId('summary_vat_delivery');
  const summaryVatTotal        = byId('summary_vat_total');
  const summaryNetTotal        = byId('summary_net_total');
  const grandTotalEl           = byId('grand_total');

  // Totals/hidden
  const productsTotal = parseFloat(byId('order_products_total').value || '0');
  const vatProducts   = parseFloat(byId('vat_products_total').value   || '0');

  const deliveryCostTotalHidden = byId('delivery_cost_total');
  const vatDeliveryHidden       = byId('vat_delivery_total');
  const vatOrderHidden          = byId('vat_order_total');
  const netOrderHidden          = byId('net_order_total');
  const distanceRawHidden       = byId('distance_km_raw');
  const distanceCappedHidden    = byId('distance_km_capped');
  const distanceMethodHidden    = byId('distance_method');
  const outOfZoneFlag           = byId('out_of_zone_flag');

  // ===== Utils =====
  function vatFromGross(g){ return g*(VAT_RATE/(1+VAT_RATE)); }
  function getSeasonHoursTableForMonth(m){ return (m>=6 && m<=10)?SUMMER_HOURS:WINTER_HOURS; }
  function getExceptionForDate(dateStr){ return (EXCEPTIONS && EXCEPTIONS[dateStr]) ? EXCEPTIONS[dateStr] : null; }
  function isExceptionClosed(dateStr){ const ex=getExceptionForDate(dateStr); return !!(ex && String(ex.status).toLowerCase()==='gesloten'); }
  function setExceptionMessage(dateStr){
    const box=byId('exception_msg'); if(!box) return;
    const ex=getExceptionForDate(dateStr);
    if(!ex){ box.classList.add('hidden'); box.textContent=''; return; }
    if(ex.status==='gesloten'){
      box.classList.remove('hidden'); box.innerHTML='<b>Uitzonderlijk gesloten</b>. De datum is verschoven naar de eerstvolgende openingsdag.';
    }else if(ex.status==='open' && ex.start && ex.end){
      box.classList.remove('hidden'); box.innerHTML=`Uitzonderlijk <b>open</b> met aangepaste openingsuren: <b>${ex.start}–${ex.end}</b>.`;
    }else{ box.classList.add('hidden'); box.textContent=''; }
  }
  function getHoursForDateStr(dateStr){
    if(!dateStr) return '';
    const ex=getExceptionForDate(dateStr);
    if(ex){
      if(ex.status==='gesloten') return '';
      if(ex.status==='open' && ex.start && ex.end) return `${ex.start}–${ex.end}`;
    }
    const d=new Date(dateStr+'T00:00:00'); if(isNaN(d)) return '';
    const m=d.getMonth()+1, wd=d.getDay();
    const table=getSeasonHoursTableForMonth(m);
    return table[wd]||'';
  }
  function parseHoursRange(str){
    if(!str||typeof str!=='string'||!str.includes('–')) return null;
    const [a,b]=str.split('–').map(s=>s.trim());
    if(!/^\d{2}:\d{2}$/.test(a)||!/^\d{2}:\d{2}$/.test(b)) return null;
    return {start:a,end:b};
  }
  function roundToStep(timeStr,stepSeconds){
    const [hh,mm]=timeStr.split(':').map(x=>parseInt(x,10));
    let secs=hh*3600+mm*60;
    const r=Math.round(secs/stepSeconds)*stepSeconds;
    const H=Math.floor(r/3600), M=Math.floor((r%3600)/60);
    return (H<10?'0':'')+H+':'+(M<10?'0':'')+M;
  }

  // ===== Datum/uur as-afhalen =====
  const TIME_STEP_SEC=900;
  function disableTimePicker(){
    ashesCollectTime.value=''; ashesCollectTime.disabled=true;
    ashesCollectTime.min=''; ashesCollectTime.max=''; ashesCollectTime.step=TIME_STEP_SEC;
  }
  function setTimeConstraintsForDate(dateStr){
    if(!ashesCollectTime) return false;
    if(isExceptionClosed(dateStr)){ disableTimePicker(); setExceptionMessage(dateStr); return false; }
    const range=parseHoursRange(getHoursForDateStr(dateStr));
    if(!range){ disableTimePicker(); setExceptionMessage(dateStr); return false; }
    setExceptionMessage(dateStr);
    ashesCollectTime.disabled=false; ashesCollectTime.step=TIME_STEP_SEC;
    ashesCollectTime.min=range.start; ashesCollectTime.max=range.end;
    if(!ashesCollectTime.value || ashesCollectTime.value<range.start || ashesCollectTime.value>range.end){
      ashesCollectTime.value=roundToStep(range.start,TIME_STEP_SEC);
    }
    return true;
  }
  function ensureValidPickupDateAndTime(){
    if(!ashesCollectDate) return;
    const todayStr=new Date().toISOString().slice(0,10);
    if(!ashesCollectDate.min) ashesCollectDate.min=todayStr;
    if(ashesCollectDate.value && ashesCollectDate.value<todayStr) ashesCollectDate.value=todayStr;
    setTimeConstraintsForDate(ashesCollectDate.value||todayStr);
  }

  // ===== Opening hours render =====
  function renderOpeningHoursInto(targetUl){
    if(!targetUl) return;
    const m=(new Date()).getMonth()+1, tbl=getSeasonHoursTableForMonth(m), labels=['Zon','Maa','Din','Woe','Don','Vri','Zat'];
    targetUl.innerHTML='';
    for(let i=1;i<=6;i++){
      const li=document.createElement('li');
      li.textContent=`${labels[i]}: ${tbl[i]||'gesloten'}`;
      targetUl.appendChild(li);
    }
  }

  // ===== UI toggles =====
  function updateAshesUI(){
    const v=(qSel('input[name="ashes_delivery_method"]:checked')||{}).value;
    setHidden(ashesSelfInfo, v!=='zelf_bezorgen'); if(v==='zelf_bezorgen') renderOpeningHoursInto(selfOpeningHoursList);
    setHidden(ashesCollectBox, v!=='afgehaald_door_ons');
    if(v==='afgehaald_door_ons'){ ensureValidPickupDateAndTime(); }
    else{
      if(ashesCollectDate) ashesCollectDate.value='';
      if(ashesCollectTime){ ashesCollectTime.value=''; ashesCollectTime.disabled=false; ashesCollectTime.min=''; ashesCollectTime.max=''; }
      setExceptionMessage('');
    }
    setHidden(ashesCourierNote, v!=='koerier');
  }

  function updateFinishedUI(){
    const am=(qSel('input[name="ashes_delivery_method"]:checked')||{}).value;
    const fm=(qSel('input[name="finished_delivery_method"]:checked')||{}).value;
    setHidden(finishedPickupInfo, fm!=='afhalen_winkel'); if(fm==='afhalen_winkel') renderOpeningHoursInto(finishedOpeningHoursList);
    setHidden(finishedDeliveryBox, fm!=='bezorgen');
    if(fm!=='bezorgen'){ if(am==='afgehaald_door_ons') refreshDistanceAndTotalsIfNeeded(); else applyDelivery(0,''); }
  }

  // ===== Tarief/levering =====
  // Altijd <sup>€</sup>3,80 per 10 km per rit (pro rata). Kies je én as-afhalen én bezorging: dan optellen.
  function getRatePer10Km(){ return 3.80; }

  function feeFromKm(km, ratePer10){
    if(!km || km<=0) return 0;
    const capped = Math.min(km, MAX_KM);
    const rate   = ratePer10 || 3.80;
    const fee    = (capped/10)*rate;        // pro rata (geen afronding naar blokken)
    return Math.round((fee + Number.EPSILON) * 100) / 100;
  }

  function applyDelivery(distanceKm, method){
    const raw = Math.max(0, distanceKm || 0);
    const outOfZone = raw > MAX_KM;           // >>> nieuw
    const capped = Math.min(raw, MAX_KM);
    const rate = getRatePer10Km();

    const am=(qSel('input[name="ashes_delivery_method"]:checked')||{}).value;
    const fm=(qSel('input[name="finished_delivery_method"]:checked')||{}).value;

    // Meld buiten zone + blokkeer berekening
    if (outOfZone){
      if (distanceDisplay) distanceDisplay.textContent = raw.toFixed(1) + ' km';
      if (distanceDisplayAshes) distanceDisplayAshes.textContent = raw.toFixed(1) + ' km';

      if (distanceWarning) {
        distanceWarning.style.display = (fm === 'bezorgen') ? 'block' : 'none';
        if (fm === 'bezorgen') {
          distanceWarning.innerHTML = 'Buiten leveringsgebied: we leveren maximaal <b>50 km</b> vanaf de winkel.';
        }
      }
      if (distanceWarningAshes) {
        if (am === 'afgehaald_door_ons') {
          distanceWarningAshes.classList.remove('hidden');
          distanceWarningAshes.innerHTML = 'Buiten afhaalsgebied: we komen afhalen maximaal <b>50 km</b> vanaf de winkel.';
        } else {
          distanceWarningAshes.classList.add('hidden');
        }
      }
      // verberg leveringsregels in overzicht
      summaryDeliveryLines.classList.add('hidden');
      summaryDeliveryVatLine.classList.add('hidden');

      // zet badges op <sup>€</sup>0,00
      // Badge in finished-blok = enkel-rit (bezorgen)
      const badgeValue = (fm === 'bezorgen') ? feeFinished : 0;

      // Toon nieuw A/B-blok
      if (feeBadgeAshes)    feeBadgeAshes.textContent    = fmtEuro(feeAshes);
      if (feeVatAshes)      feeVatAshes.textContent      = fmtEuro(vatFromGross(feeAshes));
      if (feeBadgeFinished) feeBadgeFinished.textContent = fmtEuro(feeFinished);
      if (feeVatFinished)   feeVatFinished.textContent   = fmtEuro(vatFromGross(feeFinished));

      // (Belangrijk) Houd de oude badge "Kost levering (incl. btw)" in sync met de enkel-rit
      if (finishedFeeBadge) finishedFeeBadge.textContent = fmtEuro(badgeValue);
      if (finishedFeeVatEl) finishedFeeVatEl.textContent = fmtEuro(vatFromGross(badgeValue));

      // C: totaal tonen enkel als beide ritten actief zijn
      if (finishedTotalRow) finishedTotalRow.classList.toggle('hidden', !(am==='afgehaald_door_ons' && fm==='bezorgen'));
      if (finishedTotalFee) finishedTotalFee.textContent = fmtEuro(feeTotal);


      // hidden velden resetten
      deliveryCostTotalHidden.value = '0.00';
      vatDeliveryHidden.value = '0.00';
      finishedFeeHidden.value = '0.00';
      distanceRawHidden.value = raw.toFixed(3);
      distanceCappedHidden.value = capped.toFixed(3);
      if (method) distanceMethodHidden.value = method;
      if (outOfZoneFlag) outOfZoneFlag.value = '1';     // >>> markeer blokkade

      // totaalbedrag (zonder levering) opnieuw tonen
      const vatTotal = parseFloat(byId('vat_products_total').value || '0');
      const grand    = parseFloat(byId('order_products_total').value || '0');
      const net      = grand - vatTotal;
      grandTotalEl.textContent = (fmtEuro(grand)).replace('<sup>€</sup>','').trim();
      summaryVatTotal.textContent = fmtEuro(vatTotal);
      summaryNetTotal.textContent = fmtEuro(net);
      return; // >>> stop: bestelling is buiten zone
    } else {
      if (outOfZoneFlag) outOfZoneFlag.value = '0';
      if (distanceWarning) distanceWarning.style.display = 'none';
    }

    // --- normale berekening (<= 50 km) ---
    const oneWayFee = feeFromKm(capped, rate);
    const feeAshes    = (am==='afgehaald_door_ons') ? oneWayFee : 0;
    const feeFinished = (fm==='bezorgen') ? oneWayFee : 0;
    const feeTotal    = feeAshes + feeFinished;
    const bothSelected = (am==='afgehaald_door_ons' && fm==='bezorgen');

   
    if (distanceDisplay) distanceDisplay.textContent = capped.toFixed(1) + ' km';
    if (distanceDisplayAshes) distanceDisplayAshes.textContent = capped.toFixed(1) + ' km';

    if (feeBadgeAshes)    feeBadgeAshes.textContent    = fmtEuro(feeAshes);
    if (feeVatAshes)      feeVatAshes.textContent      = fmtEuro(vatFromGross(feeAshes));
    if (feeBadgeFinished) feeBadgeFinished.textContent = fmtEuro(feeFinished);
    if (feeVatFinished)   feeVatFinished.textContent   = fmtEuro(vatFromGross(feeFinished));

    if (finishedTotalRow) finishedTotalRow.classList.toggle('hidden', !bothSelected);
    if (finishedTotalFee) finishedTotalFee.textContent = fmtEuro(feeTotal);

    const vatDelivery = vatFromGross(feeTotal);
    const vatTotal = (parseFloat(byId('vat_products_total').value||'0')) + vatDelivery;
    const grand = (parseFloat(byId('order_products_total').value||'0')) + feeTotal;
    const net = grand - vatTotal;

    summaryDeliveryLines.classList.toggle('hidden', !(feeTotal>0));
    summaryDeliveryVatLine.classList.toggle('hidden', !(feeTotal>0));
    summaryDeliveryAmount.textContent = fmtEuro(feeTotal);
    summaryVatDelivery.textContent = fmtEuro(vatDelivery);

    grandTotalEl.textContent=(fmtEuro(grand)).replace('<sup>€</sup>','').trim();
    summaryVatTotal.textContent=fmtEuro(vatTotal);
    summaryNetTotal.textContent=fmtEuro(net);

    deliveryCostTotalHidden.value=feeTotal.toFixed(2);
    vatDeliveryHidden.value=vatDelivery.toFixed(2);
    finishedFeeHidden.value=feeTotal.toFixed(2);
    distanceRawHidden.value=raw.toFixed(3);
    distanceCappedHidden.value=capped.toFixed(3);
    if (method) distanceMethodHidden.value=method;
  }


  // ===== Afstand via proxy =====
  async function distanceMatrixViaProxy(origin,destination,country){
    const params=new URLSearchParams({origin,destination,country:(country||'').toUpperCase(),mode:'driving'});
    const res=await fetch('/api/google_distance.php?'+params.toString(),{cache:'no-store'});
    if(!res.ok) throw new Error('Proxy HTTP '+res.status);
    const data=await res.json();
    if(!data.ok) throw new Error(data.error||'Proxy error');
    return data.km;
  }
  async function calcDistanceGoogle(){
    const addrCustomer=(klantAdres.value||'').trim();
    if(!addrCustomer){ distanceStatus.textContent='Geen klantadres'; return null; }
    const country=(landSel?.value||'BE').toUpperCase();
    distanceStatus.textContent='Afstand berekenen…';
    try{
      const km=await distanceMatrixViaProxy(<?= json_encode($SHOP_ADDRESS) ?>,addrCustomer,country);
      distanceStatus.textContent='Rijafstand (one-way): '+km.toFixed(1)+' km';
      return {km,method:'google_driving_proxy'};
    }catch(e){
      console.error(e);
      distanceStatus.textContent='Afstand berekenen mislukt';
      return null;
    }
  }

  async function refreshDistanceAndTotalsIfNeeded(){
    const am=(qSel('input[name="ashes_delivery_method"]:checked')||{}).value;
    const fm=(qSel('input[name="finished_delivery_method"]:checked')||{}).value;
    const needsDistance = (fm==='bezorgen') || (am==='afgehaald_door_ons');
    if(!needsDistance){ applyDelivery(0,''); return; }

    if (distanceWarning) distanceWarning.style.display = 'none';
    if (distanceWarningAshes) distanceWarningAshes.classList.add('hidden');
    if(manualWrap) manualWrap.classList.add('hidden');

    const result=await calcDistanceGoogle();
    const km=result?.km||0;
    if(km>0){ applyDelivery(km,result.method); return; }

    // fallback handmatig
    if (fm === 'bezorgen' && distanceWarning) {
      distanceWarning.style.display = 'block';
      distanceWarning.innerHTML = 'Afstand kon niet automatisch berekend worden. Vul onderstaande <b>handmatige afstand</b> in.';
    }
    if (am === 'afgehaald_door_ons' && distanceWarningAshes) {
      distanceWarningAshes.classList.remove('hidden');
      distanceWarningAshes.innerHTML = 'Afstand kon niet automatisch berekend worden. <b>Bereken opnieuw</b> of vul handmatig onderaan in.';
    }
    if(manualWrap) manualWrap.classList.remove('hidden');
    const manualVal=parseFloat(manualKmEl?.value||'');
    if(manualVal>0){ distanceStatus.textContent='Handmatige afstand (one-way): '+manualVal.toFixed(1)+' km'; applyDelivery(manualVal,'manual_input'); }
    else{ applyDelivery(0,''); }
  }

  // ===== Listeners =====
  document.querySelectorAll('input[name="ashes_delivery_method"]').forEach(r=>r.addEventListener('change',()=>{ updateAshesUI(); refreshDistanceAndTotalsIfNeeded(); }));
  document.querySelectorAll('input[name="finished_delivery_method"]').forEach(r=>r.addEventListener('change',()=>{ updateFinishedUI(); refreshDistanceAndTotalsIfNeeded(); }));

  if(ashesCollectDate){ ashesCollectDate.addEventListener('change',()=>{ setTimeConstraintsForDate(ashesCollectDate.value); }); }
  if(ashesCollectTime){
    ashesCollectTime.addEventListener('change',()=>{
      const range=parseHoursRange(getHoursForDateStr(ashesCollectDate?.value));
      if(range){
        if(ashesCollectTime.value<range.start) ashesCollectTime.value=range.start;
        if(ashesCollectTime.value>range.end)   ashesCollectTime.value=range.end;
      }
    });
  }

  if(btnCalc)      btnCalc.addEventListener('click',refreshDistanceAndTotalsIfNeeded);
  if(klantAdres)   klantAdres.addEventListener('blur',refreshDistanceAndTotalsIfNeeded);
  if(landSel)      landSel.addEventListener('change',refreshDistanceAndTotalsIfNeeded);
  if(manualKmEl)   manualKmEl.addEventListener('input',refreshDistanceAndTotalsIfNeeded);

  // ===== Init =====
  updateAshesUI();
  updateFinishedUI();
  if((qSel('input[name="ashes_delivery_method"]:checked')||{}).value==='zelf_bezorgen'){ renderOpeningHoursInto(selfOpeningHoursList); }
  if((qSel('input[name="finished_delivery_method"]:checked')||{}).value==='afhalen_winkel'){ renderOpeningHoursInto(finishedOpeningHoursList); }
  if(ashesCollectDate?.value){ setTimeConstraintsForDate(ashesCollectDate.value); }

  // ===== Submit-validatie =====
  document.getElementById('order-form').addEventListener('submit', async function(e){
    const am=(qSel('input[name="ashes_delivery_method"]:checked')||{}).value;
    const fm=(qSel('input[name="finished_delivery_method"]:checked')||{}).value;

    // As-afhalen: datum/uur binnen openingsuren
    if(am==='afgehaald_door_ons'){
      const ds=ashesCollectDate?.value||'';
      if(!ds){ e.preventDefault(); alert('Gelieve een datum te kiezen wanneer wij de as mogen afhalen.'); return; }
      if(isExceptionClosed(ds)){ e.preventDefault(); alert('De gekozen datum is uitzonderlijk gesloten. Kies een andere datum.'); return; }
      const range=parseHoursRange(getHoursForDateStr(ds));
      if(!range){ e.preventDefault(); alert('De gekozen datum valt op een dag dat we gesloten zijn. Kies een andere dag aub.'); return; }
      if(!ashesCollectTime?.value){ e.preventDefault(); alert('Gelieve een uur te kiezen wanneer wij de as mogen afhalen.'); return; }
      if(ashesCollectTime.value<range.start || ashesCollectTime.value>range.end){
        e.preventDefault(); alert('Het gekozen uur ligt buiten de openingsuren. Kies een uur tussen '+range.start+' en '+range.end+'.'); return;
      }
    }

    // Afstand verplicht indien nodig
    const needsDistance = (fm==='bezorgen') || (am==='afgehaald_door_ons');
    if(needsDistance && !(parseFloat(distanceCappedHidden.value||'0')>0)){
      e.preventDefault();
      await refreshDistanceAndTotalsIfNeeded();
      if(!(parseFloat(distanceCappedHidden.value||'0')>0)){
        alert('Afstand niet beschikbaar. Vul handmatig de afstand (km) in of controleer het adres.');
        return;
      }
    }

    // Harde blokkade buiten zone
    const rawKm = parseFloat(distanceRawHidden.value || '0');
    if((fm==='bezorgen' || am==='afgehaald_door_ons') && rawKm > MAX_KM){
      e.preventDefault();
      alert('Buiten leveringsgebied: we leveren maximaal 50 km vanaf de winkel.');
      return;
    }
    if(outOfZoneFlag && outOfZoneFlag.value==='1'){
      e.preventDefault();
      alert('Buiten leveringsgebied: we leveren maximaal 50 km vanaf de winkel.');
      return;
    }
  });
})();
</script>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
<?php

// kleine fonteinstraat 9, 3950 bocholt