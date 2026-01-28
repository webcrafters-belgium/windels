<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config.php';
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
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!-- Custom Glassmorphism Styles -->
    <style>
        /* Light/Dark Theme Variables */
        :root[data-theme="light"] {
            --bg-primary: #f0f4f8;
            --bg-secondary: #ffffff;
            --bg-glass: rgba(255, 255, 255, 0.7);
            --bg-glass-hover: rgba(255, 255, 255, 0.85);
            --border-glass: rgba(255, 255, 255, 0.3);
            --text-primary: #1a202c;
            --text-secondary: #4a5568;
            --text-muted: #718096;
            --shadow: rgba(0, 0, 0, 0.1);
            --accent: #10b981;
            --accent-hover: #059669;
            --sidebar-bg: rgba(255, 255, 255, 0.8);
            --card-bg: rgba(255, 255, 255, 0.6);
        }
        
        :root[data-theme="dark"] {
            --bg-primary: #0a0e1a;
            --bg-secondary: #111827;
            --bg-glass: rgba(17, 24, 39, 0.7);
            --bg-glass-hover: rgba(17, 24, 39, 0.85);
            --border-glass: rgba(255, 255, 255, 0.1);
            --text-primary: #f3f4f6;
            --text-secondary: #d1d5db;
            --text-muted: #9ca3af;
            --shadow: rgba(0, 0, 0, 0.5);
            --accent: #10b981;
            --accent-hover: #34d399;
            --sidebar-bg: rgba(17, 24, 39, 0.8);
            --card-bg: rgba(17, 24, 39, 0.6);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            transition: background 0.3s ease, color 0.3s ease;
        }
        
        /* Glassmorphism Effect */
        .glass {
            background: var(--bg-glass);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid var(--border-glass);
            box-shadow: 0 8px 32px 0 var(--shadow);
        }
        
        .glass-hover:hover {
            background: var(--bg-glass-hover);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        
        /* Sidebar Glass */
        .sidebar-glass {
            background: var(--sidebar-bg);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-right: 1px solid var(--border-glass);
        }
        
        /* Card Glass */
        .card-glass {
            background: var(--card-bg);
            backdrop-filter: blur(12px) saturate(150%);
            -webkit-backdrop-filter: blur(12px) saturate(150%);
            border: 1px solid var(--border-glass);
            border-radius: 1rem;
            box-shadow: 0 4px 24px 0 var(--shadow);
        }
        
        /* Smooth transitions */
        .transition-theme {
            transition: background 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        
        /* Accent colors */
        .accent-primary {
            color: var(--accent);
        }
        
        .accent-bg {
            background: var(--accent);
        }
        
        .accent-bg:hover {
            background: var(--accent-hover);
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
    </style>
</head>
<body class="transition-theme">
    <!-- Top Navigation -->
    <nav class="glass fixed top-0 left-0 right-0 z-50 transition-theme">
        <div class="max-w-full mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo & Title -->
                <div class="flex items-center space-x-4">
                    <i class="bi bi-shop text-3xl accent-primary"></i>
                    <div>
                        <h1 class="text-xl font-bold" style="color: var(--text-primary);">Windels Green & Deco Resin</h1>
                        <p class="text-sm" style="color: var(--text-muted);">Admin Panel</p>
                    </div>
                </div>
                
                <!-- Right Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Accessibility Options -->
                    <button onclick="toggleLargeText()" class="p-2 rounded-lg glass-hover" title="Groot lettertype">
                        <i class="bi bi-type text-xl"></i>
                    </button>
                    
                    <button onclick="toggleHighContrast()" class="p-2 rounded-lg glass-hover" title="Hoog contrast">
                        <i class="bi bi-circle-half text-xl"></i>
                    </button>
                    
                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()" class="p-2 rounded-lg glass-hover" title="Wissel thema">
                        <i class="bi bi-moon-stars text-xl theme-icon-dark"></i>
                        <i class="bi bi-sun text-xl theme-icon-light hidden"></i>
                    </button>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-3 glass rounded-lg px-4 py-2">
                        <i class="bi bi-person-circle text-2xl accent-primary"></i>
                        <div>
                            <p class="font-semibold text-sm"><?= htmlspecialchars($currentUser['name']) ?></p>
                            <p class="text-xs" style="color: var(--text-muted);">Administrator</p>
                        </div>
                    </div>
                    
                    <a href="/pages/account/logout.php" class="p-2 rounded-lg glass-hover text-red-500" title="Uitloggen">
                        <i class="bi bi-box-arrow-right text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Layout -->
    <div class="flex pt-20 min-h-screen">
        <!-- Sidebar -->
        <aside class="sidebar-glass fixed left-0 top-20 bottom-0 w-72 p-6 overflow-y-auto transition-theme">
            <nav class="space-y-2">
                <a href="/admin_new/pages/dashboard/index.php" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg glass-hover <?= $currentPage === 'index' && strpos($_SERVER['PHP_SELF'], 'dashboard') !== false ? 'accent-bg text-white' : '' ?>">
                    <i class="bi bi-speedometer2 text-2xl"></i>
                    <span class="text-lg font-medium">Dashboard</span>
                </a>
                
                <a href="/admin_new/pages/products/index.php" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg glass-hover <?= strpos($_SERVER['PHP_SELF'], 'products') !== false ? 'accent-bg text-white' : '' ?>">
                    <i class="bi bi-box-seam text-2xl"></i>
                    <span class="text-lg font-medium">Producten</span>
                </a>
                
                <a href="/admin_new/pages/orders/index.php" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg glass-hover <?= strpos($_SERVER['PHP_SELF'], 'orders') !== false ? 'accent-bg text-white' : '' ?>">
                    <i class="bi bi-receipt text-2xl"></i>
                    <span class="text-lg font-medium">Bestellingen</span>
                </a>
                
                <a href="/admin_new/pages/shipments/index.php" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg glass-hover <?= strpos($_SERVER['PHP_SELF'], 'shipments') !== false ? 'accent-bg text-white' : '' ?>">
                    <i class="bi bi-truck text-2xl"></i>
                    <span class="text-lg font-medium">Verzendingen</span>
                </a>
                
                <a href="/admin_new/pages/settings/index.php" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg glass-hover <?= strpos($_SERVER['PHP_SELF'], 'settings') !== false ? 'accent-bg text-white' : '' ?>">
                    <i class="bi bi-gear text-2xl"></i>
                    <span class="text-lg font-medium">Instellingen</span>
                </a>
                
                <hr style="border-color: var(--border-glass);" class="my-4">
                
                <a href="/admin/" class="flex items-center space-x-3 px-4 py-3 rounded-lg glass-hover">
                    <i class="bi bi-arrow-left text-2xl"></i>
                    <span class="text-lg font-medium">Oude Admin</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="ml-72 flex-1 p-8">
            <input type="hidden" id="csrf_token" value="<?= generateCSRFToken() ?>">