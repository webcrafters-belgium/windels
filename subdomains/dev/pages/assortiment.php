<?php
include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';

if(session_status()!==PHP_SESSION_ACTIVE){
  session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','secure'=>isset($_SERVER['HTTPS'])]);
  session_start();
}
$isPartner = !empty($_SESSION['partner_id']);
// --- Helpers ---
function q($s){return htmlspecialchars($s,ENT_QUOTES,'UTF-8');}
function field_or_null(array $row, array $keys){
  foreach($keys as $k){
      if(isset($row[$k]) && $row[$k] !== '' && $row[$k] !== null){
          return $row[$k];
      }
  }
  return null;
}

function buildUrl($merge=[]){
  $base=strtok($_SERVER['REQUEST_URI'],'?');
  $qs=array_merge($_GET,$merge);
  if(isset($qs['page']) && (int)$qs['page']<=1) unset($qs['page']);
  $query=http_build_query($qs);
  return $base.($query?'?'.$query:'');
}
function tableHasColumn(mysqli $db, string $table, string $col): bool{
  $colEsc=$db->real_escape_string($col);
  $tableEsc=str_replace('`','', $table);
  $sql="SHOW COLUMNS FROM `$tableEsc` LIKE '$colEsc'";
  if(!$res=$db->query($sql)) return false;
  $ok=$res->num_rows>0;
  $res->close();
  return $ok;
}

// --- Parameters ---
$perPage=12;
$page=isset($_GET['page'])?max(1,(int)$_GET['page']):1;
$search=isset($_GET['q'])?trim($_GET['q']):'';
$activeCat=isset($_GET['cat'])?strtolower(trim($_GET['cat'])):'';
$ash=isset($_GET['as'])?strtolower(trim($_GET['as'])):'';
if(!in_array($ash,['','ja','nee'],true)) $ash='';
// --- Sessiestatus ---
$isPartner = isset($_SESSION) && !empty($_SESSION['partner_id']);

// --- Categorie mapping (whitelist) ---
$validCats=['decoratie'=>'epoxy','kaarsen'=>'kaarsen','verpakking'=>'inkoop'];
if(!array_key_exists($activeCat,$validCats)) $activeCat='';

// --- product_webshop schema ---
$pwHasSku       = tableHasColumn($mysqli_medewerkers,'product_webshop','sku');
$pwHasSmallDesc = tableHasColumn($mysqli_medewerkers,'product_webshop','small_desc');

// Helper: JOIN + DESC per bron op SKU, met geforceerde collation in ON
function pwJoinAndDescBySku(mysqli $db, string $table, string $alias, bool $pwHasSku, bool $pwHasSmallDesc){
  $hasSkuInProduct = tableHasColumn($db,$table,'sku');
  $join = '';
  // Alleen joinen als beide kanten een SKU hebben
  if($pwHasSku && $hasSkuInProduct){
    // Forceer collatie aan beide kanten om mix-conflict te vermijden
    $join = "LEFT JOIN product_webshop w
             ON (CONVERT(w.sku USING utf8mb4) COLLATE utf8mb4_general_ci
                 = CONVERT({$alias}.sku USING utf8mb4) COLLATE utf8mb4_general_ci)";
  }
  // Beschrijving: ENKEL small_desc (nooit fallback). Zo ja: cast + collate, zo nee: lege string.
  if($pwHasSmallDesc && $join!==''){
    $descInner = "w.small_desc";
  }else{
    $descInner = "''"; // leeg als er geen join/small_desc is
  }
  $desc = "CAST(COALESCE($descInner,'') AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci";
  return [$join,$desc];
}

list($joinEpoxy,$descEpoxy)     = pwJoinAndDescBySku($mysqli_medewerkers,'epoxy_products','p',$pwHasSku,$pwHasSmallDesc);
list($joinKaarsen,$descKaarsen) = pwJoinAndDescBySku($mysqli_medewerkers,'kaarsen_products','p',$pwHasSku,$pwHasSmallDesc);
list($joinInkoop,$descInkoop)   = pwJoinAndDescBySku($mysqli_medewerkers,'inkoop_products','p',$pwHasSku,$pwHasSmallDesc);

