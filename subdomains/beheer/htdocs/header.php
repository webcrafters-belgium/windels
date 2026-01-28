<?php
// header.php

// Dynamische SEO titel instellen
$pagetitle = $pagetitle ?? "Windels Product Manager";
$description = $description ?? "Beheer eenvoudig products, voorraad en meer in de Windels Product Manager.";
$keywords = $keywords ?? "Windels, Product Manager, Voorraadbeheer, E-commerce";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pagetitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($keywords); ?>">
    <link rel="stylesheet" href="/css/bootstrap-5.3.scss">
    <link rel="stylesheet" href="/css/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="/js/jquery/jquery-3.7.1.min.js"></script>
</head>
<body>
<header class="header w-100">
    <div class="header-logo w-25">
        <img loading="lazy" src="/images/windels-logo.svg" alt="Windels Green & Deco Resin">
    </div>

    <div class='topnav'>
        <a class="nav-link" href="/">Dashboard</a>
        <a class="nav-link" href="/pages/products/products.php">Producten</a>
        <a class="nav-link" href="/pages/categories/categories.php">Categorieën</a>
        <a class="nav-link" href="/pages/logs.php">Logs</a>
        <a class="nav-link" href="/logout.php">Uitloggen</a>
    </div>

    <h1 class="mt-5"><?php echo htmlspecialchars($pagetitle); ?></h1>
</header>
<main>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo w-25">
            <img loading="lazy" src="/images/windels-logo.svg" class="w-100" alt="Windels Green & Deco Resin">
        </div>
        <ul class="nav-list">
            <li class="nav-item">
                <a class="nav-link" href="/">Dashboard</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" href="#">Beheer</a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="/pages/products/products.php">Producten</a></li>
                    <li><a class="nav-link" href="/pages/categories/categories.php">Categorieën</a></li>
                    <li><a class="nav-link" href="/pages/logs.php">Logs</a></li>
                    <li><a class="nav-link" href="/pages/calendar/calendar.php">Kalender</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/pages/blogs/add.php">Blogs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/logout.php">Uitloggen</a>
            </li>
        </ul>
    </aside>


