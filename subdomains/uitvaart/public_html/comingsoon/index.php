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
      margin-bottom:0.5rem;

    }
    h1{
      font-size:2.2rem;
      margin:0 0 1rem;
      font-weight:600;
    }
    .intro p{
      font-size:1.05rem;
      max-width:600px;
      margin:0 auto 1rem auto;
      line-height:1.6;
      text-align:center;
    }
    .highlight{
      font-weight:600;
      color:#1e4025;
    }
    footer{font-size:.95rem;color:#333;background-color:#f8f8f8;width:100%;}
    .footer-header{background-color:#e9f1ec;padding:1.5rem 0;border-bottom:1px solid #d0ded7;}
    .footer-main{background-color:#2a5934;color:#fff;padding:1rem 0;font-size:.9rem;text-align:center;}
    .footer-header p,.footer-main p{margin:.4rem 0;line-height:1.5;text-align:center;}
    .footer-links{margin-top:.5rem;}
    .footer-links a,.footer-header .container a{color:#1e4025;text-decoration:none;margin:0 .3rem;}
    .footer-links a:hover,.footer-header .container a:hover{text-decoration:underline;}
    .container{max-width:1140px;margin:0 auto;padding:0 1rem;}
  </style>
</head>
<body>

<main>
  <img src="/comingsoon/nieuwlogonametransparant.png" alt="Windels green & deco resin logo" class="logo">
  <h1>Binnenkort online</h1>
  <div class="intro">
    <p>Deze gedenkpagina is in opbouw.<br>
    Windels green & deco resin werkt achter de schermen aan iets moois:</p>
    <p>Een nieuwe website met handgemaakte uitvaartdecoratie, vol betekenis en vakmanschap.</p>
    <p>Volg ons op sociale media voor sfeerbeelden en updates.</p>
    <p class="highlight">Vanaf 30 augustus 2025 zijn we hier volledig online!</p>
  </div>

  <div id="countdown" style="font-size:1.8rem;color:#1e4025;font-weight:600;text-align:center;"></div>
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