// Ook title naar consistente collation casten
$titleEpoxy   = "CAST(p.title AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci";
$titleKaarsen = "CAST(p.title AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci";
$titleInkoop  = "CAST(p.title AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci";

// --- Basis UNION met SKU-join + consistente collations ---
$unionBase="
    SELECT p.id,
           $titleEpoxy   AS name,
           $descEpoxy    AS description,
           p.total_product_price AS price,
           p.margin AS margin,
           p.product_image AS image,
           'decoratie' AS category,
           'epoxy' AS src
    FROM epoxy_products p
    $joinEpoxy
    WHERE p.sub_category='uitvaart'
    UNION ALL
    SELECT p.id,
           $titleKaarsen AS name,
           $descKaarsen  AS description,
           p.total_product_price AS price,
           p.margin AS margin,
           p.product_image AS image,
           'kaarsen' AS category,
           'kaarsen' AS src
    FROM kaarsen_products p
    $joinKaarsen
    WHERE p.sub_category='uitvaart'
    UNION ALL
    SELECT p.id,
           $titleInkoop  AS name,
           $descInkoop   AS description,
           p.total_product_price AS price,
           p.margin AS margin,
           p.product_image AS image,
           'verpakking' AS category,
           'inkoop' AS src
    FROM inkoop_products p
    $joinInkoop
    WHERE p.sub_category='uitvaart'
";

// --- Filterlaag ---
$whereParts=[]; $params=[]; $types='';
if(!$isPartner){
  // Verberg deze twee voor publiek; andere inkoop-producten blijven zichtbaar
  $whereParts[] = "NOT (src='inkoop' AND id IN (38,39))";
}

// Categorie filter
if($activeCat!==''){
  $whereParts[]="category = ?";
  $params[]=$activeCat;
  $types.='s';
}

// Zoekterm (parameters zijn ASCII/UTF8, MySQL bepaalt collation via kolommen; we hebben die al vastgezet)
if($search!==''){
  $whereParts[]="(name LIKE ? OR description LIKE ?)";
  $like='%'.$search.'%';
  $params[]=$like; $params[]=$like;
  $types.='ss';
}

// As-verwerking via product_as (alleen product_id beschikbaar)
$existsSql="EXISTS (SELECT 1 FROM product_as pa WHERE pa.product_id=u.id)";
if($ash==='ja'){ $whereParts[]=$existsSql; }
if($ash==='nee'){ $whereParts[]="NOT $existsSql"; }

// --- Tellen voor paginering ---
$sqlCount="SELECT COUNT(*) AS cnt FROM ( $unionBase ) u";
if($whereParts) $sqlCount.=" WHERE ".implode(' AND ',$whereParts);

$stmt=$mysqli_medewerkers->prepare($sqlCount);
if($types) $stmt->bind_param($types,...$params);
$stmt->execute();
$res=$stmt->get_result();
$totalItems=(int)($res->fetch_assoc()['cnt']??0);
$stmt->close();

$totalPages=max(1,(int)ceil($totalItems/$perPage));
$page=min($page,$totalPages);
$offset=($page-1)*$perPage;

// --- Ophalen rijtjes ---
$sqlRows="SELECT u.*,
  (EXISTS (SELECT 1 FROM product_as pa WHERE pa.product_id=u.id)) AS has_ash,
  (SELECT pa2.gram FROM product_as pa2 WHERE pa2.product_id=u.id LIMIT 1) AS ash_gram
  FROM ( $unionBase ) u";
if($whereParts) $sqlRows.=" WHERE ".implode(' AND ',$whereParts);
$sqlRows.=" ORDER BY name ASC LIMIT ?, ?";

$stmt=$mysqli_medewerkers->prepare($sqlRows);
if($types){
  $types2=$types.'ii';
  $params2=$params; $params2[]=$offset; $params2[]=$perPage;
  $stmt->bind_param($types2,...$params2);
}else{
  $stmt->bind_param('ii',$offset,$perPage);
}
$stmt->execute();
$result=$stmt->get_result();
$items=[];
while($row=$result->fetch_assoc()){ $items[]=$row; }
$stmt->close();

?>
<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>
<main class="hero" id="top">
  <section class="hero-content">
    <h1>Ons Assortiment</h1>
    <p>Hieronder vindt je ons volledig assortiment. Speciaal voor herdenking van onze dierbare personen of dieren.</p>
  </section>
