<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
        <i class="bi bi-gear-fill accent-primary mr-3"></i>Instellingen
    </h1>
    <p class="text-lg" style="color: var(--text-muted);">Beheer je websiteconfiguratie</p>
</div>

<!-- SETTINGS GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 stagger-children">
    
    <a href="/admin/config/" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500/30 to-cyan-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-sliders text-2xl text-teal-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Algemene Configuratie</h3>
        <p class="text-sm" style="color: var(--text-muted);">Website-instellingen en variabelen</p>
    </a>
    
    <a href="/admin/config/opening_times/" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-clock-fill text-2xl text-blue-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Openingstijden</h3>
        <p class="text-sm" style="color: var(--text-muted);">Winkel openings- en sluitingstijden</p>
    </a>
    
    <a href="/admin/config/opening_times/vacation/" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/30 to-orange-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-calendar-x text-2xl text-amber-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Vakanties</h3>
        <p class="text-sm" style="color: var(--text-muted);">Beheer vakantiedagen en sluitingen</p>
    </a>
    
    <a href="/admin/pages/manage_admin_pages/add.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500/30 to-purple-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-file-earmark-plus text-2xl text-violet-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Admin Pagina's</h3>
        <p class="text-sm" style="color: var(--text-muted);">Nieuwe admin pagina's toevoegen</p>
    </a>
    
    <a href="/admin/tools/mailing/" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500/30 to-pink-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-envelope-fill text-2xl text-rose-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">E-mail Instellingen</h3>
        <p class="text-sm" style="color: var(--text-muted);">SMTP en mailing configuratie</p>
    </a>
    
    <a href="/admin/tools/onfact/" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/30 to-green-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-receipt-cutoff text-2xl text-emerald-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Onfact</h3>
        <p class="text-sm" style="color: var(--text-muted);">Facturatie integratie</p>
    </a>
    
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
