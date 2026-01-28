<?php
include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';

function highlightZoek($text,$zoekterm){
    $text=$text??'';
    if(trim($zoekterm)===''){return htmlspecialchars($text,ENT_QUOTES,'UTF-8');}
    $escaped=htmlspecialchars($text,ENT_QUOTES,'UTF-8');
    $woorden=preg_split('/\s+/',trim($zoekterm));
    foreach($woorden as $w){
        $w=trim($w);
        if($w===''){continue;}
        $pattern='/('.preg_quote(htmlspecialchars($w,ENT_QUOTES,'UTF-8'),'/').')/i';
        $escaped=preg_replace($pattern,'<mark>$1</mark>',$escaped);
    }
    return $escaped;
}

$zoekterm=trim($_GET['zoek']??'');
$diensten=[];

$sql="SELECT 
    fp.id,
    fp.bedrijf_naam,
    fp.contact_naam,
    fp.adres,
    fp.telefoon,
    fp.email,
    fp.is_actief,
    fpi.profile_image
FROM funeral_partners fp
LEFT JOIN funeral_partner_info fpi ON fpi.partner_id=fp.id
WHERE fp.is_actief=1";

$filters=[];
$params=[];
$types='';

if($zoekterm!==''){
    $woorden=preg_split('/\s+/',$zoekterm);
    foreach($woorden as $woord){
        $woord=trim($woord);
        if($woord===''){continue;}
        $filters[]="(fp.bedrijf_naam LIKE ? OR fp.adres LIKE ? OR fp.contact_naam LIKE ?)";
        $like='%'.$woord.'%';
        $params[]=$like;
        $params[]=$like;
        $params[]=$like;
        $types.='sss';
    }
    if(!empty($filters)){
        $sql.=" AND (".implode(' OR ',$filters).")";
    }
}
$sql.=" ORDER BY fp.bedrijf_naam ASC";

$stmt=$mysqli->prepare($sql);
if($stmt===false){
    die('Er ging iets mis bij het laden van de uitvaartdiensten.');
}
if(!empty($params)){
    $stmt->bind_param($types,...$params);
}
$stmt->execute();
$res=$stmt->get_result();
while($row=$res->fetch_assoc()){
    $diensten[]=$row;
}
$stmt->close();

/**
 * Normaliseer tekst naar lijst met “belangrijke” tokens
 */
function normalizeTokens($str){
    $str=mb_strtolower(trim($str??''),'UTF-8');
    if($str===''){return [];}

    $str=str_replace(['&','/','\\','-','.',';',','], ' ', $str);
    $parts=preg_split('/\s+/u',$str);

    // generieke woorden die we willen negeren
    $stop=[
        'bv','bvba','nv','cv','vof','sprl','sa',
        'en','de','het','van','voor','onderneming','bedrijf',
        'assistentie','uitvaart','uitvaartzorg','uitvaartcentrum',
        'uitvaartvervoer','centrum','zorg','groep','b.v.'
    ];

    $tokens=[];
    foreach($parts as $p){
        $p=trim($p);
        if($p==='' || mb_strlen($p,'UTF-8')<3){continue;}
        if(in_array($p,$stop,true)){continue;}
        $tokens[]=$p;
    }
    return array_values(array_unique($tokens));
}

/**
 * Bepaal het “belangrijkste” unieke deel van de naam (meestal laatste niet-generieke token).
 */
function getPrimaryToken(array $tokens){
    if(empty($tokens)){return '';}

    // van achter naar voor zoeken naar iets wat niet op 'uitvaart' / 'assistentie' lijkt
    for($i=count($tokens)-1;$i>=0;$i--){
        $t=$tokens[$i];
        if(str_contains($t,'uitvaart') || str_contains($t,'assistentie')){
            continue;
        }
        return $t;
    }

    // als alles generiek is, neem laatste token
    return end($tokens);
}

/**
 * Checkt via Places Text Search of er minstens één resultaat is
 * waarvan de naam het primaire (unieke) token van de handelsnaam bevat.
 *
 * - "Windels green & deco resin"  → primary bv. "resin" of "windels" → match
 * - "Uitvaartvervoer & Uitvaartassistentie Lucassen"  → primary "lucassen"
 *   komt NIET in resultaten → FALSE
 */
