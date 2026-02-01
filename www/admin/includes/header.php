<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$configPath = __DIR__ . '/../config.php';
if (!file_exists($configPath)) {
    $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    $fallbacks = [
        $docRoot . '/admin/config.php',
        $docRoot . '/www/admin/config.php',
    ];
    foreach ($fallbacks as $candidate) {
        if ($candidate && file_exists($candidate)) {
            $configPath = $candidate;
            break;
        }
    }
}
require_once $configPath;
requireAdmin();

$currentUser = getCurrentUser();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="nl" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ADMIN_TITLE ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts - Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!-- Custom Glassmorphism Styles -->
    <style>
        /* Light/Dark Theme Variables */
        :root[data-theme="light"] {
            --bg-primary: #f0f2f5;
            --bg-secondary: #ffffff;
            --bg-glass: rgba(255, 255, 255, 0.65);
            --bg-glass-hover: rgba(255, 255, 255, 0.85);
            --border-glass: rgba(255, 255, 255, 0.4);
            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-muted: #64748b;
            --shadow: rgba(0, 0, 0, 0.08);
            --shadow-glow: rgba(20, 184, 166, 0.15);
            --accent: #14b8a6;
            --accent-secondary: #06b6d4;
            --accent-hover: #0d9488;
            --sidebar-bg: rgba(255, 255, 255, 0.75);
            --card-bg: rgba(255, 255, 255, 0.55);
            --gradient-start: #f0fdfa;
            --gradient-end: #ecfeff;
            --glow-color: rgba(20, 184, 166, 0.4);
        }
        
        :root[data-theme="dark"] {
            --bg-primary: #030712;
            --bg-secondary: #0f172a;
            --bg-glass: rgba(15, 23, 42, 0.75);
            --bg-glass-hover: rgba(15, 23, 42, 0.9);
            --border-glass: rgba(148, 163, 184, 0.1);
            --text-primary: #f8fafc;
            --text-secondary: #e2e8f0;
            --text-muted: #94a3b8;
            --shadow: rgba(0, 0, 0, 0.5);
            --shadow-glow: rgba(20, 184, 166, 0.2);
            --accent: #14b8a6;
            --accent-secondary: #06b6d4;
            --accent-hover: #2dd4bf;
            --sidebar-bg: rgba(15, 23, 42, 0.85);
            --card-bg: rgba(15, 23, 42, 0.6);
            --gradient-start: #042f2e;
            --gradient-end: #0c4a6e;
            --glow-color: rgba(45, 212, 191, 0.3);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            transition: background 0.4s ease, color 0.4s ease;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated gradient background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(ellipse at 20% 20%, var(--glow-color) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(6, 182, 212, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(139, 92, 246, 0.08) 0%, transparent 60%);
            pointer-events: none;
            z-index: -1;
            animation: gradientShift 15s ease-in-out infinite alternate;
        }
        
        @keyframes gradientShift {
            0% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.05); }
            100% { opacity: 0.6; transform: scale(1); }
        }
        
        /* Noise texture overlay */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            opacity: 0.02;
            pointer-events: none;
            z-index: -1;
        }
        
        /* Enhanced Glassmorphism Effect */
        .glass {
            background: var(--bg-glass);
            backdrop-filter: blur(24px) saturate(200%);
            -webkit-backdrop-filter: blur(24px) saturate(200%);
            border: 1px solid var(--border-glass);
            box-shadow: 
                0 8px 32px 0 var(--shadow),
                inset 0 1px 0 0 rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glass-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glass-hover:hover {
            background: var(--bg-glass-hover);
            transform: translateY(-4px);
            box-shadow: 
                0 20px 40px 0 var(--shadow),
                0 0 30px 0 var(--shadow-glow),
                inset 0 1px 0 0 rgba(255, 255, 255, 0.1);
            border-color: var(--accent);
        }
        
        /* Sidebar Glass */
        .sidebar-glass {
            background: var(--sidebar-bg);
            backdrop-filter: blur(30px) saturate(200%);
            -webkit-backdrop-filter: blur(30px) saturate(200%);
            border-right: 1px solid var(--border-glass);
            box-shadow: 4px 0 30px var(--shadow);
        }
        
        /* Enhanced Card Glass */
        .card-glass {
            background: var(--card-bg);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid var(--border-glass);
            border-radius: 1.25rem;
            box-shadow: 
                0 8px 32px 0 var(--shadow),
                inset 0 1px 0 0 rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .card-glass::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        }
        
        .card-glass:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 16px 48px 0 var(--shadow),
                0 0 20px 0 var(--shadow-glow);
        }
        
        /* Smooth transitions */
        .transition-theme {
            transition: background 0.4s ease, color 0.4s ease, border-color 0.4s ease;
        }
        
        /* Accent colors with gradient */
        .accent-primary {
            color: var(--accent);
            text-shadow: 0 0 20px var(--shadow-glow);
        }
        
        .accent-bg {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-secondary) 100%);
            box-shadow: 0 4px 20px var(--shadow-glow);
            transition: all 0.3s ease;
        }
        
        .accent-bg:hover {
            background: linear-gradient(135deg, var(--accent-hover) 0%, var(--accent) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 30px var(--shadow-glow);
        }
        
        /* Glow text effect */
        .glow-text {
            text-shadow: 0 0 30px var(--glow-color);
        }
        
        /* Animated border gradient */
        .border-gradient {
            position: relative;
            border: none;
        }
        
        .border-gradient::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, var(--accent), var(--accent-secondary), transparent);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
        
        /* Pulse animation for icons */
        .icon-pulse {
            animation: iconPulse 2s ease-in-out infinite;
        }
        
        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* Input focus states */
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 3px var(--shadow-glow), 0 0 20px var(--shadow-glow);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--border-glass);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent);
        }
        
        /* Table row hover animation */
        tr {
            transition: all 0.3s ease;
        }
        
        tbody tr:hover {
            background: var(--bg-glass) !important;
        }
        
        /* Badge glow effect */
        .badge-glow {
            box-shadow: 0 0 15px currentColor;
        }
        
        /* Floating animation */
        .float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        /* Shimmer loading effect */
        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        /* Large readable text option */
        body.large-text {
            font-size: 1.125rem;
        }
        
        body.large-text h1 { font-size: 2.5rem; }
        body.large-text h2 { font-size: 2rem; }
        body.large-text h3 { font-size: 1.5rem; }
        body.large-text button, body.large-text input { font-size: 1.125rem; padding: 0.75rem 1.5rem; }
        
        /* High contrast mode */
        body.high-contrast {
            --text-primary: #ffffff;
            --text-secondary: #e5e7eb;
            --border-glass: rgba(255, 255, 255, 0.3);
        }
        
        /* Loading state */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        /* Entrance animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        /* Staggered children animations */
        .stagger-children > * {
            opacity: 0;
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        .stagger-children > *:nth-child(1) { animation-delay: 0.1s; }
        .stagger-children > *:nth-child(2) { animation-delay: 0.2s; }
        .stagger-children > *:nth-child(3) { animation-delay: 0.3s; }
        .stagger-children > *:nth-child(4) { animation-delay: 0.4s; }
        .stagger-children > *:nth-child(5) { animation-delay: 0.5s; }
        .stagger-children > *:nth-child(6) { animation-delay: 0.6s; }
        
        /* Button press effect */
        button:active, .btn-press:active {
            transform: scale(0.98);
        }
        
        /* Link hover effect */
        a {
            transition: all 0.3s ease;
        }
        
        /* Status indicator pulse */
        .status-pulse {
            position: relative;
        }
        
        .status-pulse::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            border-radius: inherit;
            background: inherit;
            transform: translate(-50%, -50%);
            animation: statusPulse 2s ease-out infinite;
            z-index: -1;
        }
        
        @keyframes statusPulse {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
            100% { transform: translate(-50%, -50%) scale(1.5); opacity: 0; }
        }
    </style>
