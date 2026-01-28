<?php 
require_once dirname($_SERVER['DOCUMENT_ROOT'])."/secure/ini.inc"; 
require_once dirname($_SERVER['DOCUMENT_ROOT'])."/partials/header.php"; 

?>
<style>
.cart-page-track{background-color:rgba(255,255,255,.92);padding:24px;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);margin:32px auto 24px;max-width:900px;width:92%;box-sizing:border-box}
h1.track{font-size:22px;margin:0 0 10px}
p.trackp{margin:0 0 16px}
label.tracklabel{display:block;font-weight:600;margin:10px 0 6px}
input.trackinput{width:100%;padding:12px 14px;border:1px solid #cfd8cf;border-radius:12px;outline:0}
button.trackbutton{margin-top:16px;width:100%;padding:12px 16px;border:0;border-radius:14px;cursor:pointer;background:#1f4d35;color:#fff;font-weight:700}
button.trackbutton:hover{filter:brightness(1.04)}
.small{font-size:12px;color:#406a50;margin-top:8px}
</style>
<main class="overons-hero">
    <div class="overons-container">
        <h1>Track & Trace</h1>
        <p class="subtitel">Vul hier je ordernummer en e-mailadres in om je bestelling te raadplegen.</p>
    </div>
</main>

<section class="overons-inhoud">
    <div class="container">
    <div class="cart-page-track">
    <h1 class="track">Bestelling opvolgen</h1>
    <p class="trackp">Vul je e-mailadres en ordernummer in om de status te bekijken.</p>
    <form method="get" action="order-status.php" autocomplete="on">
        <label class="tracklabel" for="email">E-mailadres</label>
        <input class="trackinput id="email" name="email" type="email" required>
        <label class="tracklabel"  for="ordernr">Ordernummer</label>
        <input class="trackinput" id="ordernr" name="order" type="text" required>
        <button class="trackbutton" type="submit">Toon bestelling</button>
    </form>
    </div>
    </div>
</section>
<?php require_once dirname($_SERVER['DOCUMENT_ROOT'])."/partials/footer.php"; ?>