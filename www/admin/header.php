<?php
// header.php – globale admin head

?>
<!DOCTYPE html>
<html lang="nl" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>WindelsGreen Admin</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts - Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Modern Glassmorphism Styling -->
    <style>
        :root {
            --bg-primary: #030712;
            --bg-glass: rgba(15, 23, 42, 0.75);
            --bg-glass-hover: rgba(15, 23, 42, 0.9);
            --border-glass: rgba(148, 163, 184, 0.1);
            --text-primary: #f8fafc;
            --text-secondary: #e2e8f0;
            --text-muted: #94a3b8;
            --accent: #14b8a6;
            --accent-secondary: #06b6d4;
            --glow-color: rgba(45, 212, 191, 0.3);
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
                radial-gradient(ellipse at 20% 20%, var(--glow-color) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(6, 182, 212, 0.15) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }
        
        .glass {
            background: var(--bg-glass);
            backdrop-filter: blur(24px) saturate(200%);
            border: 1px solid var(--border-glass);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .card-glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-glass);
            border-radius: 1.25rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-glass:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px var(--glow-color);
            border-color: var(--accent);
        }
        
        .accent-bg {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-secondary) 100%);
        }
        
        .glow-text {
            text-shadow: 0 0 30px var(--glow-color);
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadeInUp { animation: fadeInUp 0.6s ease-out forwards; }
        
        .stagger-children > * {
            opacity: 0;
            animation: fadeInUp 0.5s ease-out forwards;
        }
        .stagger-children > *:nth-child(1) { animation-delay: 0.1s; }
        .stagger-children > *:nth-child(2) { animation-delay: 0.15s; }
        .stagger-children > *:nth-child(3) { animation-delay: 0.2s; }
        .stagger-children > *:nth-child(4) { animation-delay: 0.25s; }
    </style>
</head>
<body class="min-h-screen">
<header class="glass fixed top-0 left-0 right-0 z-50 border-b border-teal-500/20">
    <nav class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl accent-bg flex items-center justify-center">
                <i class="bi bi-leaf text-xl text-white"></i>
            </div>
            <span class="text-lg font-bold glow-text">Windels Green Admin</span>
        </div>
        <a href="/admin/pages/dashboard/index.php" class="accent-bg text-white px-4 py-2 rounded-xl font-semibold hover:opacity-90 transition flex items-center space-x-2">
            <span>Dashboard</span>
            <i class="bi bi-arrow-right"></i>
        </a>
    </nav>
</header>
