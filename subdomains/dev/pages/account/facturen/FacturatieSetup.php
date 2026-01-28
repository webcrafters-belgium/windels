<?php
// FacturatieSetup.php – instellingen voor uitvaartpartners (mysqli)

include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
session_start();
if(!isset($_SESSION['partner_id'])){  header("Location: /pages/account/login.php"); exit; }
$partnerId=(int)$_SESSION['partner_id'];

if(empty($_SESSION['csrf_token'])){ $_SESSION['csrf_token']=bin2hex(random_bytes(32)); }
$csrfToken=$_SESSION['csrf_token'];

$billing_weekday=null;
$billing_month_day=null;
$billing_recipient=null;
$billing_preference=null;

// Huidige instellingen ophalen
if($stmt=$mysqli->prepare('SELECT btw_nummer, billing_preference, billing_recipient, billing_weekday, billing_month_day FROM funeral_partners WHERE id=? LIMIT 1')){
  $stmt->bind_param('i',$partnerId);
  if($stmt->execute()){
    $stmt->bind_result($btw,$bp,$br,$bw,$bm);
    if($stmt->fetch()){
      $billing_preference = $bp ?: null;
      $billing_recipient  = $br ?: null;
      $billing_weekday    = $bw!==null?(int)$bw:null;
      $billing_month_day  = $bm!==null?(int)$bm:null;
    }
  }
  $stmt->close();
}

// Defaults als er nog niets ingesteld is
$billing_preference = $billing_preference ?: 'per_order';
$billing_recipient  = $billing_recipient  ?? 'partner';

$errors=[];$success=false;

if($_SERVER['REQUEST_METHOD']==='POST'){
  if(!isset($_POST['csrf'])||!hash_equals($csrfToken,$_POST['csrf'])){ $errors[]='Ongeldige sessie. Probeer opnieuw.'; }

  // Ruwe input
  $choice    = $_POST['billing_preference'] ?? 'per_order';
  $recipient = $_POST['billing_recipient']  ?? 'partner';

  $allowed=['per_order','weekly','monthly'];
  if(!in_array($choice,$allowed,true)){$errors[]='Ongeldige keuze voor facturatie.';}

  $wday=isset($_POST['billing_weekday'])?(int)$_POST['billing_weekday']:$billing_weekday;
  if($choice==='weekly' && ($wday<1||$wday>7)){$errors[]='Kies een geldige weekdag.';}

  $mday=isset($_POST['billing_month_day'])?(int)$_POST['billing_month_day']:$billing_month_day;
  if($choice==='monthly' && ($mday<1 || $mday>5)){ $errors[]='Kies een geldige dag (1 t.e.m. 5).'; }

  $allowedRecipients=['partner','customer'];
  if(!in_array($recipient,$allowedRecipients,true)){$errors[]='Ongeldige keuze voor ontvanger van de factuur.';}

  if(!$errors){
    $pid=$partnerId;

    if($choice==='per_order'){
      $stmt=$mysqli->prepare('UPDATE funeral_partners SET billing_preference=?, billing_recipient=?, billing_weekday=NULL, billing_month_day=NULL WHERE id=? LIMIT 1');
      $stmt->bind_param('ssi', $choice, $recipient, $pid);
    }elseif($choice==='weekly'){
      $stmt=$mysqli->prepare('UPDATE funeral_partners SET billing_preference=?, billing_recipient=?, billing_weekday=?, billing_month_day=NULL WHERE id=? LIMIT 1');
      $stmt->bind_param('ssii', $choice, $recipient, $wday, $pid);
    }else{ // monthly
      $stmt=$mysqli->prepare('UPDATE funeral_partners SET billing_preference=?, billing_recipient=?, billing_weekday=NULL, billing_month_day=? WHERE id=? LIMIT 1');
      $stmt->bind_param('ssii', $choice, $recipient, $mday, $pid);
    }

    $success=false;
    if($stmt){
      $success=$stmt->execute();
      $stmt->close();
      if($success){
        $billing_preference=$choice;
        $billing_recipient=$recipient;
        $billing_weekday=$choice==='weekly'?$wday:null;
        $billing_month_day=$choice==='monthly'?$mday:null;
        $_SESSION['flash_success']='Je facturatievoorkeuren zijn opgeslagen.';
        header('Location: '.$_SERVER['REQUEST_URI']); exit;
      }
    }else{
      $errors[]='Databasefout bij voorbereiden van update.';
    }
  }
}

