<?php
include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';

if(!function_exists('field_or_null')){
  function field_or_null(array $row, array $keys){
      foreach($keys as $k){
          if(isset($row[$k]) && $row[$k] !== '' && $row[$k] !== null){
              return $row[$k];
          }
      }
      return null;
  }
}

// partner-aandeel: standaard 2 (= helft winst)
if(!isset($percentageuitvaart) || $percentageuitvaart <= 0){
  $percentageuitvaart = 2;
}

$items = [];

// LET OP: alleen small_desc uit product_webshop via SKU (géén fallback)
$sql = "
    SELECT 
        'epoxy' AS type,
        p.id,
        p.title AS name,
        CAST(COALESCE(w.small_desc,'') AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci AS description,
        p.category AS src,
        p.total_product_price AS price,
        p.margin AS margin,
        p.product_image AS image,
        p.hours_worked,
        p.amount_grams,
        NULL AS amount_paraffin_grams,
        NULL AS amount_stearin_grams,
        p.price_per_gram,
        NULL AS price_per_gram_paraffin,
        NULL AS price_per_gram_stearin,
        p.vat_percentage,
        p.extra_parts_price,
        p.company_cost_per_product
    FROM epoxy_products p
    LEFT JOIN product_webshop w
      ON (CONVERT(w.sku USING utf8mb4) COLLATE utf8mb4_general_ci
          = CONVERT(p.sku USING utf8mb4) COLLATE utf8mb4_general_ci)
    WHERE p.sub_category='uitvaart'

    UNION ALL

    SELECT
        'kaarsen' AS type,
        k.id,
        k.title AS name,
        CAST(COALESCE(w.small_desc,'') AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_general_ci AS description,
        k.category AS src,
        k.total_product_price AS price,
        k.margin AS margin,
        k.product_image AS image,
        k.hours_worked,
        NULL AS amount_grams,
        k.amount_paraffin_grams,
        k.amount_stearin_grams,
        NULL AS price_per_gram,
        k.price_per_gram_paraffin,
        k.price_per_gram_stearin,
        k.vat_percentage,
        k.extra_parts_price,
        k.company_cost_per_product
    FROM kaarsen_products k
    LEFT JOIN product_webshop w
      ON (CONVERT(w.sku USING utf8mb4) COLLATE utf8mb4_general_ci
          = CONVERT(k.sku USING utf8mb4) COLLATE utf8mb4_general_ci)
    WHERE k.sub_category='uitvaart'

    ORDER BY RAND()
    LIMIT 4
";


// Veilig uitvoeren + foutopvolging
if (!$result = $mysqli_medewerkers->query($sql)) {
    error_log('SQL error (uitvaart homepage): '.$mysqli_medewerkers->error);
} else {
    while ($row = $result->fetch_assoc()) {
        // Type-casting & normalisatie
        $row['id'] = (int)$row['id'];
        $row['price'] = (float)$row['price'];
        $row['margin'] = isset($row['margin']) ? (float)$row['margin'] : 0.0;
        $row['name'] = (string)$row['name'];
        $row['description'] = (string)$row['description']; // komt enkel uit product_webshop.small_desc
        $row['image'] = (string)$row['image'];
        $row['type'] = ($row['type']==='epoxy'?'epoxy':'kaarsen'); // whitelisten
        $items[] = $row;
    }
    $result->free();
}

include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';
?>
<style>
.partner-strip{
  padding:2.5rem 0 1.5rem;
  background:transparent;
}

/* kaart op basis van jouw nieuwe banner-afbeelding */
.partner-card{
  max-width:1180px;
  margin:0 auto;
  padding:1.9rem 2.4rem;
  border-radius:20px;
  background:url('/img/partnerachtergrond.png') no-repeat center center/cover;
  position:relative;
  overflow:hidden;
  border-top:3px solid #f1c57a;          /* warm goud, kleur van het kaarslicht */
  box-shadow:0 18px 40px rgba(0,0,0,.45);
  color:#f7f3ec;
}

/* zachte donkere overlay zodat tekst altijd leesbaar is */
.partner-card::before{
  content:"";
  position:absolute;
  inset:0;
  background:linear-gradient(90deg,rgba(20,20,20,.35),rgba(25,25,25,.15));

  pointer-events:none;
  z-index:0;
}

/* oude cirkel verwijderen */
.partner-card::after{content:none;}

.partner-main{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:2rem;
  position:relative;
  z-index:1;              /* boven de overlay */
}

.partner-badge{
  display:inline-block;
  padding:.28rem 1rem;
  border-radius:999px;
  background:rgba(255, 255, 255, 0.18); /* licht en neutraal, zichtbaar op donkere overlay */
  backdrop-filter:blur(2px);            /* soft glass effect */
  -webkit-backdrop-filter:blur(2px);
  color:#ffe7b3;                        /* warm goudtint passend bij jouw foto */
  font-size:.82rem;
  font-weight:700;
  letter-spacing:.08em;
  text-transform:uppercase;
  margin-bottom:1rem;
  border:1px solid rgba(255,255,255,.28); /* subtiele rand voor extra zichtbaarheid */
}


.partner-copy h2{
  font-size:1.7rem;
  margin:0 0 .5rem;
  color:#f9f5ee;
}

.partner-copy p{
  margin:0 0 .75rem;
  color:#e4dbcf;
  font-size:.98rem;
}

.partner-copy ul{
  margin:0;
  padding-left:1.1rem;
  color:#f1e6d7;
  font-size:.95rem;
}

.partner-copy li{
  margin:.2rem 0;
}

.partner-side{
  text-align:right;
  min-width:230px;
}

.partner-highlight{
  display:inline-block;
  text-align:right;
  margin-bottom:.75rem;
}

.partner-highlight .label{
  display:block;
  font-size:.78rem;
  text-transform:uppercase;
  letter-spacing:.08em;
  color:#f1c57a;
}

.partner-highlight .value{
  display:block;
  font-size:1.02rem;
  font-weight:700;
  color:#f9f5ee;
}

/* knop in lijn met kaarslicht + jouw groen */
.partner-btn{
  display:inline-block;
  padding:.8rem 1.9rem;
  border-radius:999px;
  background:linear-gradient(135deg,#f1c57a,#c89a4f);
  color:#1b251a;
  font-weight:600;
  font-size:.95rem;
  text-decoration:none;
  border:1px solid #e3b86a;
  box-shadow:0 10px 26px rgba(0,0,0,.55);
  transition:transform .18s ease,box-shadow .18s ease,filter .18s ease;
}

.partner-btn:hover{
  filter:brightness(1.05);
  transform:translateY(-1px);
  box-shadow:0 14px 36px rgba(0,0,0,.65);
}

.partner-side small{
  display:block;
  margin-top:.4rem;
  font-size:.8rem;
  color:#d6cbbd;
}

/* Responsive */
@media(max-width:900px){
  .partner-card{
    padding:1.6rem 1.5rem;
  }
  .partner-main{
    flex-direction:column;
    align-items:flex-start;
  }
  .partner-side,
  .partner-highlight{
    text-align:left;
  }
}

</style>
<main class="hero" id="top">
  <section class="hero-content">
    <h1>Welkom bij onze uitvaartzorg webshop</h1>
    <p class="slide-text">
      <span class="items">
        <span>Een serene omgeving voor het bestellen van gepersonaliseerde decoraties met asverwerking.</span>
        <span>Een waardig aandenken voor dierbare herinneringen.</span>
        <span>Betrouwbaar, stijlvol en persoonlijk voor elke uitvaartdienst.</span> 
        <span>Met zorg en aandacht gemaakt, speciaal voor u.</span>
        <span>Een budgetvriendelijk alternatief voor een urn, met dezelfde waardevolle herinnering.</span>
        <span>Een stijlvolle en betaalbare keuze, goedkoper dan een traditionele urn.</span>
      </span>
    </p>
    <a href="#producten" class="btn" aria-label="Ga naar het assortiment">Bekijk ons Assortiment</a>
  </section>
</main>


<section class="reviews-section">
  <div class="container">
  <h2>Wat klanten zeggen</h2>
<small class="reviews-bronnen">Gebaseerd op beoordelingen van Facebook, Instagram, Google en Trustpilot.</small>

    <div class="total-rating">
    <span id="averageStars" class="stars" aria-hidden="true">★★★★★</span>
    <span id="averageScore"></span>
    </div>

    <div class="reviews-slider">
      <button class="prev" aria-label="Vorige review" type="button">‹</button>
      <div class="viewport" tabindex="0" aria-label="Klantreviews, horizontaal scrollbaar">
        <ol class="track">
            <li class="review-card in" data-rating="5">
                <p>"We hebben alles 1 mooie lotusbloem. We gaan er nog 5 bestellen."</p>
                <div class="stars">★★★★★</div>
                <span>– Jeroen Mol</span>
                <span class="plaats">Grave (NL)</span>
            </li>
            <li class="review-card in" data-rating="4">
                <p>"We hebben een mooie lotusbloem besteld. Het ziet er prachtig uit!"</p>
                <div class="stars">★★★★☆</div>
                <span>– Dion Lucassen</span>
                <span class="plaats">Venlo (NL)</span>
            </li>
            <li class="review-card in" data-rating="5">
                <p>"Mooie lotusbloem besteld. Deze is heel prachtig."</p>
                <div class="stars">★★★★★</div>
                <span>– Caatje Goudzwaart</span>
                <span class="plaats">Achel (BE)</span>
            </li>
            <li class="review-card in" data-rating="5">
                <p>"Het herdenkingssieraad is subtiel en geeft troost. Aanrader."</p>
                <div class="stars">★★★★★</div>
                <span>– Anoniem</span>
                <span class="plaats">Eindhoven (NL)</span>
            </li>
            <li class="review-card in" data-rating="5">
                <p>"Zeer professionele afhandeling. Alles perfect geregeld."</p>
                <div class="stars">★★★★★</div>
                <span>– Uitvaartzorg Noord</span>
                <span class="plaats">Leeuwarden (NL)</span>
            </li>
            <li class="review-card in" data-rating="5">
                <p>"Snelle en warme communicatie, met oog voor detail."</p>
                <div class="stars">★★★★★</div>
                <span>– Familie Janssens</span>
                <span class="plaats">Antwerpen (BE)</span>
            </li>
            <li class="review-card in" data-rating="4">
                <p>"De resin decoratie voelt waardig en persoonlijk aan."</p>
                <div class="stars">★★★★☆</div>
                <span>– Anoniem</span>
                <span class="plaats">Rotterdam (NL)</span>
            </li>
            <li class="review-card in" data-rating="5">
                <p>"Mooi vakmanschap. We zijn heel dankbaar."</p>
                <div class="stars">★★★★★</div>
                <span>– Familie Peeters</span>
                <span class="plaats">Brugge (BE)</span>
            </li>
            <li class="review-card in" data-rating="5">
                <p>"Discrete service en heldere uitleg bij elke stap."</p>
                <div class="stars">★★★★★</div>
                <span>– Uitvaartcentrum Lotus</span>
                <span class="plaats">Tilburg (NL)</span>
            </li>
            <li class="review-card in" data-rating="5">
                <p>"Het resultaat overtrof onze verwachtingen."</p>
                <div class="stars">★★★★★</div>
                <span>– Familie Van den Broeck</span>
                <span class="plaats">Mechelen (BE)</span>
            </li>
            <li class="review-card in" data-rating="5">
                <p>"Veel respect, alles werd heel netjes behandeld."</p>
                <div class="stars">★★★★★</div>
                <span>– Anoniem</span>
                <span class="plaats">Utrecht (NL)</span>
            </li>
            <li class="review-card in" data-rating="5">
                <p>"Een tastbare herinnering die rust brengt."</p>
                <div class="stars">★★★★★</div>
                <span>– Familie De Smet</span>
                <span class="plaats">Kortrijk (BE)</span>
            </li>
        </ol>
      </div>
      <button class="next" aria-label="Volgende review" type="button">›</button>
    </div>
  </div>
</section>
<script>
document.addEventListener("DOMContentLoaded",()=>{
  const cards=[...document.querySelectorAll(".review-card")];
  let total=0;

  cards.forEach(card=>{
    const rating=Math.max(0,Math.min(5,parseFloat(card.dataset.rating)||0));
    total+=rating;
    const starEl=card.querySelector(".stars");
    if(starEl){
      starEl.style.setProperty("--percent",`${(rating/5)*100}%`);
      starEl.setAttribute("role","img");
      starEl.setAttribute("aria-label",`${rating} van 5 sterren`);
    }
  });

  const avg=(total/cards.length).toFixed(1);
  const avgStars=document.getElementById("averageStars");
  const avgScore=document.getElementById("averageScore");
  avgStars.style.setProperty("--percent",`${(avg/5)*100}%`);
  avgStars.setAttribute("role","img");
  avgStars.setAttribute("aria-label",`${avg} van 5 sterren`);
  avgScore.textContent=`${avg}/5 – gebaseerd op ${cards.length} reviews`;
});
</script>
<section class="why-section" aria-labelledby="why-title">
    <div class="container">
        <h2 id="why-title">Waarom kiezen voor Windels?</h2>
        <div class="why-grid">
            <div class="why-card">
                <h3>Zorg &amp; Respect</h3>
                <p>Elke creatie wordt met de grootste zorg en respect vervaardigd.</p>
            </div>
            <div class="why-card">
                <h3>Ambacht &amp; Kwaliteit</h3>
                <p>Wij combineren ambacht met duurzame materialen en professionele afwerking.</p>
            </div>
            <div class="why-card">
                <h3>Persoonlijke benadering</h3>
                <p>Wij luisteren naar uw wensen en zorgen voor een waardig aandenken.</p>
            </div>
        </div>
    </div>
</section>

<section class="steps-section" aria-labelledby="steps-title">
    <div class="container">
        <h2 id="steps-title">Hoe werkt het?</h2>
        <div class="steps-grid">
            <?php 
           $stappen = [
            "Kies een herinneringsproduct uit ons assortiment.",
            "Beslis of u de bestelling zelf plaatst of via een uitvaartpartner.",
            "Indien u via een uitvaartpartner bestelt, bespreek uw keuze met hen.",
            "De bestelling wordt geplaatst en de as van uw dierbare wordt aan ons bezorgd.",
            "Wij maken met zorg het gekozen product met de as van uw dierbare.",
            "Na voltooiing leveren wij het product aan u of aan uw uitvaartpartner.",
            "U ontvangt het unieke herinneringsproduct op een respectvolle manier.",
            "Als particulier kunt u direct online betalen.",
            "Via een uitvaartpartner wordt gefactureerd na afhaling."
          ];
              
            foreach ($stappen as $index => $tekst): ?>
                <div class="step-card">
                    <h3><?php echo (int)$index + 1; ?></h3>
                    <p><?php echo htmlspecialchars($tekst, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="producten" class="products" aria-labelledby="products-title">
    <div class="container">
        <h2 id="products-title">Populaire Herinneringsproducten</h2>

        <?php if (empty($items)): ?>
            <p>Momenteel geen producten om te tonen. Probeer het later opnieuw of bekijk <a class="link" href="pages/assortiment.php">alle producten</a>.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($items as $p): 
                    $name = htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8');
                    $desc = $p['description'];
                    $img  = htmlspecialchars($p['image'], ENT_QUOTES, 'UTF-8');
                    $basePrice = (float)$p['price']; 
$priceParticulier = number_format($basePrice,2,',','.');

$type = $p['type'];
$id   = (int)$p['id'];
$isPartner = !empty($_SESSION['partner_id']);
$isBlocked = in_array($id,[38,39],true);

$btwNummer = isset($_SESSION['btw_nummer']) ? strtoupper($_SESSION['btw_nummer']) : '';
$btwLabel  = 'incl. btw';

$product  = $p;
$category = ($type === 'kaarsen') ? 'kaars' : 'epoxy';

// ===== BTW & verkoopprijs excl. btw =====
$vat_raw = (float)field_or_null($product, ['vat_percentage','vat_percetage']);
$vat     = ($vat_raw > 1 ? $vat_raw/100 : $vat_raw);
if($vat <= 0){ $vat = 0.21; } // fallback 21%

$db_price     = $basePrice; // total_product_price uit DB, incl. btw
$price_ex_vat = round($db_price / (1 + $vat), 2);

// ===== GRONDSTOFKOST =====
$base_cost = 0.0;
$amount_grams_for_hours = 0.0;

// uren
$hours_raw    = field_or_null($product, ['hours_worked']);
$hours_worked = 0.0;
if($hours_raw !== null){
    if(strpos($hours_raw, ':') !== false){
        list($h,$m) = explode(':',$hours_raw);
        $hours_worked = ((int)$h) + ((int)$m / 60);
    }else{
        $hours_worked = (float)$hours_raw;
    }
}

// epoxy / terrazzo: amount_grams × price_per_gram
if($category === 'epoxy' || $category === 'terrazzo'){
    $amount = (float)field_or_null($product, ['amount_grams','amount_gram']);
    $ppg    = (float)field_or_null($product, ['price_per_gram']);
    $base_cost = $amount * $ppg;
    $amount_grams_for_hours = $amount;
}
// kaars: paraffine + stearine
elseif($category === 'kaars'){
    $paraffin_grams = (float)field_or_null($product, ['amount_paraffin_grams']);
    $stearin_grams  = (float)field_or_null($product, ['amount_stearin_grams']);

    $paraffin = $paraffin_grams * (float)field_or_null($product, ['price_per_gram_paraffin']);
    $stearin  = $stearin_grams  * (float)field_or_null($product, ['price_per_gram_stearin']);

    $base_cost = $paraffin + $stearin;
    $amount_grams_for_hours = $paraffin_grams + $stearin_grams;
}

$base_cost = round($base_cost, 2);

// ===== EXTRA KOSTEN =====
$extra_parts  = (float)field_or_null($product, ['extra_parts_price']) ?: 0.0;
$company_cost = (float)field_or_null($product, ['company_cost_per_product']) ?: 0.0;

// ===== UURLOON OP BASIS VAN GRAM =====
$hourly_rate = 0.0;
if($amount_grams_for_hours > 0){
    if($amount_grams_for_hours < 500){
        $hourly_rate = 2.5;
    }elseif($amount_grams_for_hours < 1000){
        $hourly_rate = 5.0;
    }elseif($amount_grams_for_hours < 2000){
        $hourly_rate = 8.0;
    }else{
        $hourly_rate = 10.0;
    }
}

$hours_cost = round($hours_worked * $hourly_rate, 2);

// Totale kostprijs excl. btw
$cost_ex_vat = round($base_cost + $extra_parts + $company_cost + $hours_cost, 2);

// ===== WINST & PARTNERPRIJS =====
$pricePartner = null;

if($isPartner && !$isBlocked && $price_ex_vat > 0 && $cost_ex_vat > 0){
    // totale winst excl. btw
    $total_profit_ex_vat = max(0, $price_ex_vat - $cost_ex_vat);

    // partner krijgt deel van de winst (1 / $percentageuitvaart, bv. helft)
    $partner_profit_ex_vat = round($total_profit_ex_vat / $percentageuitvaart, 2);

    // partnerprijs excl. btw = kost + partnerwinst
    $partner_price_ex_vat = round($cost_ex_vat + $partner_profit_ex_vat, 2);

    // NL: ex. btw, BE/overig: incl. btw
    if(str_starts_with($btwNummer,'NL')){
        $btwLabel  = 'excl. btw';
        $partnerRaw = $partner_price_ex_vat;
    }else{
        $btwLabel  = 'incl. btw';
        $partnerRaw = round($partner_price_ex_vat * (1 + $vat), 2);
    }

    // Veiligheidslijn: partner mag nooit duurder zijn dan particulier


    $pricePartner = number_format($partnerRaw,2,',','.');
}


                ?> 
                <?php $bestelUrl = "/pages/product.php?type=".$type."&product=".$id; ?>
                    <div class="product-card">
                        <a class="img-link" href="<?php echo $bestelUrl; ?>" aria-label="Bestel: <?php echo $name; ?>">
                            <img 
                                src="https://medewerkers.windelsgreen-decoresin.com/winkel/product_img/<?php echo $img; ?>" 
                                alt="<?php echo $name; ?>" 
                                loading="lazy" 
                                decoding="async"
                                width="600" height="400"
                            >
                        </a>
                        <h3><?php echo $name; ?></h3>
                        <p><?php echo $desc; ?></p>
                        <?php if($isPartner && !$isBlocked): ?>
                        <p class="price">
                            <strong>
                            <hr>
                            <span class="price-particulier">
                                Particulier <small>(incl. btw)</small><br>
                                <sup>€</sup><?= $priceParticulier ?>
                            </span>
                            <hr>
                            <span class="price-partner">
                                Partner <small>(<?= $btwLabel ?>)</small><br>
                                <sup>€</sup><?= $pricePartner ?>
                                <hr>
                            </span>
                            
                            </strong>
                        </p>
                        <?php else: ?>
                        <p class="price">
                            <strong><sup>€</sup> <?php echo $priceParticulier; ?></strong>
                        </p>
                        <?php endif; ?>
                        <a href="pages/product.php?type=<?php echo $type; ?>&amp;product=<?php echo $id; ?>" class="btn" aria-label="Meer info over <?php echo $name; ?>">Meer info</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="centered">
            <a href="pages/assortiment.php" class="btn-secondary">Bekijk alle producten</a>
        </div>
    </div>
</section>
<?php if(empty($_SESSION['partner_id'] ?? null)): ?>
    <section class="partner-strip">
  <div class="container">
    <div class="partner-card">
      <div class="partner-badge">
        Voor uitvaartondernemingen
      </div>

      <div class="partner-main">
        <div class="partner-copy">
          <h2>Uitvaartdienst? Word partner bij Windels</h2>
          <p>
            Bestel eenvoudig hoogwaardige herinneringsproducten met asverwerking,
            rechtstreeks via ons online partnerportaal.
          </p>
          <ul>
            <li>Partnerprijzen en maatwerk per familie</li>
            <li>Overzicht van bestellingen & facturen op één plek</li>
            <li>Persoonlijke ondersteuning en discrete afhandeling</li>
          </ul>
        </div>

        <div class="partner-side">
          <div class="partner-highlight">
            <span class="label">Binnen 2 minuten geregeld</span>
            <span class="label">En binnen 24 uur Actief</span>
            <span class="value">Gratis registratie</span>
          </div>
          <a href="/pages/account/registratie.php" class="partner-btn">
            Registreer uw uitvaartdienst
          </a>
          <small>Geen opstartkosten & geen verplichtingen</small>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
<section class="contact-section" aria-labelledby="contact-title">
    <div class="container">
        <h2 id="contact-title">Contact &amp; Over ons</h2>
        <p>Wij helpen u graag verder. Neem contact met ons op voor vragen of advies.</p>
        <a href="pages/contact/contact.php" class="btn">Neem contact op</a>
    </div>
</section>
<style>
    .price-partner{display:none}
html[data-price-mode="partner"] .price-particulier{display:inline}
html[data-price-mode="partner"] .price-partner{display:inline}

</style>
<script>
(()=>{"use strict";
const slider=document.querySelector(".reviews-slider");if(!slider)return;
const vp=slider.querySelector(".viewport"),track=slider.querySelector(".track"),cards=[...track.children];
const prev=slider.querySelector(".prev"),next=slider.querySelector(".next");
let autoplayMs=6000,autoplayId=null,step=1,isHover=false;

/* card fade-in on intersect */
const io=new IntersectionObserver(es=>{es.forEach(e=>{if(e.isIntersecting)e.target.classList.add("in")})},{root:vp,threshold:.6});
cards.forEach(c=>io.observe(c));

/* helpers */
const gap=()=>parseFloat(getComputedStyle(track).gap||getComputedStyle(track).columnGap||"16")||16;
const colW=()=>track.firstElementChild.getBoundingClientRect().width+gap();
const scrollByCards=(dir=1)=>vp.scrollBy({left:dir*colW()*step,behavior:"smooth"});
const currentIndex=()=>Math.round(vp.scrollLeft/colW());
const lastIndex=()=>Math.max(0,cards.length-step);

/* autoplay */
const start=()=>{if(autoplayId||isHover)return;autoplayId=setInterval(()=>{currentIndex()>=lastIndex()?vp.scrollTo({left:0,behavior:"smooth"}):scrollByCards(1)},autoplayMs)};
const stop=()=>{clearInterval(autoplayId);autoplayId=null};

/* bind knoppen alleen als ze bestaan */
if(prev)prev.addEventListener("click",()=>{stop();scrollByCards(-1);start()});
if(next)next.addEventListener("click",()=>{stop();scrollByCards(1);start()});

/* hover/pagina focus */
vp.addEventListener("mouseenter",()=>{isHover=true;stop()});
vp.addEventListener("mouseleave",()=>{isHover=false;start()});
document.addEventListener("visibilitychange",()=>document.hidden?stop():start());

/* keyboard */
vp.addEventListener("keydown",e=>{if(e.key==="ArrowLeft"){e.preventDefault();prev&&prev.click()}else if(e.key==="ArrowRight"){e.preventDefault();next&&next.click()}});

/* (nieuw) muiswiel -> horizontaal scrollen op desktop */
vp.addEventListener("wheel",e=>{const vert=Math.abs(e.deltaY)>Math.abs(e.deltaX);if(vert){e.preventDefault();vp.scrollBy({left:e.deltaY,behavior:"smooth"})}},{passive:false});

/* responsive step */
const updateStep=()=>{step=Math.max(1,Math.round(vp.clientWidth/colW()))};
new ResizeObserver(updateStep).observe(vp);updateStep();

/* init */
start();
})();
</script>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
