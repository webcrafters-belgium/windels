<?php
http_response_code(503);
$retryAfterSeconds=900;header("Retry-After:$retryAfterSeconds");
header("Cache-Control:no-store, no-cache, must-revalidate, max-age=0");
header("Pragma:no-cache");

$contact_email="webshop@windelsgreen-decoresin.com";
$phone="+3211753319";
$brand="Windels green & deco resin | Uitvaartzorg";

@include_once dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';

$tz=new DateTimeZone('Europe/Brussels');
$eta=null;$start_display='';$end_display='';

if(isset($mysqli)&&$mysqli instanceof mysqli){
  if($st=$mysqli->prepare("SELECT start_at,end_at FROM maintenance_config WHERE enabled=1 ORDER BY start_at DESC LIMIT 1")){
    if($st->execute()){
      $st->bind_result($start_at,$end_at);
      if($st->fetch()){
        if($start_at){
          $s=new DateTime($start_at,$tz);
          $start_display=$s->format('d-m-Y H:i');
        }
        if($end_at){
          $e=new DateTime($end_at,$tz);
          $end_display=$e->format('d-m-Y H:i');
          $eta=$e->format('Y-m-d\TH:i:sP'); // ISO voor JS countdown
        }
      }
    }
    $st->close();
  }
}

/* Fallback: indien geen DB-waarde gevonden */
if(!$eta){
  $e=new DateTime('now',$tz);$e->modify('+15 minutes');
  $eta=$e->format('Y-m-d\TH:i:sP');
  if(!$start_display){$s=new DateTime('now',$tz);$start_display=$s->format('d-m-Y H:i');}
  if(!$end_display){$end_display=$e->format('d-m-Y H:i');}
}

