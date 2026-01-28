<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unieke Herinneringsproducten met As | Windels Green & Deco Resin</title>
    <link rel="icon" href="/assets/logo/favicon.png">
    <meta name="description" content="Bekijk ons assortiment gepersonaliseerde decoraties en sieraden waarin as van overleden mensen of dieren verwerkt wordt. Uniek, sereen en op maat.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="https://uitvaart.windelsgreen-decoresin.com/pages/assortiment.php">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css?ver=<?php echo time(); ?>">
</head>
<body>
<header class="site-header">
    <!-- Topbar met contactgegevens -->
    <div class="topbar">
        <div class="container">
            <div class="contact-info">
                📞 <a href="tel:+3211753319">+32 1175 33 19</a> |
                ✉ <a href="mailto:info@windelsgreen-decoresin.com">info@windelsgreen-decoresin.com</a>
            </div>
        </div>
    </div>

    <!-- Hoofd-header -->
    <div class="container header-inner">
        <a href="/"><img src="/img/logo.png" alt="Logo" class="logo"></a>
        <button class="menu-toggle" aria-label="Menu openen">&#9776;</button>
        <nav class="site-nav">
            <ul>
                <li><a href="/index.php">Home</a></li>
                <li><a href="/pages/assortiment.php">Assortiment</a></li>
                <li><a href="/pages/contacteer-uitvaartdienst.php">Uitvaartdiensten</a></li>
                <li><a href="/pages/over-ons.php">Over Ons</a></li>
                <li><a href="/pages/contact/contact.php">Contact</a></li>
               <?php if (!isset($_SESSION['partner_id'])): ?>
                <li><a href="/pages/account/login.php" class="btn-login">Uitvaartdiensten login</a></li>
                <?php else: ?>
                    <li><a href="/pages/account/dashboard.php" class="btn-login">Uitvaartdiensten dashboard</a></li>
                
               <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>