function tradeNameExistsOnGoogle($name,$apiKey){
    $name=trim($name??'');
    if($name===''){return false;}

    $inputTokens=normalizeTokens($name);
    if(empty($inputTokens)){return false;}

    $primary=getPrimaryToken($inputTokens);
    if($primary===''){return false;}

    $url="https://maps.googleapis.com/maps/api/place/textsearch/json"
        ."?query=".urlencode($name)
        ."&key=".$apiKey;

    $ch=curl_init($url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $result=curl_exec($ch);
    curl_close($ch);

    if(!$result){return false;}

    $data=json_decode($result,true);

    if(!isset($data['status']) || $data['status']!=='OK'){return false;}
    if(empty($data['results'])){return false;}

    foreach($data['results'] as $place){
        if(empty($place['name'])){continue;}
        $placeTokens=normalizeTokens($place['name']);
        if(empty($placeTokens)){continue;}

        if(in_array($primary,$placeTokens,true)){
            // alleen match als het unieke deel (bv. familienaam) terugkomt
            return true;
        }
    }

    return false;
}

/**
 * Bouwt de Google Maps query:
 * - als Google de handelsnaam echt herkent → naam + adres
 * - anders → alleen adres
 */
function mapsQuerySmart($name,$address,$apiKey){
    $name=trim($name??'');
    $address=trim($address??'');

    if($address===''){return urlencode($name);}
    if($name!=='' && tradeNameExistsOnGoogle($name,$apiKey)){
        return urlencode(trim($name.' '.$address));
    }
    return urlencode($address);
}

$apiKey=GOOGLE_MAPS_API_KEY;
?>

<style>
.uitvaart-container{padding:3rem 0}
.uitvaart-container .search-bar{text-align:center;margin-bottom:2rem}
.uitvaart-container input[type="text"]{padding:.5rem 1rem;border:1px solid #ccc;border-radius:25px;width:300px;max-width:100%;font-size:1rem}
.uitvaart-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem}
.uitvaart-card{background:url('/img/uitvaartachtergrond.jpg') no-repeat center center/cover;color:#f5f5f5;border-radius:18px;padding:2rem;position:relative;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,.08);display:flex;flex-direction:column;justify-content:space-between;height:100%;min-height:320px;transition:transform .3s ease}
.uitvaart-card:hover{transform:translateY(-4px)}
.uitvaart-card::before{content:'';position:absolute;inset:0;background:linear-gradient(to bottom,rgba(0,0,0,.2),rgba(0,0,0,.6));z-index:0;border-radius:18px}
.uitvaart-card>*{position:relative;z-index:1}
.uitvaart-card h3{font-size:1.4rem;margin-bottom:.5rem;color:#fff;text-shadow:0 1px 3px rgba(0,0,0,.4)}
.uitvaart-card p{margin:.25rem 0;font-size:1rem;color:#f0f0f0;text-shadow:0 1px 2px rgba(0,0,0,.4)}
.uitvaart-card a.mail{color:#aad2f0;text-decoration:underline;font-weight:500}
.uitvaart-card .btn{background-color:#fff;color:#1e4025;padding:.5rem 1.2rem;border-radius:30px;text-align:center;font-weight:600;text-decoration:none;margin-top:1rem;transition:background-color .3s ease;display:inline-block;border:none;cursor:pointer}
.uitvaart-card .btn:hover{background-color:#d9f0e3}
.uitvaart-reset-btn{margin-left:10px;border-radius:30px;background-color:#1e4025;color:#fff;padding:.5rem 1.2rem;text-decoration:none;font-weight:600;display:inline-block;border:none;cursor:pointer}
.uitvaart-reset-btn:hover{background-color:#245332}
.uitvaart-empty{text-align:center;margin-top:2rem}
.uitvaart-result-info{text-align:center;margin-bottom:1.5rem;font-size:.95rem;opacity:.9}
.uitvaart-logo img{max-width:120px;max-height:60px;object-fit:contain;margin-bottom:.75rem;filter:drop-shadow(0 1px 2px rgba(0,0,0,.5))}
.uitvaart-card-actions{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:1rem}
.uitvaart-route-btn{background-color:rgba(255,255,255,.9)}
.uitvaart-route-btn:hover{background-color:#d9f0e3}
mark{background:rgba(255,255,255,.35);padding:0 .1em;border-radius:2px}
</style>

<main class="hero" id="top">
  <section class="hero-content">
    <h1>Onze uitvaartpartners</h1>
    <p>Onderstaande uitvaartdiensten werken samen met ons. Je kan hen contacteren om een bestelling door te geven.</p>
  </section>
</main>

<main class="uitvaart-container">
  <div class="container">
    <form method="get" class="search-bar" aria-label="Zoek uitvaartdienst">
      <input type="text" name="zoek" placeholder="Zoek op naam, gemeente of adres..." value="<?=htmlspecialchars($zoekterm,ENT_QUOTES,'UTF-8')?>">
      <?php if($zoekterm!==''):?>
        <a href="contacteer-uitvaartdienst.php" class="uitvaart-reset-btn">Reset</a>
      <?php endif;?>
    </form>

    <?php if(count($diensten)>0):?>
      <p class="uitvaart-result-info">
        <?=count($diensten)===1?'1 uitvaartpartner gevonden':count($diensten).' uitvaartpartners gevonden'?>
        <?=$zoekterm!==''?' voor: “'.htmlspecialchars($zoekterm,ENT_QUOTES,'UTF-8').'”':''?>
      </p>
      <div class="uitvaart-grid">
      <?php foreach($diensten as $d):?>
        <div class="uitvaart-card">
          <?php if(!empty($d['profile_image'])):?>
            <div class="uitvaart-logo">
              <img src="/uploads/partners/<?=htmlspecialchars($d['profile_image'],ENT_QUOTES,'UTF-8')?>" alt="Profielafbeelding <?=htmlspecialchars($d['bedrijf_naam']??'',ENT_QUOTES,'UTF-8')?>">
            </div>
          <?php endif;?>
          <div>
            <h3><?=highlightZoek($d['bedrijf_naam']??'',$zoekterm)?></h3>
            <p><strong>Contact:</strong> <?=highlightZoek($d['contact_naam']??'',$zoekterm)?></p>
            <p><strong>Adres:</strong> <?=nl2br(highlightZoek($d['adres']??'',$zoekterm))?></p>
            <?php if($d['telefoon']):?>
              <p><strong>Tel:</strong> <a href="tel:<?=htmlspecialchars($d['telefoon'],ENT_QUOTES,'UTF-8')?>" style="color:#f0f0f0;text-decoration:underline"><?=htmlspecialchars($d['telefoon']??'')?></a></p>
            <?php endif;?>
            <p><strong>E-mail:</strong>
              <a class="mail" href="mailto:<?=htmlspecialchars($d['email'],ENT_QUOTES,'UTF-8')?>">
                <?=htmlspecialchars($d['email']??'')?>
              </a>
            </p>
          </div>
          <div class="uitvaart-card-actions">
            <a href="partner.php?id=<?=intval($d['id'])?>" class="btn">Meer info</a>
            <?php
              $mapsQuery=mapsQuerySmart(
                  $d['bedrijf_naam']??'',
                  $d['adres']??'',
                  $apiKey
              );
            ?>
            <a href="https://www.google.com/maps/search/?api=1&query=<?=$mapsQuery?>" target="_blank" rel="noopener" class="btn uitvaart-route-btn">Route</a>
          </div>
        </div>
      <?php endforeach;?>
      </div>
    <?php else:?>
      <p class="uitvaart-empty">
        Geen uitvaartpartners gevonden<?=$zoekterm!==''?' voor “'.htmlspecialchars($zoekterm,ENT_QUOTES,'UTF-8').'”':''?>.<br>
        Neem gerust <a href="/contact.php">contact</a> op, dan helpen we je persoonlijk verder.
      </p>
    <?php endif;?>
  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded',function(){
  var zoekInput=document.querySelector('.search-bar input[name="zoek"]');
  if(zoekInput){
    var timer;
    zoekInput.addEventListener('input',function(){
      clearTimeout(timer);
      timer=setTimeout(function(){
        zoekInput.form.submit();
      },400);
    });
  }
});
</script>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