</main>

<main class="products">
  <div class="container">
    <!-- Filterbalk -->
    <div class="filter-bar">
      <div class="cat-bar">
        <a class="tag<?php echo $activeCat===''?' active':'';?>" href="<?php echo q(buildUrl(['cat'=>null,'page'=>1]));?>">Alles</a>
        <a class="tag<?php echo $activeCat==='decoratie'?' active':'';?>" href="<?php echo q(buildUrl(['cat'=>'decoratie','page'=>1]));?>"><span class="dot dot-decoratie"></span>Decoratie</a>
        <a class="tag<?php echo $activeCat==='kaarsen'?' active':'';?>" href="<?php echo q(buildUrl(['cat'=>'kaarsen','page'=>1]));?>"><span class="dot dot-kaarsen"></span>Kaarsen</a>
        <a class="tag<?php echo $activeCat==='verpakking'?' active':'';?>" href="<?php echo q(buildUrl(['cat'=>'verpakking','page'=>1]));?>"><span class="dot dot-verpakking"></span>Verpakking</a>
      </div>

      <form method="get" action="" class="search-bar" role="search">
        <?php if($activeCat!==''): ?><input type="hidden" name="cat" value="<?php echo q($activeCat);?>"><?php endif; ?>
        <select name="as" aria-label="Filter op as-verwerking">
          <?php
            $ashOptions=[''=>'As-verwerking (alles)','ja'=>'Met as-verwerking','nee'=>'Zonder as-verwerking'];
            foreach($ashOptions as $val=>$label){
              $sel=$ash===$val?' selected':'';
              echo '<option value="'.q($val).'"'.$sel.'>'.q($label).'</option>';
            }
          ?>
        </select>
        <input type="search" name="q" placeholder="Zoek product..." value="<?php echo q($search);?>" enterkeyhint="search" inputmode="search" aria-label="Zoek product">
        <button type="submit">Zoeken</button>
        <?php if($search!==''): ?><a class="btn-clear" href="<?php echo q(buildUrl(['q'=>null,'page'=>1]));?>">Wissen</a><?php endif; ?>
      </form>
    </div>

    <!-- Grid -->
    <div class="product-grid">
      <?php if(empty($items)): ?>
        <div class="empty-state">
          <div class="empty-card" role="status" aria-live="polite">
            <div class="empty-emoji">🕯️</div>
            <p class="empty-title">Geen producten gevonden</p>
            <p class="empty-text">We vonden geen resultaten voor deze combinatie van filters.</p>
            <div class="empty-actions">
              <a class="btn-reset" href="<?php echo q(buildUrl(['cat'=>null,'q'=>null,'as'=>null,'page'=>1]));?>">Toon alles</a>
              <?php if($activeCat!==''): ?><a class="btn-all" href="<?php echo q(buildUrl(['q'=>null,'as'=>null,'page'=>1]));?>">Alle <?php echo q($activeCat);?></a><?php endif; ?>
            </div>
          </div>
        </div>
      <?php else: ?>
        <?php foreach($items as $p): ?>
          <?php
    $id        = (int)$p['id'];
    $bestelUrl = "/pages/product.php?type=" . q($p['src']) . "&product=" . $id;

    // ID's zonder partnerkorting (maar wel bestelbaar)
    $blockedNoDiscountIds = [34,35,36,38,39];

    // ID's die voor particulieren géén knop mogen tonen
    $publicNoButtonIds = [38,39];

    $isBlockedNoDiscount = in_array($id,$blockedNoDiscountIds,true);
    $isPublicNoButton    = !$isPartner && in_array($id,$publicNoButtonIds,true);

    // extra klasse voor 38/39
    $isToggleBlocked = in_array($id,[38,39],true);

    // Basisprijs (particulier, incl. btw)
    $baseListPrice   = (float)$p['price'];
    $listParticulier = number_format($baseListPrice,2,',','.');

    $btwNummer = isset($_SESSION['btw_nummer']) ? strtoupper($_SESSION['btw_nummer']) : '';
    $btwLabel  = 'incl. btw';
    $listPartner = $listParticulier; // default zelfde als particulier

    // Alleen partner ziet partnerprijs
    if($isPartner){

        // Geen korting voor 34–39
        if($isBlockedNoDiscount){
            $listPartner = $listParticulier;

        }else{

            $partnerRaw = null;

            // ➜ uitgebreide kostprijs-berekening voor epoxy/kaarsen
            if(in_array($p['src'],['epoxy','kaarsen'],true)){

                // Volledige productrij ophalen
                if($p['src']==='epoxy'){
                    $stmtProd=$mysqli_medewerkers->prepare("SELECT * FROM epoxy_products WHERE id=? LIMIT 1");
                }else{
                    $stmtProd=$mysqli_medewerkers->prepare("SELECT * FROM kaarsen_products WHERE id=? LIMIT 1");
                }
                $stmtProd->bind_param('i',$id);
                $stmtProd->execute();
                $resProd=$stmtProd->get_result();
                $product=$resProd->fetch_assoc() ?: null;
                $stmtProd->close();

                if($product){

                    // === BTW & verkoopprijs excl. btw ===
                    $basePrice = $baseListPrice; // total_product_price incl. btw
                    $vat_raw   = (float)field_or_null($product,['vat_percentage','vat_percetage']);
                    $vat       = ($vat_raw>1 ? $vat_raw/100 : $vat_raw);
                    if($vat<=0){$vat=0.21;}

                    $db_price     = $basePrice;
                    $price_ex_vat = round($db_price/(1+$vat),2);

                    // === GRONDSTOFKOST ===
                    $base_cost = 0.0;
                    $amount_grams_for_hours = 0.0;

                    // uren
                    $hours_raw    = field_or_null($product,['hours_worked']);
                    $hours_worked = 0.0;
                    if($hours_raw!==null){
                        if(strpos($hours_raw,':')!==false){
                            list($h,$m)=explode(':',$hours_raw);
                            $hours_worked=((int)$h)+((int)$m/60);
                        }else{
                            $hours_worked=(float)$hours_raw;
                        }
                    }

                    // epoxy / terrazzo
                    $category = ($p['src']==='kaarsen') ? 'kaars' : 'epoxy';
                    if($category==='epoxy' || $category==='terrazzo'){
                        $amount=(float)field_or_null($product,['amount_grams','amount_gram']);
                        $ppg   =(float)field_or_null($product,['price_per_gram']);
                        $base_cost=$amount*$ppg;
                        $amount_grams_for_hours=$amount;
                    }
                    // kaars: paraffine + stearine
                    elseif($category==='kaars'){
                        $paraffin_grams=(float)field_or_null($product,['amount_paraffin_grams']);
                        $stearin_grams =(float)field_or_null($product,['amount_stearin_grams']);

                        $paraffin=$paraffin_grams*(float)field_or_null($product,['price_per_gram_paraffin']);
                        $stearin =$stearin_grams *(float)field_or_null($product,['price_per_gram_stearin']);

                        $base_cost=$paraffin+$stearin;
                        $amount_grams_for_hours=$paraffin_grams+$stearin_grams;
                    }

                    $base_cost=round($base_cost,2);

                    // === EXTRA KOSTEN ===
                    $extra_parts =(float)field_or_null($product,['extra_parts_price']) ?: 0.0;
                    $company_cost=(float)field_or_null($product,['company_cost_per_product']) ?: 0.0;

                    // === UURLOON OP BASIS VAN GRAM ===
                    $hourly_rate=0.0;
                    if($amount_grams_for_hours>0){
                        if($amount_grams_for_hours<500){$hourly_rate=2.5;}
                        elseif($amount_grams_for_hours<1000){$hourly_rate=5.0;}
                        elseif($amount_grams_for_hours<2000){$hourly_rate=8.0;}
                        else{$hourly_rate=10.0;}
                    }
                    $hours_cost=round($hours_worked*$hourly_rate,2);

                    // Totale kostprijs excl. btw
                    $cost_ex_vat=round($base_cost+$extra_parts+$company_cost+$hours_cost,2);

                    if($price_ex_vat>0 && $cost_ex_vat>0){
                        // totale winst excl. btw
                        $total_profit_ex_vat=max(0,$price_ex_vat-$cost_ex_vat);
                        // partner krijgt deel van winst (1 / $percentageuitvaart)
                        $partner_profit_ex_vat=round($total_profit_ex_vat/$percentageuitvaart,2);
                        $partner_price_ex_vat=round($cost_ex_vat+$partner_profit_ex_vat,2);

                        if(str_starts_with($btwNummer,'NL')){
                            $btwLabel='excl. btw';
                            $partnerRaw=$partner_price_ex_vat;
                        }else{
                            $btwLabel='incl. btw';
                            $partnerRaw=round($partner_price_ex_vat*(1+$vat),2);
                        }

                        // veiligheid: partner nooit duurder dan particulier
                        if($partnerRaw>$baseListPrice){
                            $partnerRaw=$baseListPrice;
                        }
                    }
                }
            }

            // ➜ fallback: oude marge-berekening (bij inkoop of als kostberekening faalt)
            if($partnerRaw===null){
                $marginPercent = isset($p['margin']) ? (float)$p['margin'] : 0.0;
                $priceExVat    = $baseListPrice/1.21;
                $markup        = $marginPercent/100;
                if($markup<=-1){
                    $partnerRaw=$priceExVat;
                }else{
                    $productCost  = $priceExVat/(1+$markup);
                    $marginAmount = $priceExVat-$productCost;
                    $halfMargin   = $marginAmount/$percentageuitvaart;
                    $partnerPriceExVat=$productCost+$halfMargin;
                    if(str_starts_with($btwNummer,'NL')){
                        $btwLabel='excl. btw';
                        $partnerRaw=$partnerPriceExVat;
                    }else{
                        $btwLabel='incl. btw';
                        $partnerRaw=$partnerPriceExVat*1.21;
                    }
                }
            }

            $listPartner=number_format($partnerRaw,2,',','.');
        }
    }
  ?>

  <div class="product-card<?php echo $isToggleBlocked ? ' toggle-blocked' : ''; ?>">
    <a class="img-link" href="<?php echo $bestelUrl; ?>" aria-label="Bestel: <?php echo q($p['name']); ?>">
      <img src="https://medewerkers.windelsgreen-decoresin.com/winkel/product_img/<?php echo q($p['image']); ?>"
           alt="<?php echo q($p['name']); ?>" loading="lazy">
    </a>

    <div class="product-meta">
      <a class="tag small" href="<?php echo q(buildUrl(['cat'=>$p['category'],'page'=>1]));?>">
        <?php echo q(ucfirst($p['category'])); ?>
      </a>
      <?php if (!empty($p['has_ash']) && (int)$p['has_ash'] === 1): ?>
        <a class="tag small" href="<?php echo q(buildUrl(['as'=>'ja','page'=>1]));?>" title="Met as-verwerking">
          🕯️ As-verwerking<?php echo isset($p['ash_gram']) && $p['ash_gram']!==null && $p['ash_gram']!=='' ? ' ('.q($p['ash_gram']).' g)' : ''; ?>
        </a>
      <?php endif; ?>
    </div>

    <h3><?php echo q($p['name']); ?></h3>
    <p><?php echo $p['description']; ?></p>

    <?php if($isPartner): ?>

      <p class="price">
        <strong>
          <hr>
          <span class="price-particulier">
            Particulier <small>(incl. btw)</small><br>
            <sup>€</sup><?= $listParticulier ?>
          </span>
          <hr>
          <span class="price-partner">
            Partner <small>(<?= $btwLabel ?>)</small><br>
            <sup>€</sup><?= $listPartner ?>
            <hr>
          </span>
          
        </strong>
      </p>

      <a href="<?= $bestelUrl ?>" class="btn price-partner-link">Bestel nu</a>