</head>
<body class="transition-theme">
    <!-- Top Navigation -->
    <nav class="glass fixed top-0 left-0 right-0 z-50 transition-theme border-b border-gradient" data-testid="top-navigation">
        <div class="max-w-full mx-auto px-6 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo & Title -->
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-xl accent-bg flex items-center justify-center shadow-lg">
                            <i class="bi bi-leaf text-2xl text-white"></i>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 rounded-full border-2 border-current animate-pulse"></div>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold glow-text" style="color: var(--text-primary);">Windels Green</h1>
                        <p class="text-xs font-medium tracking-wider uppercase" style="color: var(--accent);">Admin Dashboard</p>
                    </div>
                </div>
                
                <!-- Right Actions -->
                <div class="flex items-center space-x-3">
                    <!-- Accessibility Options -->
                    <button onclick="toggleLargeText()" class="p-2.5 rounded-xl glass-hover border border-transparent hover:border-current" title="Groot lettertype" data-testid="toggle-large-text-btn">
                        <i class="bi bi-type text-lg"></i>
                    </button>
                    
                    <button onclick="toggleHighContrast()" class="p-2.5 rounded-xl glass-hover border border-transparent hover:border-current" title="Hoog contrast" data-testid="toggle-high-contrast-btn">
                        <i class="bi bi-circle-half text-lg"></i>
                    </button>
                    
                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()" class="p-2.5 rounded-xl glass-hover border border-transparent hover:border-current" title="Wissel thema" data-testid="toggle-theme-btn">
                        <i class="bi bi-moon-stars text-lg theme-icon-dark"></i>
                        <i class="bi bi-sun text-lg theme-icon-light hidden"></i>
                    </button>
                    
                    <div class="w-px h-8 bg-gradient-to-b from-transparent via-current to-transparent opacity-20 mx-2"></div>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-3 glass rounded-xl px-4 py-2 border border-transparent hover:border-teal-500/30 transition-all cursor-pointer group" data-testid="user-menu">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-teal-400 to-cyan-500 flex items-center justify-center text-white font-bold shadow-lg group-hover:scale-110 transition-transform">
                            <?= strtoupper(substr($currentUser['name'], 0, 1)) ?>
                        </div>
                        <div>
                            <p class="font-semibold text-sm"><?= htmlspecialchars($currentUser['name']) ?></p>
                            <p class="text-xs" style="color: var(--text-muted);">Administrator</p>
                        </div>
                        <i class="bi bi-chevron-down text-sm opacity-50 group-hover:opacity-100 transition-opacity"></i>
                    </div>
                    
                    <a href="/pages/account/logout.php" class="p-2.5 rounded-xl glass-hover text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/30" title="Uitloggen" data-testid="logout-btn">
                        <i class="bi bi-box-arrow-right text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Layout -->
    <div class="flex pt-16 min-h-screen">
        <!-- Sidebar -->
        <aside class="sidebar-glass fixed left-0 top-16 bottom-0 w-72 p-5 overflow-y-auto transition-theme" data-testid="sidebar">
            <nav class="space-y-1.5 stagger-children">
                <?php
                $menuItems = [
                    ['url' => '/admin/pages/dashboard/index.php', 'icon' => 'bi-grid-1x2-fill', 'label' => 'Dashboard', 'match' => 'dashboard'],
                    ['url' => '/admin/pages/products/index.php', 'icon' => 'bi-box-seam-fill', 'label' => 'Producten', 'match' => 'products'],
                    ['url' => '/admin/pages/orders/index.php', 'icon' => 'bi-receipt', 'label' => 'Bestellingen', 'match' => 'orders'],
                    ['url' => '/admin/pages/customers/index.php', 'icon' => 'bi-people-fill', 'label' => 'Klanten', 'match' => 'customers'],
                    ['url' => '/admin/pages/shipments/index.php', 'icon' => 'bi-truck', 'label' => 'Verzendingen', 'match' => 'shipments'],
                ];
                
                foreach ($menuItems as $item):
                    $isActive = strpos($_SERVER['PHP_SELF'], $item['match']) !== false;
                ?>
                <a href="<?= $item['url'] ?>" 
                   class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-300 <?= $isActive ? 'accent-bg text-white shadow-lg' : 'hover:bg-white/5' ?>"
                   data-testid="nav-<?= $item['match'] ?>">
                    <div class="w-10 h-10 rounded-lg <?= $isActive ? 'bg-white/20' : 'bg-gradient-to-br from-teal-500/20 to-cyan-500/20 group-hover:from-teal-500/30 group-hover:to-cyan-500/30' ?> flex items-center justify-center transition-all">
                        <i class="bi <?= $item['icon'] ?> text-xl <?= $isActive ? 'text-white' : 'text-teal-400' ?>"></i>
                    </div>
                    <span class="font-medium <?= $isActive ? '' : 'group-hover:text-teal-300' ?>"><?= $item['label'] ?></span>
                    <?php if ($isActive): ?>
                    <div class="ml-auto w-2 h-2 rounded-full bg-white animate-pulse"></div>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
                
                <div class="py-4">
                    <div class="h-px bg-gradient-to-r from-transparent via-teal-500/30 to-transparent"></div>
                </div>
                
                <p class="px-4 text-xs uppercase tracking-widest font-semibold mb-3" style="color: var(--text-muted);">
                    <i class="bi bi-megaphone-fill mr-2 text-teal-500"></i>Marketing
                </p>
                
                <?php
                $marketingItems = [
                    ['url' => '/admin/pages/coupons/index.php', 'icon' => 'bi-tag-fill', 'label' => 'Kortingscodes', 'match' => 'coupons'],
                    ['url' => '/admin/pages/reports/index.php', 'icon' => 'bi-graph-up-arrow', 'label' => 'Rapporten', 'match' => 'reports'],
                ];
                
                foreach ($marketingItems as $item):
                    $isActive = strpos($_SERVER['PHP_SELF'], $item['match']) !== false;
                ?>
                <a href="<?= $item['url'] ?>" 
                   class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-300 <?= $isActive ? 'accent-bg text-white shadow-lg' : 'hover:bg-white/5' ?>"
                   data-testid="nav-<?= $item['match'] ?>">
                    <div class="w-10 h-10 rounded-lg <?= $isActive ? 'bg-white/20' : 'bg-gradient-to-br from-purple-500/20 to-pink-500/20 group-hover:from-purple-500/30 group-hover:to-pink-500/30' ?> flex items-center justify-center transition-all">
                        <i class="bi <?= $item['icon'] ?> text-xl <?= $isActive ? 'text-white' : 'text-purple-400' ?>"></i>
                    </div>
                    <span class="font-medium <?= $isActive ? '' : 'group-hover:text-purple-300' ?>"><?= $item['label'] ?></span>
                </a>
                <?php endforeach; ?>
                
                <div class="py-4">
                    <div class="h-px bg-gradient-to-r from-transparent via-slate-500/20 to-transparent"></div>
                </div>
                
                <a href="/admin/pages/settings/index.php" 
                   class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-300 <?= strpos($_SERVER['PHP_SELF'], 'settings') !== false ? 'accent-bg text-white shadow-lg' : 'hover:bg-white/5' ?>"
                   data-testid="nav-settings">
                    <div class="w-10 h-10 rounded-lg <?= strpos($_SERVER['PHP_SELF'], 'settings') !== false ? 'bg-white/20' : 'bg-gradient-to-br from-slate-500/20 to-slate-600/20 group-hover:from-slate-500/30 group-hover:to-slate-600/30' ?> flex items-center justify-center transition-all">
                        <i class="bi bi-gear-fill text-xl <?= strpos($_SERVER['PHP_SELF'], 'settings') !== false ? 'text-white' : 'text-slate-400' ?> group-hover:rotate-90 transition-transform duration-500"></i>
                    </div>
                    <span class="font-medium <?= strpos($_SERVER['PHP_SELF'], 'settings') !== false ? '' : 'group-hover:text-slate-300' ?>">Instellingen</span>
                </a>
                
                <a href="/" class="group flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all duration-300" data-testid="nav-home">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500/20 to-orange-500/20 group-hover:from-amber-500/30 group-hover:to-orange-500/30 flex items-center justify-center transition-all">
                        <i class="bi bi-house-door text-xl text-amber-400 group-hover:scale-110 transition-transform"></i>
                    </div>
                    <span class="font-medium group-hover:text-amber-300">Hoofdpagina</span>
                </a>
            </nav>
            
            <!-- Bottom info card -->
            <div class="mt-6 p-4 rounded-xl bg-gradient-to-br from-teal-500/10 to-cyan-500/10 border border-teal-500/20">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-teal-500/20 flex items-center justify-center">
                        <i class="bi bi-lightning-charge-fill text-teal-400"></i>
                    </div>
                    <span class="font-semibold text-sm">Pro Tip</span>
                </div>
                <p class="text-xs leading-relaxed" style="color: var(--text-muted);">
                    Gebruik <kbd class="px-1.5 py-0.5 rounded bg-white/10 text-xs">Ctrl+K</kbd> voor snelzoeken
                </p>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="ml-72 flex-1 p-8 animate-fadeInUp">
            <input type="hidden" id="csrf_token" value="<?= generateCSRFToken() ?>">
