<?php

// Start sessie (met veilige cookie-instellingen indien mogelijk)
if(session_status()!==PHP_SESSION_ACTIVE){
  session_set_cookie_params([
    'httponly'=>true,
    'samesite'=>'Lax',
    'secure'=>!empty($_SERVER['HTTPS'])
  ]);
  session_start();
}

include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';

// ⬇️ login/CSRF helpers
$is_partner = !empty($_SESSION['partner_id']);
if(empty($_SESSION['csrf'])){
  $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'] ?? '';

// Helpers
function euro($v){ return number_format((float)$v,2,',','.'); }
function h($s){ return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8'); }

// Input
$items = [];
$productId = isset($_GET['product']) ? (int)$_GET['product'] : null;

// ⛔ Early guard: blokkeer directe toegang tot inkoop id 38 & 39 voor niet-partners
if(!$is_partner && $productId && in_array($productId,[38,39],true)){
  if($stmt = $mysqli_medewerkers->prepare("SELECT 1 FROM inkoop_products WHERE id=? AND sub_category='uitvaart' LIMIT 1")){
    $stmt->bind_param('i',$productId);
    $stmt->execute();
    $stmt->store_result();
    $isInkoopTarget = $stmt->num_rows > 0;
    $stmt->close();

    if($isInkoopTarget){
      // Zet statuscode vóór er output is geweest
      http_response_code(403);

      // Optioneel: toon een nette foutpagina mét header/footer
      include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';
      echo '<main class="assortiment denied" style="max-width:900px;margin:50px auto;padding:40px;text-align:center;background:#fafafa;border:1px solid #e6e6e6;border-radius:12px;box-shadow:0 2px 6px rgba(0,0,0,.05)">
          <div style="font-size:48px;margin-bottom:16px">🔒</div>
          <h1 style="margin:0 0 12px;color:#2d2d2d">Alleen voor uitvaartpartners</h1>
          <p style="font-size:1.1rem;line-height:1.6;color:#555;max-width:700px;margin:0 auto 20px">
            Dit product is uitsluitend bedoeld voor gebruik door onze uitvaartpartners.<br>
            Het is niet beschikbaar voor particuliere verkoop.
          </p>
          <p>
            <a class="btn" href="/pages/contacteer-uitvaartdienst.php">Contacteer een uitvaartdienst</a>
            <a class="btn ghost" style="margin-left:8px" href="/pages/assortiment.php">← Terug naar overzicht</a>
          </p>
        </main>';
      include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php';
      exit;
    }
  }
}

// Filters voor detail of lijst
$filterIdE = $productId ? "AND e.id=".$productId : "";
$filterIdK = $productId ? "AND k.id=".$productId : "";
$filterIdI = $productId ? "AND i.id=".$productId : "";

// UNION: epoxy + kaarsen + inkoop (alleen uitvaart)
$sql = "
(
  SELECT 
    e.id,
    e.title AS name,
    e.product_description AS description,
    e.total_product_price AS price,
    e.margin AS margin,  
    e.product_image AS image,
    'epoxy' AS category,
    pa.gram AS gram_per_stuk,
    e.sku AS sku
  FROM epoxy_products e
  LEFT JOIN product_as pa ON pa.product_id=e.id
  WHERE e.sub_category='uitvaart' $filterIdE
)
UNION ALL
(
  SELECT 
    k.id,
    k.title AS name,
    k.product_description AS description,
    k.total_product_price AS price,
    k.margin AS margin,  
    k.product_image AS image,
    'kaarsen' AS category,
    NULL AS gram_per_stuk,
    k.sku AS sku
  FROM kaarsen_products k
  WHERE k.sub_category='uitvaart' $filterIdK
)
UNION ALL
(
  SELECT 
    i.id,
    i.title AS name,
    i.product_description AS description,
    i.total_product_price AS price,
    i.margin AS margin,
    i.product_image AS image,
    'inkoop' AS category,
    NULL AS gram_per_stuk,
    i.sku AS sku
  FROM inkoop_products i
  WHERE i.sub_category='uitvaart' $filterIdI
)
ORDER BY name ASC
";

// Query uitvoeren vóór header-output
if(!$r = $mysqli_medewerkers->query($sql)){
  // Netjes 500 zetten en fout tonen
  http_response_code(500);
  include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';
  echo '<main class="assortiment" style="max-width:900px;margin:auto;padding:24px">
          <h1>Er ging iets mis</h1>
          <p>De productinformatie kon niet worden opgehaald. Probeer het later opnieuw.</p>
          <p><a class="btn ghost" href="/pages/assortiment.php">← Terug naar overzicht</a></p>
        </main>';
  include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php';
  exit;
}

while($row = $r->fetch_assoc()){
  $row['margin'] = isset($row['margin']) ? (float)$row['margin'] : 0.0;
  $items[] = $row;
}
$r->free();

/** ---- Verrijk met medewerkers.product_webshop op SKU ---- */
if(!empty($items)){
  $skus = array_values(array_unique(array_filter(array_map(fn($x)=>$x['sku']??'', $items))));
  if($skus){
    $esc = array_map([$mysqli_medewerkers,'real_escape_string'],$skus);
    $in  = "'".implode("','",$esc)."'";
    $q   = "SELECT sku, small_desc, large_desc, lenghte, width, breedte, wight 
            FROM product_webshop 
            WHERE sku IN ($in)";
    if($rs = $mysqli_medewerkers->query($q)){
      $bySku = [];
      while($row = $rs->fetch_assoc()){
        $bySku[$row['sku']] = $row;
      }
      $rs->free();

      foreach($items as &$it){
        $sku = $it['sku'] ?? '';
        if($sku && isset($bySku[$sku])){
          $it['small_desc'] = $bySku[$sku]['small_desc'] ?? '';
          $it['large_desc'] = $bySku[$sku]['large_desc'] ?? '';
          $it['lenghte']    = $bySku[$sku]['lenghte'] ?? '';
          $it['width']      = $bySku[$sku]['width'] ?? '';
          $it['breedte']    = $bySku[$sku]['breedte'] ?? '';
          $it['wight']      = $bySku[$sku]['wight'] ?? '';
        }
      }
      unset($it);
    }
  }
}

// ✅ Vanaf hier is alles oké: nu pas de header tonen en verder renderen
include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';

?>
<main class="assortiment">
<?php if($productId&&isset($items[0])): $p=$items[0];
  $img="https://medewerkers.windelsgreen-decoresin.com/winkel/product_img/".h($p['image']);
  $name=h($p['name']);
  $allowed = '<b><i><strong><em><p><br><ul><li>';
  $small_desc = nl2br(strip_tags($p['small_desc'] ?? '', $allowed));
  $large_desc = nl2br(strip_tags($p['large_desc'] ?? '', $allowed));  
  $hasGram=($p['category']==='epoxy'&&$p['gram_per_stuk']!==''&&$p['gram_per_stuk']!==null);

  // prijzen
    // prijzen
    $basePrice        = (float)$p['price'];
    $priceParticulier = number_format($basePrice,2,',','.');
    $btw = isset($_SESSION['btw_nummer']) ? strtoupper($_SESSION['btw_nummer']) : '';
    $isBlocked = in_array((int)$p['id'],[34,35,36,38,39],true);
  
    // partnerprijs op basis van margin (markup op kostprijs)
    $marginPercent = isset($p['margin']) ? (float)$p['margin'] : 0.0;
    $pricePartner  = null;
  
    if($is_partner && !$isBlocked){
      // consumentenprijs incl. 21% → excl. btw
      $priceExVat = $basePrice / 1.21;
  
      $markup = $marginPercent / 100;
      if($markup <= -1){
        // veiligheidsfallback: neem gewoon excl. prijs
        $partnerPriceExVat = $priceExVat;
      }else{
        // kostprijs uitrekenen o.b.v. markup (bv. 181.3%)
        $productCost  = $priceExVat / (1 + $markup);
        $marginAmount = $priceExVat - $productCost;
  
        // helft marge terug naar partner
        $halfMargin        = $marginAmount /$percentageuitvaart;
        $partnerPriceExVat = $productCost + $halfMargin;
      }
  
      // NL: excl. btw, BE/overige: incl. 21% btw
      if(str_starts_with($btw,'NL')){
        $partnerRaw = $partnerPriceExVat;
      }else{
        $partnerRaw = $partnerPriceExVat * 1.21;
      }
  
      $pricePartner = number_format($partnerRaw,2,',','.');
    }
  

  // mapping naar cart product_type
  $cart_type = ($p['category']==='kaarsen') ? 'kaars' : (($p['category']==='epoxy') ? 'epoxy' : 'inkoop');
  $unit_price_raw = number_format((float)$p['price'],2,'.',''); // hidden veld (blijft basisprijs)


  // ⬇️ NIEUW: varianten ophalen uit medewerkers.product_variant op basis van SKU
  $variants=[];
  $skuEsc=$mysqli_medewerkers->real_escape_string($p['sku'] ?? '');
  if($skuEsc!==''){
    if($vr=$mysqli_medewerkers->query("SELECT type, option_value FROM product_variant WHERE sku='$skuEsc'")){
      while($row=$vr->fetch_assoc()){
        $variants[]=['type'=>strtolower(trim((string)$row['type'])),'option_value'=>trim((string)$row['option_value'])];
      }
      $vr->free();
    }
  }
?>
  <nav class="crumbs"><a href="/pages/assortiment.php">Assortiment</a><span>›</span><strong><?=$name?></strong></nav>
  <article class="detail">
    <div class="detail-media">
      <button class="zoom" type="button" aria-label="Vergroot afbeelding" data-full="<?=$img?>">
        <img id="mainProductImg" src="<?=$img?>" alt="<?=$name?>" loading="lazy" decoding="async" width="900" height="900">
      </button>
      <!-- thumbs etc. (ongewijzigd) -->
    </div>

    <div class="detail-info">
      <h1 class="detail-title"><?=$name?></h1>
      <div class="detail-meta">
        <?php if($hasGram): ?><span class="badge">Crematie as: <?=round((float)$p['gram_per_stuk'])?> g/stuk</span><?php endif; ?>
          <?php if($is_partner && !$isBlocked): ?>
            <span class="price">
              <strong>
              Particulier <sup>€</sup>
              <span class="price-particulier"><?php echo $priceParticulier; ?></span>
              <br>
              Partner <sup>€</sup>
              <span class="price-partner"><?php echo $pricePartner; ?></span>
              </strong>  
            </span>
          <?php else: ?>
            <span class="price">
              <sup>€</sup><?= $priceParticulier ?>
            </span>
          <?php endif; ?>
      </div>

      <!-- KORT OVERZICHT: small_desc -->
      <div class="detail-desc"><?=$small_desc?></div>

      <div class="detail-cta">
        <?php if($is_partner): ?>
          <form method="post" action="/pages/account/orders/cart_actions.php" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_type" value="<?= h($cart_type) ?>">
            <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
            <input type="hidden" name="name" value="<?= $name ?>">
            <input type="hidden" name="unit_price" value="<?= $unit_price_raw ?>">

            <!-- ⬇️ NIEUW: Variant inputs op basis van product_variant -->
            <?php if(!empty($variants)): ?>
              <fieldset class="variant">
                <legend>Variant</legend>
                <?php foreach($variants as $vx=>$var):
                  $type=$var['type'];
                  $opts=$var['option_value'];
                  if($type==='color'): ?>
                    <label class="variant-row">
                      <span class="label">Kleur</span>
                      <input type="color" id="colorInput" name="variant[color]" value="#000000" data-require-change="0" data-default="">   <!-- standaardkleur om tegen te vergelijken -->
                    </label>
                  <?php elseif($type==='checkbox'):
                    $values=array_values(array_filter(array_map('trim', explode(',',$opts)),fn($v)=>$v!==''));
                  ?>
                    <div class="variant-row">
                      <span class="label">Opties</span>
                      <div class="checks">
                        <?php foreach($values as $i=>$val):
                          $id='opt'.$vx.'_'.$i; ?>
                          <label class="check" for="<?=$id?>">
                            <input type="checkbox" id="<?=$id?>" name="variant_options[]" value="<?=h($val)?>"> <?=h($val)?>
                          </label>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </fieldset>
            <?php endif; ?>

            <div class="qty-wrap">
              <!-- qty -->
              <input type="number" id="qtyInput" name="qty" value="1" min="1" max="50"
                    class="qty-input"
                    style="width:72px;padding:6px;border:1px solid #c9c9c9;border-radius:6px">

              <!-- foutmelding -->
              <div id="qtyError" class="inline-error" aria-live="polite" style="display:none">
                <div class="msg-row">
                  <span class="icon">⚠️</span>
                  <span class="msg"></span>
                </div>
              </div>

              <!-- knoppen naast elkaar -->
              <div class="btn-row">
                <button class="btn" type="submit"
                        style="background:#006c4d;color:#fff;border-radius:10px;padding:.8rem 1.2rem;border:0;cursor:pointer">
                  In winkelwagen
                </button>
                <a href="/pages/account/orders/cart.php" class="btn ghost">Naar winkelwagen</a>
              </div>
            </div>
          </form>
        <?php else: ?>
          <form method="post" action="/pages/orders/cart_actions.php" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_type" value="<?= h($cart_type) ?>">
            <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
            <input type="hidden" name="name" value="<?= $name ?>">
            <input type="hidden" name="unit_price" value="<?= $unit_price_raw ?>">

            <!-- ⬇️ NIEUW: Variant inputs op basis van product_variant -->
            <?php if(!empty($variants)): ?>
              <fieldset class="variant">
                <legend>Variant</legend>
                <?php foreach($variants as $vx=>$var):
                  $type=$var['type'];
                  $opts=$var['option_value'];
                  if($type==='color'): ?>
                    <label class="variant-row">
                      <span class="label">Kleur</span>
                      <input type="color" id="colorInput" name="variant[color]" value="#000000" data-require-change="0" data-default="">   <!-- standaardkleur om tegen te vergelijken -->
                    </label>
                  <?php elseif($type==='checkbox'):
                    $values=array_values(array_filter(array_map('trim', explode(',',$opts)),fn($v)=>$v!==''));
                  ?>
                    <div class="variant-row">
                      <span class="label">Opties</span>
                      <div class="checks">
                        <?php foreach($values as $i=>$val):
                          $id='opt'.$vx.'_'.$i; ?>
                          <label class="check" for="<?=$id?>">
                            <input type="checkbox" id="<?=$id?>" name="variant_options[]" value="<?=h($val)?>"> <?=h($val)?>
                          </label>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              </fieldset>
            <?php endif; ?>

            <div class="qty-wrap">
              <!-- qty -->
              <input type="number" id="qtyInput" name="qty" value="1" min="1" max="50"
                    class="qty-input"
                    style="width:72px;padding:6px;border:1px solid #c9c9c9;border-radius:6px">

              <!-- foutmelding -->
              <div id="qtyError" class="inline-error" aria-live="polite" style="display:none">
                <div class="msg-row">
                  <span class="icon">⚠️</span>
                  <span class="msg"></span>
                </div>
              </div>

              <!-- knoppen naast elkaar -->
              <div class="btn-row">
                <button class="btn" type="submit"
                        style="background:#006c4d;color:#fff;border-radius:10px;padding:.8rem 1.2rem;border:0;cursor:pointer">
                  In winkelwagen
                </button>
                <a href="/pages/account/orders/cart.php" class="btn ghost">Naar winkelwagen</a>
              </div>
            </div>
          </form>
        <?php endif; ?>
        <?php if(!$is_partner): ?>
          <p>Of</p><br>
          <p style="margin-top:-0.5rem;">
            <a href="/pages/contacteer-uitvaartdienst.php" class="btn">Contacteer een uitvaartdienst</a>
            <a href="/pages/assortiment.php" class="btn ghost">← Terug naar overzicht</a>
          </p>
        <?php else: ?>
          <p>
          <a href="/pages/assortiment.php" class="btn ghost">← Terug naar overzicht</a>
        </p>
        <?php endif; ?>
      </div>

      <!-- Tabs voor uitgebreide info -->
      <div class="detail-tabs">
        <div class="tabs-nav" role="tablist">
          <button class="tab-btn active" data-tab="tab-desc" role="tab" aria-selected="true">Beschrijving</button>
          <button class="tab-btn" data-tab="tab-specs" role="tab" aria-selected="false">Specificaties</button>
        </div>
        <div class="tabs-panels">
          <section id="tab-desc" class="tab-panel active" role="tabpanel">
            <?php if(trim(strip_tags($large_desc))!==''): ?>
              <?=$large_desc?>
            <?php else: ?>
              <p>Geen extra beschrijving beschikbaar.</p>
            <?php endif; ?>
          </section>
          <section id="tab-specs" class="tab-panel" role="tabpanel">
            <table class="specs">
              <tbody>
                <tr><th>Hoogte</th><td><?= !empty($p['lenghte']) ? h($p['lenghte'] ?? '').' cm' : 'Geen Gegevens beschikbaar'; ?></td></tr>
                <tr><th>Lengte</th><td><?= !empty($p['width']) ? h($p['width'] ?? '').' cm' : 'Geen Gegevens beschikbaar';  ?></td></tr>
                <tr><th>Dikte</th><td><?= !empty($p['breedte']) ? h($p['breedte'] ?? '').' cm' : 'Geen Gegevens beschikbaar';  ?></td></tr>
                <tr><th>Gewicht</th><td><?= !empty($p['wight']) ? h($p['wight'] ?? '').' kg' : 'Geen Gegevens beschikbaar';  ?></td></tr>
              </tbody>
            </table>
          </section>
        </div>
      </div>

      <ul class="detail-notes">
        <li>Gemaakt met zorg en respect, handgemaakt in ons atelier.</li>
        <li>Discreet en veilig aanleveren van as via je uitvaartverzorger.</li>
        <li>Levertijd in overleg na ontvangst van de as.</li>
      </ul>
    </div>
  </article>
<?php else: ?>
  <!-- lijst-weergave -->
  <header class="list-head">
    <h1>Ons assortiment</h1>
    <p>Bekijk onze selectie en contacteer je plaatselijke uitvaartdienst om een bestelling door te geven.</p>
  </header>
  <section class="grid">
    <?php foreach($items as $it):
      $img="https://medewerkers.windelsgreen-decoresin.com/winkel/product_img/".h($it['image']);
      $name=h($it['name']);
      $hasGram=($it['category']==='epoxy'&&$it['gram_per_stuk']!==null&&$it['gram_per_stuk']!=='');
      $small=h($it['small_desc'] ?? '');
      $snippet = $small!=='' ? $small : ($it['description'] ?? '');
      $snippet = nl2br(h(mb_strimwidth((string)$snippet,0,220,'…','UTF-8')));
    ?>
    <article class="card">
      <button class="card-media zoom" type="button" aria-label="Vergroot afbeelding" data-full="<?=$img?>"><img src="<?=$img?>" alt="<?=$name?>" loading="lazy" decoding="async" width="600" height="600"></button>
      <div class="card-body">
        <h2 class="card-title"><?=$name?></h2>
        <div class="card-line">
          <?php if($hasGram): ?><span class="badge">As: <?=round((float)$it['gram_per_stuk'])?> g/stuk</span><?php endif; ?>
            <?php
            $baseListPrice   = (float)$it['price'];
            $listParticulier = number_format($baseListPrice,2,',','.');
            $btw = isset($_SESSION['btw_nummer']) ? strtoupper($_SESSION['btw_nummer']) : '';
            $isBlockedList = in_array((int)$it['id'],[34,35,36,38,39],true);
      
            // partnerprijs op basis van margin
            $marginPercentList = isset($it['margin']) ? (float)$it['margin'] : 0.0;
            $listPartner       = null;
      
            if($is_partner && !$isBlockedList){
              $priceExVat = $baseListPrice / 1.21;
              $markup = $marginPercentList / 100;
      
              if($markup <= -1){
                $partnerPriceExVatList = $priceExVat;
              }else{
                $productCostList  = $priceExVat / (1 + $markup);
                $marginAmountList = $priceExVat - $productCostList;
                $halfMarginList   = $marginAmountList /$percentageuitvaart;
                $partnerPriceExVatList = $productCostList + $halfMarginList;
              }
      
              if(str_starts_with($btw,'NL')){
                $partnerRawList = $partnerPriceExVatList;
              }else{
                $partnerRawList = $partnerPriceExVatList * 1.21;
              }
      
              $listPartner = number_format($partnerRawList,2,',','.');
            }

          ?>
          <?php if($is_partner && !$isBlocked): ?>
            <span class="price">
              Particulier <sup>€</sup>
              <span class="price-particulier"><?php echo $priceParticulier; ?></span>
              <br>
              Partner <sup>€</sup>
              <span class="price-partner"><?php echo $pricePartner; ?></span>          
            </span>
          <?php else: ?>
            <span class="price">
              <sup>€</sup><?= $listParticulier ?>
            </span>
          <?php endif; ?>
        </div>
        <p class="card-desc"><?=$snippet?></p>
        <div class="card-cta"><a class="btn small" href="?product=<?=(int)$it['id']?>">Meer info</a></div>
      </div>
    </article>
    <?php endforeach;?>
  </section>
<?php endif;?>

<div id="customAlert" class="custom-alert" style="display:none">
  <div class="custom-alert-content">
    <p id="customAlertMsg"></p>
    <button type="button" id="customAlertClose">OK</button>
  </div>
</div>

</main>
<div id="imgOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);justify-content:center;align-items:center;z-index:1000">
  <img id="overlayImg" src="" alt="Vergrote afbeelding" style="max-width:90%;max-height:90%;border-radius:12px;box-shadow:0 0 15px rgba(0,0,0,.5)">
</div>

<style>
  .price-partner{display:none}
html[data-price-mode="partner"] .price-particulier{display:inline}
html[data-price-mode="partner"] .price-partner{display:inline}

/* Container */
.assortiment{max-width:1100px;margin:auto;padding:24px}
/* Breadcrumbs */
.crumbs{display:flex;gap:8px;align-items:center;margin:4px 0 18px;color:#6b6b6b;font-size:.95rem}
.crumbs a{color:#006c4d;text-decoration:none}
.crumbs a:hover{text-decoration:underline}
/* Detail layout */
.detail{display:grid;grid-template-columns:520px 1fr;gap:28px;align-items:start}
.detail-media{position:sticky;top:24px}
/* Sticky alleen op desktop */
@media(min-width:1081px){.detail-media{position:sticky;top:24px}}
@media(max-width:1080px){.detail-media{position:static;top:auto}}
@media(max-width:720px){.detail-media{position:static;top:auto}}
.zoom{border:0;background:transparent;padding:0;cursor:zoom-in}
.detail-media img{width:100%;height:auto;border-radius:14px}
.detail-info{min-width:0}
.detail-title{margin:0 0 8px;font-size:1.9rem;color:#2e2e2e}
.detail-meta{display:flex;flex-wrap:wrap;gap:12px;align-items:center;margin:10px 0 16px}
.badge{display:inline-block;background:#eef7f3;border:2px solid #cbe6d9;color:#004d36;border-radius:10px;padding:.45rem .7rem;font-weight:700;font-size:1.05rem}
.price{margin-left:auto;font-weight:800;font-size:2rem;color:#006c4d;line-height:1}
.detail-desc{font-size:1.05rem;line-height:1.7;color:#444}
.detail-cta{display:flex;flex-wrap:wrap;gap:10px;margin:18px 0}
.btn{background:#006c4d;color:#fff;border-radius:10px;padding:.8rem 1.2rem;text-decoration:none;font-weight:700;display:inline-block;transition:.2s}
.btn:hover{background:#004d36}
.btn.ghost{background:#e6e6e6;color:#2e2e2e}
.btn.ghost:hover{background:#cfcfcf}
.detail-notes{margin:12px 0 0;color:#555;line-height:1.6;padding-left:18px}
.detail-notes li{margin:.2rem 0}
/* List layout */
.list-head h1{margin:0 0 6px;font-size:2rem;color:#2e2e2e}
.list-head p{margin:0;color:#444}
.grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;margin:20px 0 0}
.card{border:1px solid #e6e6e6;border-radius:14px;background:#fafafa;box-shadow:0 2px 6px rgba(0,0,0,.04);overflow:hidden;display:flex;flex-direction:column}
.card-media img{width:100%;height:280px;object-fit:cover;display:block}
.card-body{padding:14px}
.card-title{margin:0 0 6px;font-size:1.2rem;color:#2d2d2d}
.card-line{display:flex;gap:10px;align-items:center;margin:4px 0 10px}
.card-line .badge{font-size:.95rem;padding:.35rem .55rem;border-radius:8px;border-width:1.5px}
.card-line .price{margin-left:auto;font-size:1.4rem}
.card-desc{margin:0 0 12px;color:#444;line-height:1.55}
.card-cta{display:flex;justify-content:flex-end}
.btn.small{padding:.6rem .9rem;border-radius:8px;font-weight:700}
/* Overlay */
#imgOverlay{display:none}
@media(max-width:1080px){.detail{grid-template-columns:1fr}}
@media(max-width:720px){
  .grid{grid-template-columns:repeat(2,minmax(0,1fr))}
  .detail-title{font-size:1.6rem}
  .price{font-size:1.7rem}
}
@media(max-width:520px){
  .grid{grid-template-columns:1fr}
  .detail-meta{gap:10px}
  .price{font-size:1.6rem}
}
.thumbs{display:flex;gap:10px;margin-top:12px;flex-wrap:wrap}
.thumbnail{border:0;background:transparent;padding:0;cursor:pointer}
.thumbnail img{width:88px;height:88px;object-fit:cover;border-radius:10px;border:2px solid #e6e6e6;transition:transform .15s ease, border-color .15s ease}
.thumbnail:hover img{transform:scale(1.03);border-color:#cfe5db}
.thumbnail.active img{border-color:#006c4d}
.detail-tabs{margin:18px 0}
.tabs-nav{display:flex;gap:8px;border-bottom:1px solid #e6e6e6}
.tab-btn{background:#f4f6f5;border:1px solid #e2e2e2;border-bottom:none;padding:.55rem .9rem;border-top-left-radius:10px;border-top-right-radius:10px;cursor:pointer;font-weight:700;color:#2e2e2e}
.tab-btn.active{background:#fff;border-color:#d8d8d8 #d8d8d8 #fff}
.tabs-panels{border:1px solid #d8d8d8;border-radius:0 10px 10px 10px;padding:14px;background:#fff}
.tab-panel{display:none}
.tab-panel.active{display:block}
.specs{width:100%;border-collapse:collapse}
.specs th,.specs td{padding:.45rem .6rem;border-bottom:1px solid #eee;text-align:left}
.specs th{width:160px;color:#2a2a2a}
.variant{border:1px solid #e6e6e6;border-radius:10px;padding:10px;margin:6px 0 10px;background:#fafafa}
.variant legend{font-weight:700;color:#2e2e2e}
.variant .variant-row{display:flex;align-items:center;gap:10px;margin:6px 0}
.variant .label{min-width:90px;color:#333}
.variant .checks{display:flex;flex-wrap:wrap;gap:10px}
.variant .check{display:inline-flex;align-items:center;gap:6px}
.field-error{outline:2px solid #e53935;outline-offset:2px;border-radius:6px}
.custom-alert{
  position:fixed;
  top:0;left:0;right:0;bottom:0;
  background:rgba(0,0,0,.5);
  display:flex;
  justify-content:center;
  align-items:center;
  z-index:2000;
}
.custom-alert-content{
  background:#006c4d;
  color:#fff;
  padding:20px 30px;
  border-radius:12px;
  box-shadow:0 4px 15px rgba(0,0,0,.4);
  max-width:400px;
  text-align:center;
}
.custom-alert-content p{
  margin:0 0 12px;
  font-size:1.1rem;
}
.custom-alert-content button{
  background:#fff;
  color:#006c4d;
  border:0;
  padding:.6rem 1.2rem;
  border-radius:8px;
  font-weight:700;
  cursor:pointer;
}
.custom-alert-content button:hover{
  background:#e6e6e6;
}
.qty-wrap{
  display:flex;
  flex-direction:column;
  align-items:flex-start;
  gap:6px;
}
.inline-error{
  display:flex;
  align-items:center;
  gap:6px;
  color:#e53935;
  font-size:.9rem;
}
.inline-error .icon{font-size:1rem}
.btn-row{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
}



</style>
<script>
(function(){
  const qty=document.getElementById('qtyInput');
  const err=document.getElementById('qtyError');
  const msgSpan=err?err.querySelector('.msg'):null;
  const form=document.querySelector('.detail-cta form');
  if(!qty||!err||!msgSpan||!form) return;

  const max=parseInt(qty.getAttribute('max')||'50',10);
  const min=parseInt(qty.getAttribute('min')||'1',10);

  function showError(msg){
    msgSpan.textContent=msg;
    err.style.display='flex';
    qty.classList.add('field-error');
    qty.setAttribute('aria-invalid','true');
  }
  function clearError(){
    msgSpan.textContent='';
    err.style.display='none';
    qty.classList.remove('field-error');
    qty.removeAttribute('aria-invalid');
  }

  function enforceBounds(){
    let v=qty.value?parseInt(qty.value,10):NaN;
    if(isNaN(v)){ v=min; }
    if(v>max){
      qty.value=max;
      showError(`\n\nDe maximale bestelcapaciteit is ${max} stuks.\n\n`);
    }else if(v<min){
      qty.value=min;
      showError(`Minimaal ${min} stuk.`);
    }else{
      clearError();
    }
  }

  qty.addEventListener('input',enforceBounds,{passive:true});
  qty.addEventListener('blur',enforceBounds,{passive:true});
  form.addEventListener('submit',function(e){
    enforceBounds();
    if(qty.classList.contains('field-error')) e.preventDefault();
  });
})();
</script>


<script>
(function(){
  const form=document.querySelector('.detail-cta form');
  if(!form) return;

  // COLOR veld selecteren (1 stuk verwacht)
  const colorInput=form.querySelector('input[type="color"][name="variant[color]"]');

  // Helper: toon custom alert (deze heb je al)
  function showCustomAlert(msg,autocloseMs){
    const box=document.getElementById('customAlert'); if(!box) return;
    document.getElementById('customAlertMsg').textContent=msg;
    box.style.display='flex';
    if(autocloseMs){ setTimeout(()=>{ box.style.display='none'; },autocloseMs); }
  }

  // Helper: invalid markering
  function markInvalid(el){ if(!el) return; el.classList.add('field-error'); el.setAttribute('aria-invalid','true'); }
  function clearInvalid(el){ if(!el) return; el.classList.remove('field-error'); el.removeAttribute('aria-invalid'); }

  // Submit guard uitbreiden met color-validatie
  form.addEventListener('submit',function(e){
    let hasError=false;

    // 1) Checkbox-regel (bestond al)
    const boxes=form.querySelectorAll('input[name="variant_options[]"]');
    if(boxes.length && ![...boxes].some(b=>b.checked)){
      hasError=true;
      showCustomAlert('Kies minstens één optie voor dit product.');
    }

    //* 2) Color-regel
    if(colorInput){
      clearInvalid(colorInput);
      const val=(colorInput.value||'').trim();
      const requireChange=(colorInput.dataset.requireChange==='1');
      const def=(colorInput.dataset.default||'').trim();

      // a) moet een waarde hebben
      if(!val){
        if(!hasError) showCustomAlert('Kies een kleur voor dit product.');
        markInvalid(colorInput);
        hasError=true;
      }
      // b) indien verplicht: mag niet gelijk zijn aan default
      else if(requireChange && def && val.toLowerCase()===def.toLowerCase()){
        if(!hasError) showCustomAlert('Wijzig de kleur naar je gewenste keuze.');
        markInvalid(colorInput);
        hasError=true;
      }
    }

    if(hasError){ e.preventDefault(); }
  });

  // Wis errorstate zodra gebruiker een geldige kleur kiest
  if(colorInput){
    colorInput.addEventListener('input',()=>{ clearInvalid(colorInput); });
    colorInput.addEventListener('change',()=>{ clearInvalid(colorInput); });
  }
})();
</script>

<script>
(function(){
  // qty = aantal aangevinkte checkboxen (min 1)
  const qty=document.getElementById('qtyInput');
  const form=document.querySelector('.detail-cta form');
  if(!form||!qty) return;

  const boxes=form.querySelectorAll('input[name="variant_options[]"]');
  function updateQtyFromChecks(){
    const count=[...boxes].filter(b=>b.checked).length;
    qty.value=Math.max(1,count);
  }
  if(boxes.length){ boxes.forEach(b=>b.addEventListener('change',updateQtyFromChecks,{passive:true})); updateQtyFromChecks(); }

  // Custom alert
  function showCustomAlert(msg,autocloseMs){
    const box=document.getElementById('customAlert'); if(!box) return;
    document.getElementById('customAlertMsg').textContent=msg;
    box.style.display='flex';
    if(autocloseMs){ setTimeout(()=>{ box.style.display='none'; },autocloseMs); }
  }
  const alertClose=document.getElementById('customAlertClose');
  if(alertClose){ alertClose.addEventListener('click',()=>{ document.getElementById('customAlert').style.display='none'; }); }

  // Submit guard: minstens 1 checkbox indien er checkbox-varianten zijn
  form.addEventListener('submit',function(e){
    if(boxes.length && ![...boxes].some(b=>b.checked)){
      e.preventDefault();
      showCustomAlert('Kies minstens één optie voor dit product.', 0); // auto-close? zet bv. 3000
    }
  });
})();
</script>

<script>
function openOverlay(src){const img=document.getElementById('overlayImg');img.src=src;document.getElementById('imgOverlay').style.display='flex'}
document.querySelectorAll('.zoom').forEach(el=>el.addEventListener('click',()=>openOverlay(el.dataset.full)));
document.getElementById('imgOverlay').addEventListener('click',()=>{document.getElementById('imgOverlay').style.display='none'});

(function(){
  const mainImg   = document.getElementById('mainProductImg');
  const zoomBtn   = document.querySelector('.detail-media .zoom');
  const thumbs    = document.querySelectorAll('.detail-media .thumbnail');

  if(!mainImg || !zoomBtn || thumbs.length===0) return;

  thumbs.forEach(btn=>{
    btn.addEventListener('click',()=>{
      // active UI
      document.querySelectorAll('.thumbnail').forEach(b=>b.classList.remove('active'));
      btn.classList.add('active');

      // wissel src en zoom data-full
      const large = btn.getAttribute('data-large');
      mainImg.src = large;
      zoomBtn.dataset.full = large;
    }, {passive:true});
  });
})();
(function(){
  const nav=document.querySelector('.detail-tabs .tabs-nav'); if(!nav) return;
  const btns=[...nav.querySelectorAll('.tab-btn')];
  const panels=[...document.querySelectorAll('.detail-tabs .tab-panel')];
  btns.forEach(b=>b.addEventListener('click',()=>{
    btns.forEach(x=>{x.classList.remove('active');x.setAttribute('aria-selected','false');});
    panels.forEach(p=>p.classList.remove('active'));
    b.classList.add('active'); b.setAttribute('aria-selected','true');
    const id=b.dataset.tab; const p=document.getElementById(id); if(p) p.classList.add('active');
  },{passive:true}));
})();
</script>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
