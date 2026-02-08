<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

/**
 * Veilig escapen (PHP 8.2+ compatible)
 */
function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

global $count;
$count = $count ?? 0;

// SEO defaults (overridable before including header)
$siteBaseUrl = 'https://windelsgreen-decoresin.com';
$currentRequest = $_SERVER['REQUEST_URI'] ?? '/';
$pathOnly = strtok($currentRequest, '?');
$canonicalUrl = $canonical ?? rtrim($siteBaseUrl, '/') . ($pathOnly ?: '/');
$seoTitle = $pagetitle ?? 'Windels Green & Deco Resin | Epoxy, terrazzo & kaarsen';
$seoDescription = trim($seoDescription ?? 'Windels Green & Deco Resin maakt unieke epoxy, terrazzo en kaarsen met oog voor handwerk en duurzame afwerking.');
$seoKeywords = trim($seoKeywords ?? 'windels, resin, epoxy, terrazzo, kaarsen, handgemaakt, Belgisch, atelier');
$seoImage = $seoImage ?? $siteBaseUrl . '/images/layout/new_index/eco-resin-mini-tray-terrazzo-craft-workshop-edinburgh-portrait-big.png';
$ogType = $ogType ?? 'website';
$organizationSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $bedrijfsnaam ?? 'Windels Green & Deco Resin',
    'url' => $siteBaseUrl,
    'logo' => $siteBaseUrl . ($bedrijfslogo ?? '/images/windels-logo.svg'),
    'contactPoint' => [
        [
            '@type' => 'ContactPoint',
            'telephone' => $bedrijfstelefoon ?? '+3211753319',
            'contactType' => 'customer service',
            'areaServed' => 'BE',
            'availableLanguage' => 'Dutch'
        ]
    ],
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => 'Beukenlaan 8',
        'addressLocality' => 'Hamont-Achel',
        'postalCode' => '3930',
        'addressCountry' => 'BE'
    ]
];
$schemaJson = json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title><?= e($seoTitle); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Gielen Matthias">
    <meta name="description" content="<?= e($seoDescription); ?>">
    <meta name="keywords" content="<?= e($seoKeywords); ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= e($canonicalUrl); ?>">
    <meta property="og:locale" content="nl_BE">
    <meta property="og:site_name" content="Windels Green & Deco Resin">
    <meta property="og:type" content="<?= e($ogType); ?>">
    <meta property="og:title" content="<?= e($seoTitle); ?>">
    <meta property="og:description" content="<?= e($seoDescription); ?>">
    <meta property="og:url" content="<?= e($canonicalUrl); ?>">
    <meta property="og:image" content="<?= e($seoImage); ?>">
    <meta property="og:image:secure_url" content="<?= e($seoImage); ?>">
    <meta property="og:image:alt" content="Windels Green & Deco Resin handgemaakte creaties">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($seoTitle); ?>">
    <meta name="twitter:description" content="<?= e($seoDescription); ?>">
    <meta name="twitter:image" content="<?= e($seoImage); ?>">
    <meta name="twitter:creator" content="@windelsgreen">
    <meta name="theme-color" content="#3c8c72">

    <link rel="icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="/css/bootstrap-5.3.min.css">
    <link rel="stylesheet" href="/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/scss/styles.min.css">
    <link rel="stylesheet" href="/assets/scss/home.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="/js/jquery/jquery-3.7.1.min.js" defer></script>
    <script src="/js/swiper/swiper-bundle.min.js" defer></script>

    <!-- TrustBox script -->
    <script type="text/javascript" src="https://widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>

    <!-- End TrustBox script -->

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-DC5BLRCC50"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-DC5BLRCC50');
    </script>

    <!-- End Google Tag Manager -->
    <script type="application/ld+json">
        <?= $schemaJson ?: '{}'; ?>
    </script>
</head>

<body class="site-body">