/* Auto-terug als .maintenance > 15 min oud */
$maintenanceFile=__DIR__.'/.maintenance';
if(file_exists($maintenanceFile)){
  $created=filemtime($maintenanceFile);
  if(time()-$created>=900){
    @unlink($maintenanceFile);
    header("Location:/index.php");exit;
  }
}
?>
<!doctype html><html lang="nl"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Gepland onderhoud – <?=htmlspecialchars($brand)?></title>
<meta name="robots" content="noindex, nofollow">
<link rel="icon" href="/assets/logo/favicon.png">  
<link rel="preload" href="/assets/js/lottie.min.js" as="script">
<style>
:root{--bg:#f4f8f7;--card:#fff;--txt:#2f3b35;--muted:#5a6a63;--accent:#5F8575;--accent-2:#6fbf8a}
*{box-sizing:border-box}html,body{height:100%}body{margin:0;background:var(--bg);color:var(--txt);font:16px/1.5 system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,"Helvetica Neue",Arial}
.container{min-height:100%;display:flex;align-items:center;justify-content:center;padding:20px}
.card{width:100%;max-width:720px;background:var(--card);border:1px solid #e6ece9;border-radius:20px;box-shadow:0 6px 18px rgba(0,0,0,.06);padding:26px 22px;text-align:center;position:relative;overflow:hidden}
.logo{margin-bottom:10px}.logo img{max-height:56px;width:auto}
h1{font-size:clamp(20px,3.5vw,28px);margin:6px 0 8px}
.badge{display:inline-flex;align-items:center;gap:8px;background:#eaf5f1;border:1px solid #d3e9e0;color:#2f5f44;padding:4px 10px;border-radius:999px;font-size:12px;letter-spacing:.2px;margin-bottom:8px;white-space:nowrap}
.badge svg{width:14px;height:14px}
.lead{margin:8px 0;color:var(--muted)}
.window{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin:12px 0 6px}
.window .chip{display:inline-flex;align-items:center;gap:6px;border:1px solid #e1ece7;background:#f6fbf9;color:#365449;padding:6px 10px;border-radius:10px;font-size:14px}
.window time{font-weight:700}
.progress{height:8px;background:#f0f3f2;border-radius:999px;overflow:hidden;margin:14px 0;position:relative}
.progress::before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(95,133,117,.15),rgba(95,133,117,.7),rgba(95,133,117,.15));transform:translateX(-100%);animation:load 2.4s infinite}
@keyframes load{0%{transform:translateX(-100%)}50%{transform:translateX(0)}100%{transform:translateX(100%)}}
.timer{font-variant-numeric:tabular-nums;font-weight:600;margin-top:2px}
.actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap;margin-top:12px}
.btn{display:inline-block;padding:10px 14px;border-radius:12px;border:1px solid #cfe4da;background:#e8f4ef;color:#244a3a;text-decoration:none;transition:.2s}
.btn:hover{transform:translateY(-1px);background:#dff0e8}
.small{font-size:12px;opacity:.75}
.footer{margin-top:14px;color:#7a8a84}
.corner{position:absolute;top:10px;right:12px;color:#7a8a84;font-size:12px}
.corner time{font-weight:700}
.anim-tools{margin:10px auto 18px;display:flex;justify-content:center;align-items:center}
.anim-tools svg{width:120px;height:120px;display:block}
.anim-tools .tool{transform-origin:60px 60px;transform-box:fill-box}
.anim-tools .wrench{animation:wrenchMove 2.8s ease-in-out infinite}
.anim-tools .hammer{animation:hammerMove 2.8s ease-in-out infinite}
@keyframes wrenchMove{0%,100%{transform:rotate(22deg)}50%{transform:rotate(-12deg)}}
@keyframes hammerMove{0%,100%{transform:rotate(-22deg)}50%{transform:rotate(12deg)}}
@media (prefers-reduced-motion:reduce){.anim-tools .tool{animation:none}}
</style>
</head><body>
<div class="container"><main class="card" role="main" aria-labelledby="title">
  <div class="corner" aria-hidden="true">Ref: HTTP 503</div>
  <div class="logo"><img src="/img/logo.png" alt="<?=htmlspecialchars($brand)?>"></div>
  <span class="badge" aria-live="polite">
    <!-- wrench icon -->
    <svg viewBox="0 0 512 512" aria-hidden="true" focusable="false"><path fill="currentColor" d="M501.1 395.3 352.6 246.8c13.7-39.6 4-85-26.4-115.4-30.3-30.3-75.7-40.1-115.3-26.4l74.6 74.6c6.2 6.2 6.2 16.4 0 22.6L241.2 246c-6.2 6.2-16.4 6.2-22.6 0l-74.6-74.6c-13.7 39.6-4 85 26.4 115.3 30.4 30.4 75.8 40.1 115.4 26.4l148.5 148.5c14.9 14.9 39.1 14.9 54 0 14.9-14.9 14.9-39.1 0-54zM96 64c17.7 0 32 14.3 32 32 0 17.6-14.3 32-32 32-17.7 0-32-14.4-32-32s14.3-32 32-32z"/></svg>
    Gepland onderhoud
  </span>
  <div class="anim-tools" aria-hidden="true">
  <!-- Hamer + sleutel (moersleutel) in inline SVG, huisstijl-kleur #5F8575 -->
  <svg viewBox="0 0 120 120" role="img" aria-label="Onderhoud animatie: hamer en sleutel">
    <!-- Moersleutel -->
    <g class="tool wrench" fill="#5F8575">
      <!-- steel -->
      <rect x="28" y="56" width="56" height="8" rx="4"/>
      <!-- sleutelkop (C-vorm) -->
      <path d="M28,52 a12,12 0 1,1 0,16 l4,-3 0,-10 z"/>
      <!-- klein gat in de kop -->
      <circle cx="32" cy="60" r="2" fill="#fff" opacity=".9"/>
    </g>

    <!-- Hamer -->
    <g class="tool hammer" fill="#5F8575">
      <!-- steel -->
      <rect x="36" y="74" width="56" height="6" rx="3"/>
      <!-- kop -->
      <rect x="72" y="60" width="16" height="10" rx="2"/>
      <!-- klauw (gestileerd) -->
      <path d="M72,60 l-8,4 l8,4 z"/>
    </g>
  </svg>
</div>

  <h1 id="title">We voeren een update uit</h1>
  <p class="lead">Er is gepland onderhoud aan onze webshop van <time datetime="<?=htmlspecialchars((new DateTime($start_display?:'now',$tz))->format('Y-m-d\TH:i:sP'))?>"><?=htmlspecialchars($start_display)?></time> tot <time datetime="<?=htmlspecialchars((new DateTime($end_display,$tz))->format('Y-m-d\TH:i:sP'))?>"><?=htmlspecialchars($end_display)?></time>. Tijdens deze periode kan de site tijdelijk minder goed bereikbaar zijn. Bedankt voor uw begrip.</p>

  <div class="window" aria-label="Gepland onderhoudsvenster">
    <div class="chip">Start: <time datetime="<?=htmlspecialchars((new DateTime($start_display?:'now',$tz))->format('Y-m-d\TH:i:sP'))?>"><?=htmlspecialchars($start_display)?></time></div>
    <div class="chip">Einde: <time datetime="<?=htmlspecialchars((new DateTime($end_display,$tz))->format('Y-m-d\TH:i:sP'))?>"><?=htmlspecialchars($end_display)?></time></div>
  </div>

  <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-label="Voortgang"></div>
  <div id="eta" class="timer" hidden></div>

  <div class="actions">
    <a class="btn" href="mailto:<?=$contact_email?>">Mail ons</a>
    <a class="btn" href="tel:<?=$phone?>">Bel: <?=$phone?></a>
    <a class="btn" href="/index.php">Terug naar startpagina</a>
  </div>

  <p class="footer small">We proberen zo snel mogelijk weer online te zijn. Mocht u dringende hulp nodig hebben, neem gerust contact met ons op.</p>
</main></div>
<script src="/assets/js/lottie.min.js"></script>
<script>
lottie.loadAnimation({
  container: document.getElementById('lottie-maintenance'),
  renderer: 'svg',
  loop: true,
  autoplay: true,
  path: '/assets/anim/maintenance.json'
});
</script>
<script>
(()=>{"use strict";
  const iso="<?=htmlspecialchars($eta)?>";
  const el=document.getElementById("eta");
  const end=new Date(iso).getTime();
  if(!isNaN(end)){el.hidden=false;
    const tick=()=>{const diff=end-Date.now();
      if(diff<=0){el.textContent="We gaan elk moment weer online.";return}
      const s=Math.floor(diff/1000),h=Math.floor(s/3600),m=Math.floor((s%3600)/60),sec=s%60;
      el.textContent=`Verwachte herstart over ${String(h).padStart(2,"0")}:${String(m).padStart(2,"0")}:${String(sec).padStart(2,"0")}`;
    };
    tick();setInterval(tick,1000);
  }
})();
</script>
</body></html>