<?php else: ?>

<p class="price">
  <strong><span class="price-particulier"><sup>€</sup><?= $listParticulier ?></span></strong>
</p>

<?php if($isPublicNoButton): ?>
  <!-- NIET ingelogd + ID 38/39: geen knop -->
  <p class="only-funeral price-particulier">
  Dit artikel is enkel beschikbaar als partnerproduct bij Windels green &amp; deco resin.
  </p>
<?php else: ?>
  <a href="<?= $bestelUrl ?>" class="btn price-particulier-link">Bestel nu</a>
<?php endif; ?>
<?php endif; ?>
  </div>
<?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Paginering -->
    <?php if($totalPages>=1): ?>
      <div class="pagination" role="nav-pageigation" aria-label="Paginering">
        <?php if($page>1): ?>
          <a class="nav-page prev" href="<?php echo q(buildUrl(['page'=>$page-1]));?>" rel="prev" aria-label="Vorige pagina">← Vorige</a>
        <?php else: ?>
          <span class="nav-page prev disabled" aria-hidden="true">← Vorige</span>
        <?php endif; ?>

        <span class="page-links" aria-live="polite">
          <?php
            $start=max(1,$page-2); $end=min($totalPages,$page+2);
            if($start>1){
              echo '<a href="'.q(buildUrl(['page'=>1])).'">1</a>';
              if($start>2) echo '<span class="dots">…</span>';
            }
            for($i=$start;$i<=$end;$i++){
              $cls=$i===$page?'active':'';
              echo '<a class="'.$cls.'" href="'.q(buildUrl(['page'=>$i])).'">'.$i.'</a>';
            }
            if($end<$totalPages){
              if($end<$totalPages-1) echo '<span class="dots">…</span>';
              echo '<a href="'.q(buildUrl(['page'=>$totalPages])).'">'.$totalPages.'</a>';
            }
          ?>
        </span>

        <?php if($page<$totalPages): ?>
          <a class="nav-page next" href="<?php echo q(buildUrl(['page'=>$page+1]));?>" rel="next" aria-label="Volgende pagina">Volgende →</a>
        <?php else: ?>
          <span class="nav-page next disabled" aria-hidden="true">Volgende →</span>
        <?php endif; ?>
      </div>
      <div class="paging-info">Pagina <?php echo $page;?> van <?php echo $totalPages;?> • <?php echo $totalItems;?> items</div>
    <?php endif; ?>
  </div>