<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MWSH4NMH" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<header class="header py-3 bg-offwhite">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <!-- LOGO -->
        <a class="header-logo d-flex align-items-center text-decoration-none"
           href="/">
            <img src="/images/windels-logo.svg"
                 alt="Windels Green & Deco Resin"
                 class="img-fluid"
                 style="height:55px;">
        </a>

        <!-- DESKTOP NAV -->
        <nav class="header-nav d-none d-lg-flex align-items-center gap-4">

            <a href="/" class="nav-link fw-semibold">Home</a>
            <a href="/pages/shop/" class="nav-link fw-semibold">Winkel</a>
            <a href="/pages/about/" class="nav-link fw-semibold">Over ons</a>
            <a href="/pages/blogs/" class="nav-link fw-semibold">Blog</a>
            <a href="/pages/contact/" class="nav-link fw-semibold">Contact</a>
            <a href="/pages/uitvaart/" class="nav-link fw-semibold">Uitvaart</a>

            <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                <a href="/admin" class="nav-link fw-bold text-danger">
                    <i class="bi bi-shield-lock"></i> Admin
                </a>
            <?php endif; ?>

        </nav>

        <!-- ACTION BUTTONS -->
        <div class="header-actions d-flex align-items-center gap-3">

            <!-- SEARCH -->
            <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasSearch">
                <i class="bi bi-search fs-5"></i>
            </button>

            <!-- ACCOUNT -->
            <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasAccount">
                <i class="bi bi-person fs-5"></i>
            </button>

            <!-- CART -->
            <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center position-relative"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasCart">
                <i class="bi bi-cart3 fs-5"></i>
                <span class="cart-count-badge" style="display:none">0</span>
            </button>

            <!-- MOBILE MENU -->
            <button class="btn btn-primary rounded-circle d-lg-none"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasMenu">
                <i class="bi bi-list fs-4"></i>
            </button>

        </div>

    </div>
</header>


<!-- MOBILE OFFCANVAS NAV -->
<div class="offcanvas offcanvas-end" id="offcanvasMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">

        <a href="/" class="list-group-item list-group-item-action">Home</a>
        <a href="/pages/shop/" class="list-group-item list-group-item-action">Winkel</a>
        <a href="/pages/about/" class="list-group-item list-group-item-action">Over ons</a>
        <a href="/pages/blogs/" class="list-group-item list-group-item-action">Blog</a>
        <a href="/pages/contact/" class="list-group-item list-group-item-action">Contact</a>

        <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="/admin" class="list-group-item list-group-item-action text-danger fw-bold">
                <i class="bi bi-shield-lock"></i> Admin
            </a>
        <?php endif; ?>

    </div>
</div>


<!-- SEARCH PANEL -->
<div class="offcanvas offcanvas-top" id="offcanvasSearch" style="height:230px;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Zoeken</h5>
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form action="/API/shop/search_products.php" method="get" class="d-flex gap-2">
            <input name="q" type="search" required
                   class="form-control form-control-lg"
                   placeholder="Zoek in alle producten…">
            <button class="btn btn-primary btn-lg">Zoek</button>
        </form>
    </div>
</div>


<!-- ACCOUNT PANEL -->
<div class="offcanvas offcanvas-end" id="offcanvasAccount">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Mijn account</h5>
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">

        <?php if (!isset($_SESSION['user'])): ?>

            <a href="/pages/account/login" class="btn btn-dark w-100 mb-3">Inloggen</a>
            <a href="/pages/account/register" class="btn btn-outline-dark w-100">Registreren</a>

        <?php else: ?>

            <p class="fw-bold mb-2">
                Hallo, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Gebruiker', ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <div class="list-group mb-3">
                <a href="/pages/account/" class="list-group-item list-group-item-action">Mijn account</a>
                <a href="/pages/account/bestellingen" class="list-group-item list-group-item-action">Bestellingen</a>
                <a href="/pages/account/logout" class="list-group-item list-group-item-action text-danger">Uitloggen</a>
            </div>

        <?php endif; ?>

    </div>
</div>


<!-- CART PANEL -->
<div class="offcanvas offcanvas-end" id="offcanvasCart">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Winkelwagen</h5>
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">
        <ul id="cart-items-list" class="list-group mb-3"></ul>

        <button onclick="window.location='/pages/shop/cart/'"
                class="btn btn-primary w-100 btn-lg">
            Bekijk winkelmandje
        </button>
    </div>
</div>


<!-- ❄️ Sneeuwvlokken tot na Nieuwjaar -->
<style>
    .snowflake {
        position: fixed;
        top: -10px;
        z-index: 9999;
        color: rgba(255,255,255,0.9);
        user-select: none;
        pointer-events: none;
        font-size: 1em;
        animation: fall linear infinite;
        display: none;
    }

    @keyframes fall {
        to {
            transform: translateY(110vh);
        }
    }
</style>

<script>
    (function () {
        const maxFlakes = 35;

        for (let i = 0; i < maxFlakes; i++) {
            const flake = document.createElement("div");
            flake.className = "snowflake";
            flake.innerHTML = "❄";
            flake.style.left = Math.random() * 100 + "vw";
            flake.style.animationDuration = (5 + Math.random() * 10) + "s";
            flake.style.fontSize = (12 + Math.random() * 18) + "px";
            flake.style.opacity = Math.random();
            document.body.appendChild(flake);
        }
    })();
</script>