function h($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
$flashSuccess=$_SESSION['flash_success']??null; unset($_SESSION['flash_success']);
?>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>

<style>
body{background:url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;background-size:cover;}
.dashboard-page{background-color:rgba(255,255,255,.9);padding:3rem 2rem;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);margin:3rem auto 2rem;}
.info-box{background:#f5f5f5;border-left:4px solid #5a7d5a;padding:1rem 1.5rem;margin:1.5rem 0;font-size:1rem;color:#333;border-radius:.5rem;box-shadow:0 1px 3px rgba(0,0,0,.05);}
.filter-card{background:#fff;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.06);padding:1rem 1rem 1.25rem;margin:1rem 0 1.5rem;border:1px solid #f0f0f0}
.filter-title{font-size:1.1rem;font-weight:700;color:#2e2e2e;margin:.25rem 0 1rem}
.filter-grid{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:.75rem}
@media(max-width:1100px){.filter-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media(max-width:680px){.filter-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
.ctrl{display:flex;flex-direction:column}
.ctrl label{font-weight:600;color:#2f3b2f;margin-bottom:.35rem}
.ctrl input[type="text"],.ctrl input[type="number"],.ctrl input[type="email"],.ctrl input[type="password"],.ctrl input[type="search"],.ctrl input[type="tel"],.ctrl select{appearance:none;border:1px solid #e6e6e6;border-radius:.65rem;padding:.6rem .75rem;background:#fafafa;color:#222;transition:border-color .15s,box-shadow .15s,background .15s}
.ctrl input[type="text"]:focus,.ctrl input[type="number"]:focus,.ctrl input[type="email"]:focus,.ctrl input[type="password"]:focus,.ctrl input[type="search"]:focus,.ctrl input[type="tel"]:focus,.ctrl select:focus{outline:0;border-color:#5a7d5a;box-shadow:0 0 0 3px rgba(90,125,90,.18);background:#fff}
.ctrl input[type="radio"]{appearance:auto;accent-color:#5a7d5a;width:1rem;height:1rem;margin-right:.4rem;}
.ctrl input:focus,.ctrl select:focus{outline:0;border-color:#5a7d5a;box-shadow:0 0 0 3px rgba(90,125,90,.18);background:#fff}
.ctrl small{color:#666;margin-top:.25rem}
.btn{display:inline-flex;align-items:center;justify-content:center;padding:.6rem 1rem;border-radius:.65rem;text-decoration:none;cursor:pointer;transition:transform .05s,box-shadow .15s}
.btn-primary{background:#5a7d5a;color:#fff;border:0}
.btn-primary:hover{box-shadow:0 6px 16px rgba(90,125,90,.18)}
.btn-primary:active{transform:translateY(1px)}
.btn-ghost{background:#fff;border:1px solid #dcdcdc;color:#2f3b2f}
.btn-ghost:hover{box-shadow:0 6px 16px rgba(90,125,90,.18);color:#fff;background:#5a7d5a;border-color:#5a7d5a}
.btn-ghost:active{transform:translateY(1px)}
.btn[disabled],.btn-primary[disabled],.btn-ghost[disabled]{opacity:.5;pointer-events:none}
.btn-row{display:flex;gap:.6rem;align-items:flex-end;flex-wrap:wrap}
.badge{display:inline-block;background:#eef4ee;color:#2f3b2f;border:1px solid #dbe7db;border-radius:999px;padding:.2rem .6rem;font-size:.8rem;margin-left:.5rem}
.hint{display:flex;align-items:center;gap:.5rem;margin:.5rem 0 0;color:#4a5a4a;font-size:.9rem}
.hint:before{content:"ⓘ";font-weight:700}
.hidden{display:none!important}
</style>

<main class="dashboard-page container">
  <h1>Facturatie-instellingen <span class="badge">Uitvaartdienst</span></h1>
  <p class="hint">
    Hier bepaal je wie de factuur ontvangt en hoe vaak we factureren.
    Kies je voor <strong>eindklant</strong>, dan verschijnen facturen niet in jouw overzicht onder <strong>Mijn Facturen</strong>.
  </p>

  <?php if($flashSuccess): ?>
    <div class="info-box"><?=h($flashSuccess)?></div>
  <?php endif; ?>
  <?php if($errors): ?>
    <div class="info-box" style="border-left-color:#c62828;color:#c62828"><?php echo implode('<br>', array_map('h',$errors)); ?></div>
  <?php endif; ?>
  <?php
$prefLabels=['per_order'=>'Per order','weekly'=>'Wekelijks','monthly'=>'Maandelijks'];
$recpLabels=['partner'=>'Uitvaartdienst','customer'=>'Eindklant'];

$prefLabel = $prefLabels[$billing_preference] ?? 'Nog niet ingesteld';
$recpLabel = $recpLabels[$billing_recipient] ?? 'Nog niet ingesteld';
?>
<div class="info-box">
  <strong>Huidige instellingen</strong><br>
  Factuur naar: <strong><?= h($recpLabel) ?></strong><br>
  Facturatievoorkeur: <strong><?= h($prefLabel) ?></strong>
  <?php if($billing_preference==='weekly' && $billing_weekday): ?>
    <br>Weekdag: <strong><?= (int)$billing_weekday ?></strong>
  <?php elseif($billing_preference==='monthly' && $billing_month_day): ?>
    <br>Dag van de maand: <strong><?= (int)$billing_month_day ?></strong>
  <?php endif; ?>
</div>

  <form method="post" action="">
    <input type="hidden" name="csrf" value="<?=h($csrfToken)?>">

    <fieldset style="border:0;padding:0;margin:0">
      <div class="filter-card">
        <div class="filter-title">Wie ontvangt de factuur?</div>

        <!-- Factuur naar -->
        <div class="ctrl" style="margin-bottom:.75rem">
          <label style="margin-bottom:.5rem">Factuur naar</label>
          <div class="inline-options" style="display:flex;gap:2rem;align-items:center;flex-wrap:wrap">
            <label style="display:flex;align-items:center;gap:.4rem;margin:0">
              <input type="radio" name="billing_recipient" value="partner"
                <?= ($billing_recipient==='partner'?'checked':''); ?>>
              Uitvaartdienst (partner)
            </label>

            <label style="display:flex;align-items:center;gap:.4rem;margin:0">
              <input type="radio" name="billing_recipient" value="customer"
                <?= ($billing_recipient==='customer'?'checked':''); ?>>
              Eindklant (nabestaande)
            </label>
          </div>

          <small id="recipientText">
          <?= $billing_recipient==='customer'
    ? 'De factuur wordt verzonden naar de nabestaande. Deze facturen verschijnen niet in jouw overzicht “Mijn facturen” in het dashboard.'
    : 'De factuur wordt rechtstreeks naar jou als uitvaartdienst gestuurd én is zichtbaar in “Mijn facturen”.' ?>
          </small>
        </div>

        <hr style="border:0;border-top:1px solid #f0f0f0;margin:.75rem 0">

        <!-- Facturatievoorkeur -->
        <div class="filter-title" style="margin-top:.25rem;">Hoe vaak wil je een factuur?</div>
        <div class="ctrl">
          <div class="inline-options" style="display:flex;gap:2rem;align-items:center;flex-wrap:wrap">
            <label style="display:flex;align-items:center;gap:.4rem;margin:0">
              <input type="radio" name="billing_preference" value="per_order" 
                <?= ($billing_preference==='per_order'?'checked':''); ?>>
              Per order
            </label>
            <label style="display:flex;align-items:center;gap:.4rem;margin:0">
              <input type="radio" name="billing_preference" value="weekly"
                <?= ($billing_preference==='weekly'?'checked':''); ?>>
              Wekelijks
            </label>
            <label style="display:flex;align-items:center;gap:.4rem;margin:0">
              <input type="radio" name="billing_preference" value="monthly"
                <?= ($billing_preference==='monthly'?'checked':''); ?>>
              Maandelijks
            </label>
          </div>
          <small>
            Bij <strong>per order</strong> ontvang je voor elke bestelling apart een factuur.
            Bij <strong>wekelijks</strong> of <strong>maandelijks</strong> bundelen we bestellingen tot één factuur.
          </small>
        </div>
      </div>

      <div id="weeklyFields" class="filter-card <?= $billing_preference==='weekly'?'':'hidden' ?>">
        <div class="ctrl">
          <label>Weekdag voor factuur</label>
          <select name="billing_weekday">
            <?php
            $days=[1=>'Maandag',2=>'Dinsdag',3=>'Woensdag',4=>'Donderdag',5=>'Vrijdag',6=>'Zaterdag'];
            foreach($days as $val=>$label){
              $sel=$billing_weekday===$val?'selected':'';
              echo '<option value="'.(int)$val.'" '.$sel.'>'.h($label).'</option>';
            }
            ?>
          </select>
          <small>We verzamelen bestellingen tot en met deze dag en maken dan één factuur.</small>
        </div>
      </div>

      <div id="monthlyFields" class="filter-card <?= $billing_preference==='monthly'?'':'hidden' ?>">
        <div class="ctrl">
          <label>Dag van de maand</label>
          <select name="billing_month_day">
            <?php
            for($i=1;$i<=5;$i++){
              $sel=$billing_month_day===$i?'selected':'';
              echo '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';
            }
            ?>
          </select>
          <small>Je kan kiezen tussen de 1ste en de 5de van de maand voor het opmaken van de factuur.</small>
        </div>
      </div>
    </fieldset>

    <div class="btn-row"> 
      <button type="submit" class="btn btn-primary">Opslaan</button>
      <button type="button" class="btn btn-ghost" onclick="history.back()">Annuleren</button>
      <a href="index.php" class="btn btn-ghost">Terug</a>
    </div>
  </form>
</main>

<script>
document.querySelectorAll('input[name="billing_recipient"]').forEach(function(radio){
  radio.addEventListener('change', function(){
    const text = document.getElementById('recipientText');
    if(this.value === 'customer'){
      text.textContent = 'De factuur wordt verzonden naar de nabestaande. Deze facturen verschijnen niet in jouw overzicht “Mijn facturen” in het dashboard.';
    } else {
      text.textContent = 'De factuur wordt rechtstreeks naar jou als uitvaartdienst gestuurd én is zichtbaar in “Mijn facturen”.';
    }
  });
});

(function(){
  // Toon/verberg week/maand-velden
  var weekly  = document.getElementById('weeklyFields');
  var monthly = document.getElementById('monthlyFields');
  var radios  = document.querySelectorAll('input[name="billing_preference"]');
  function toggle(){
    var checked = document.querySelector('input[name="billing_preference"]:checked');
    var val = checked ? checked.value : 'per_order';
    if(weekly){ weekly.classList.toggle('hidden', val !== 'weekly'); }
    if(monthly){ monthly.classList.toggle('hidden', val !== 'monthly'); }
  }
  radios.forEach(function(r){ r.addEventListener('change', toggle); });
  if(document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', toggle);
  }else{ toggle(); }
})();
</script>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
