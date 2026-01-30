<?php
/**
 * Admin Header - Glassmorphism Dark Theme
 * Includes navigation sidebar matching design screenshot
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /pages/account/login?referer=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

$username = $_SESSION['user']['name'] ?? 'Admin';
$userRole = $_SESSION['user']['role'] ?? 'admin';

// Get current page for active nav highlighting
$currentPage = $_SERVER['REQUEST_URI'];

function isActive($path) {
    global $currentPage;
    return strpos($currentPage, $path) !== false;
}
?>
<!DOCTYPE html>
<html lang="nl" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Windels Green - Admin</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts - Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --bg-primary: #0a0f1a;
            --bg-secondary: #111827;
            --bg-glass: rgba(15, 23, 42, 0.85);
            --bg-glass-hover: rgba(15, 23, 42, 0.95);
            --border-glass: rgba(148, 163, 184, 0.12);
            --text-primary: #f8fafc;
            --text-secondary: #e2e8f0;
            --text-muted: #94a3b8;
            --accent: #14b8a6;
            --accent-secondary: #06b6d4;
            --accent-blue: #3b82f6;
            --glow-color: rgba(45, 212, 191, 0.25);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background:
                radial-gradient(ellipse at 15% 15%, var(--glow-color) 0%, transparent 45%),
                radial-gradient(ellipse at 85% 85%, rgba(6, 182, 212, 0.12) 0%, transparent 45%);
            pointer-events: none;
            z-index: -1;
        }

        .glass {
            background: var(--bg-glass);
            backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid var(--border-glass);
        }

        .glass-hover:hover {
            background: var(--bg-glass-hover);
        }

        .card-glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: 1rem;
            transition: all 0.3s ease;
        }

        .card-glass:hover {
            border-color: rgba(20, 184, 166, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .accent-bg {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-secondary) 100%);
        }

        .accent-primary { color: var(--accent); }

        .glow-text {
            text-shadow: 0 0 20px var(--glow-color);
        }

        /* Sidebar Styles */
        .admin-sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-glass);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 40;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-glass);
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.75rem;
            overflow-y: auto;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.25rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.625rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(20, 184, 166, 0.15), rgba(6, 182, 212, 0.1));
            color: var(--accent);
            border-left: 3px solid var(--accent);
        }

        .nav-item.active i {
            color: var(--accent);
        }

        .nav-item i {
            font-size: 1.1rem;
            width: 1.5rem;
            text-align: center;
        }

        /* Main Content */
        .admin-main {
            margin-left: 260px;
            min-height: 100vh;
            padding: 2rem;
        }

        /* User Card in Sidebar */
        .user-card {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border-glass);
            margin-top: auto;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeInUp { animation: fadeInUp 0.5s ease-out forwards; }

        .stagger-children > * {
            opacity: 0;
            animation: fadeInUp 0.4s ease-out forwards;
        }
        .stagger-children > *:nth-child(1) { animation-delay: 0.05s; }
        .stagger-children > *:nth-child(2) { animation-delay: 0.1s; }
        .stagger-children > *:nth-child(3) { animation-delay: 0.15s; }
        .stagger-children > *:nth-child(4) { animation-delay: 0.2s; }

        /* Form Elements */
        input, select, textarea {
            background: rgba(15, 23, 42, 0.5) !important;
            color: var(--text-primary) !important;
        }

        input::placeholder, textarea::placeholder {
            color: var(--text-muted) !important;
        }

        /* Alert Styles */
        .alert-warning {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #fbbf24;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #60a5fa;
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl accent-bg flex items-center justify-center">
                <i class="bi bi-leaf text-xl text-white"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg" style="color: var(--text-primary);">Windels Green</h1>
                <span class="text-xs uppercase tracking-wider" style="color: var(--text-muted);">Admin Dashboard</span>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <a href="/admin/" class="nav-item <?= $currentPage === '/admin/' || $currentPage === '/admin/index.php' ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
            <a href="/admin/pages/products/" class="nav-item <?= isActive('/admin/pages/products') ? 'active' : '' ?>">
                <i class="bi bi-box-seam-fill"></i>
                <span>Producten</span>
            </a>
            <a href="/admin/pages/orders/" class="nav-item <?= isActive('/admin/pages/orders') ? 'active' : '' ?>">
                <i class="bi bi-receipt"></i>
                <span>Bestellingen</span>
            </a>
            <a href="/admin/pages/customers/" class="nav-item <?= isActive('/admin/pages/customers') ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i>
                <span>Klanten</span>
            </a>
            <a href="/admin/pages/shipments/" class="nav-item <?= isActive('/admin/pages/shipments') ? 'active' : '' ?>">
                <i class="bi bi-truck"></i>
                <span>Verzendingen</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Marketing</div>
            <a href="/admin/pages/coupons/" class="nav-item <?= isActive('/admin/pages/coupons') || isActive('/admin/customers/coupons') ? 'active' : '' ?>">
                <i class="bi bi-tags-fill"></i>
                <span>Kortingscodes</span>
            </a>
            <a href="/admin/pages/promo/" class="nav-item <?= isActive('/admin/pages/promo') ? 'active' : '' ?>">
                <i class="bi bi-percent"></i>
                <span>Promoties</span>
            </a>
            <a href="/admin/pages/reports/" class="nav-item <?= isActive('/admin/pages/reports') ? 'active' : '' ?>">
                <i class="bi bi-graph-up"></i>
                <span>Rapporten</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Content</div>
            <a href="/admin/pages/blogs/" class="nav-item <?= isActive('/admin/pages/blogs') ? 'active' : '' ?>">
                <i class="bi bi-journal-text"></i>
                <span>Blogs</span>
            </a>
            <a href="/admin/pages/newsletter/" class="nav-item <?= isActive('/admin/pages/newsletter') ? 'active' : '' ?>">
                <i class="bi bi-envelope-fill"></i>
                <span>Nieuwsbrief</span>
            </a>
            <a href="/admin/pages/workshops/" class="nav-item <?= isActive('/admin/pages/workshops') ? 'active' : '' ?>">
                <i class="bi bi-calendar-event"></i>
                <span>Workshops</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Winkel</div>
            <a href="/admin/pages/winkel/" class="nav-item <?= isActive('/admin/pages/winkel') && !isActive('/admin/pages/winkel/producten') && !isActive('/admin/pages/winkel/schaplabel') && !isActive('/admin/pages/winkel/schappenplan') ? 'active' : '' ?>">
                <i class="bi bi-shop"></i>
                <span>Winkel Dashboard</span>
            </a>
            <a href="/admin/pages/winkel/producten/" class="nav-item <?= isActive('/admin/pages/winkel/producten') ? 'active' : '' ?>">
                <i class="bi bi-basket3-fill"></i>
                <span>Winkelproducten</span>
            </a>
            <a href="/admin/pages/winkel/schaplabel/" class="nav-item <?= isActive('/admin/pages/winkel/schaplabel') ? 'active' : '' ?>">
                <i class="bi bi-tag-fill"></i>
                <span>Schaplabels</span>
            </a>
            <a href="/admin/pages/winkel/schappenplan/" class="nav-item <?= isActive('/admin/pages/winkel/schappenplan') ? 'active' : '' ?>">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                <span>Schappenplan</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Systeem</div>
            <a href="/admin/pages/settings/" class="nav-item <?= isActive('/admin/pages/settings') ? 'active' : '' ?>">
                <i class="bi bi-gear-fill"></i>
                <span>Instellingen</span>
            </a>
            <a href="/admin/config/" class="nav-item <?= isActive('/admin/config') ? 'active' : '' ?>">
                <i class="bi bi-sliders"></i>
                <span>Configuratie</span>
            </a>
            <a href="/" class="nav-item" target="_blank">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Naar website</span>
            </a>
        </div>
    </nav>

    <div class="user-card">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500/30 to-purple-500/30 flex items-center justify-center">
                <i class="bi bi-person-fill text-violet-400"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm truncate" style="color: var(--text-primary);"><?= htmlspecialchars($username) ?></p>
                <p class="text-xs" style="color: var(--text-muted);">Administrator</p>
            </div>
            <a href="/pages/account/logout" class="p-2 rounded-lg hover:bg-rose-500/20 text-rose-400" title="Uitloggen">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="admin-main">
