<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Unieke Herinneringsproducten met As | Windels Green & Deco Resin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Bekijk ons assortiment gepersonaliseerde decoraties en sieraden waarin as van overleden mensen of dieren verwerkt wordt. Uniek, sereen en op maat.">
    <link rel="canonical" href="https://uitvaart.windelsgreen-decoresin.com/">
    <link rel="shortcut icon" href="/nieuwlogonametransparant.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    body{
      margin:0;
      font-family:'Playfair Display';
      background:#f4f4f4;
      color:#2f4f4f;
      display:flex;
      flex-direction:column;
      min-height:100vh;
    }
    main{
      flex:1;
      display:flex;
      flex-direction:column;
      align-items:center;
      justify-content:center;
      padding:1rem 1rem;
    }
    .logo{
      max-width:240px;
      margin-bottom:-0.5rem;

    }

.section-title::after{
  content:"";
  display:block;
  width:60px;
  height:3px;
  background:var(--green);
  margin:0.6rem auto 0;
  border-radius:2px;
}
.hero{max-width:760px;margin:0 auto 26px auto;text-align:center}
.hero-title{font-size:2.8rem;line-height:1.15;margin:.2rem 0 1rem;color:#1e4025;font-weight:700;letter-spacing:.2px}
.hero-line{margin:.45rem 0;line-height:1.55}
.hero-highlight{margin:0.5rem 0 .6rem;font-weight:700;color:#1e4025}
.count-pill{display:inline-block;margin-top:.6rem;padding:.6rem 1.2rem;border:1px solid #c5d6ce;border-radius:14px;background:#e9f1ec;box-shadow:0 4px 14px rgba(0,0,0,.05);font-size:1.6rem;font-weight:700;color:#1e4025}



    footer{font-size:.95rem;color:#333;background-color:#f8f8f8;width:100%; margin-top:-1rem;}
    .footer-header{background-color:#e9f1ec;padding:1rem 0;border-bottom:1px solid #d0ded7;}
    .footer-main{background-color:#2a5934;color:#fff;padding:1rem 0;font-size:.9rem;text-align:center;}
    .footer-header p,.footer-main p{margin:.4rem 0;line-height:1.25;text-align:center;}
    .footer-links{margin-top:.5rem;}
    .footer-links a,.footer-header .container a{color:#1e4025;text-decoration:none;margin:0 .3rem;}
    .footer-links a:hover,.footer-header .container a:hover{text-decoration:underline;}
    .container{max-width:1140px;margin:0 auto;padding:0 1rem;}
    .footer-social{
  margin-top:0.55rem;
  display:flex;
  gap:.4rem;
  justify-content:center;
  flex-wrap:wrap;
}
.footer-social a{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  width:40px;
  height:40px;
  border-radius:50%;
  background:#e9f1ec;
  color:#1e4025;
  font-size:1.1rem;
  transition:all .2s ease;
  text-decoration:none!important;
}
.footer-social a:hover{
  background:#2a5934;
  color:#fff;
  transform:translateY(-2px);
  text-decoration:none!important; /* geen onderlijning bij hover */
}
.footer-social a:focus{
  outline:none;
  text-decoration:none!important;
}

  </style>
</head>
<body>

<main>
  <img src="/comingsoon/nieuwlogonametransparant.png" alt="Windels green & deco resin logo" class="logo">
  <section class="hero">
  <h1 class="hero-title">Binnenkort online</h1>
  <p class="hero-line">Deze gedenkpagina is in opbouw.</p>
  <p class="hero-line">Windels green & deco resin werkt achter de schermen aan iets moois:</p>
  <p class="hero-line">Een nieuwe website met handgemaakte uitvaartdecoratie, vol betekenis en vakmanschap.</p>
  <p class="hero-line">Volg ons op sociale media voor sfeerbeelden en updates.</p>
  <div id="countdown" class="count-pill" aria-live="polite" role="status">Laden…</div>
  <p class="hero-highlight">Vanaf 30 augustus 2025 zijn we hier volledig online!</p>
</section>

</main>

<script>
const countdownElement = document.getElementById('countdown');
const targetDate = new Date("2025-08-30T10:00:00+02:00").getTime();

function updateCountdown() {
  const now = new Date().getTime();
  const distance = targetDate - now;

  if (distance <= 0) {
    countdownElement.innerHTML = "We zijn live!";
    return;
  }

  const days = Math.floor(distance / (1000 * 60 * 60 * 24));
  const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((distance % (1000 * 60)) / 1000);

  countdownElement.innerHTML =
    `Nog ${days} dag${days !== 1 ? 'en' : ''}, ${hours}u ${minutes}m ${seconds}s`;
}

updateCountdown();
setInterval(updateCountdown, 1000);
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/comingsoon/footer.php'; ?>