</main>

<style>
/* --- basis knop- en prijslogica --- */
.price-partner{display:none}
.price-particulier-link{display:inline-block }
.price-partner-link{display:none}

/* Partner-modus */
html[data-price-mode="partner"] .price-particulier{display:inline}
html[data-price-mode="partner"] .price-partner{display:inline}
html[data-price-mode="partner"] .price-particulier-link{display:none}
html[data-price-mode="partner"] .price-partner-link{display:inline-block}

html:not([data-price-mode="partner"]) .toggle-blocked .price-particulier-link,
html:not([data-price-mode="partner"]) .toggle-blocked .price-partner-link{
  display:none !important;
}
/* compact CSS (ongewijzigd behalve dat small_desc nu wordt getoond) */
.filter-bar{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:space-between;margin:8px 0 18px}
.cat-bar{display:flex;flex-wrap:wrap;gap:8px;margin:0;padding:6px;border:1px solid #e6e6e6;border-radius:14px;background:#f9f9f7}
.tag{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border:1px solid #b6c2b6;border-radius:999px;text-decoration:none;font-size:.92rem;color:#1f3b2d;background:#fff;box-shadow:0 1px 0 rgba(0,0,0,.04);transition:transform .08s ease,box-shadow .08s ease}
.tag:hover{transform:translateY(-1px);box-shadow:0 3px 8px rgba(0,0,0,.06)}
.tag:focus-visible{outline:none;box-shadow:0 0 0 3px rgba(31,59,45,.18)}
.tag.active{background:#1f3b2d;color:#fff;border-color:#1f3b2d;box-shadow:inset 0 -2px 0 rgba(0,0,0,.15)}
.tag.small{padding:4px 8px;font-size:.8rem}
.tag .dot{width:8px;height:8px;border-radius:50%}
.dot-decoratie{background:#2e6b4a}.dot-kaarsen{background:#b3812d}.dot-verpakking{background:#6b7280}

.search-bar{display:flex;gap:8px;margin-left:auto}
.search-bar select,.search-bar input,.search-bar button,.btn-clear{min-height:44px}
.search-bar select{padding:8px 10px;border:1px solid #cfd8cf;border-radius:10px}
.search-bar input{flex:1;min-width:220px;padding:8px 10px;border:1px solid #cfd8cf;border-radius:10px}
.search-bar button{padding:8px 12px;background:#1f3b2d;color:#fff;border:none;border-radius:10px;cursor:pointer}
.search-bar button:hover{filter:brightness(1.08)}
.btn-clear{display:inline-flex;align-items:center;justify-content:center;padding:8px 10px;border:1px solid #cfd8cf;border-radius:10px;background:#eef2ee;color:#1f3b2d;text-decoration:none}

/* Mobielvriendelijk: stapelen + full-width + grote touch targets */
@media(max-width:720px){
  .filter-bar{align-items:stretch;gap:12px}
  .search-bar{width:100%;margin-left:0;display:grid;grid-template-columns:1fr auto;grid-template-areas:"sel sel" "q btn" "clr clr";gap:8px}
  .search-bar select{grid-area:sel;width:100%}
  .search-bar input{grid-area:q;width:100%}
  .search-bar button{grid-area:btn}
  .btn-clear{grid-area:clr;text-align:center}
}


.product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px}
.product-card{border:1px solid #e6e6e6;border-radius:16px;padding:14px;display:flex;flex-direction:column;background:#fff}
.product-card img{width:100%;height:180px;object-fit:cover;border-radius:12px}
.product-meta{margin:8px 0;display:flex;gap:6px;flex-wrap:wrap}
.btn{display:inline-block;padding:10px 14px;border-radius:12px;background:#1f3b2d;color:#fff;text-decoration:none}
.btn:hover{filter:brightness(1.05)}
.product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px}
.product-card{border:1px solid #e6e6e6;border-radius:16px;padding:14px;display:flex;flex-direction:column;background:#fff}
.product-card img{width:100%;height:180px;object-fit:cover;border-radius:12px}
.product-meta{margin:8px 0}
.btn{display:inline-block;padding:10px 14px;border-radius:12px;background:#1f3b2d;color:#fff;text-decoration:none}
.btn:hover{filter:brightness(1.05)}

.empty-state{display:flex;align-items:center;justify-content:center;grid-column:1/-1}
.empty-card{max-width:720px;width:100%;margin:16px auto;padding:22px;border:1px dashed #c8d0c8;border-radius:16px;background:#fbfcfb;text-align:center}
.empty-emoji{font-size:34px;line-height:1;margin-bottom:8px}
.empty-title{margin:0 0 6px;font-size:1.1rem;color:#1f3b2d}
.empty-text{margin:0 0 14px;font-size:.95rem;color:#3a4a3a}
.empty-actions{display:flex;gap:10px;justify-content:center}
.btn-reset,.btn-all{display:inline-block;padding:10px 14px;border-radius:12px;text-decoration:none}
.btn-reset{background:#1f3b2d;color:#fff}.btn-reset:hover{filter:brightness(1.06)}
.btn-all{background:#eef2ee;color:#1f3b2d;border:1px solid #cfd8cf}

/* Paginering: desktop */
.pagination{width:100%;display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:8px;margin:20px 0}
.pagination .prev{justify-self:start}
.pagination .next{justify-self:end}
.pagination .page-links{justify-self:center;display:flex;gap:6px;flex-wrap:wrap;max-width:100%}
.pagination a,.pagination .nav-page{padding:6px 12px;border:1px solid #cfd8cf;border-radius:8px;text-decoration:none;color:#1f3b2d;background:#fff}
.pagination .page-links{padding:0;border:0;background:transparent}
.pagination a.active{background:#1f3b2d;color:#fff;border-color:#1f3b2d}
.pagination a.nav-page{font-weight:600}
.pagination a:hover{background:#e8eee8}
.pagination .dots{border:none;background:transparent;padding:0 4px}
.pagination .disabled{opacity:.5;pointer-events:none}
.pagination .page-links{min-width:0;flex-wrap:wrap}


/* Mobiel: knoppen bovenaan links/rechts, nummers op eigen rij gecentreerd */
@media(max-width:720px){
  .pagination{grid-template-columns:1fr 1fr;grid-template-areas:"prev next" "pages pages";gap:10px}
  .pagination .prev{grid-area:prev;justify-self:start;min-height:44px}
  .pagination .next{grid-area:next;justify-self:end;min-height:44px}
  .pagination .page-links{grid-area:pages;justify-content:center}
  .pagination .page-links a{padding:8px 12px}
}


</style>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
