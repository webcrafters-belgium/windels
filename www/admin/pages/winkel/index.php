<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-shop accent-primary mr-3"></i>Winkel Dashboard
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Beheer je fysieke winkel</p>
        </div>
    </div>
</div>

<!-- STATUS ALERT -->
<div class="card-glass p-4 mb-8 border-amber-500/30">
    <div class="flex items-center space-x-4">
        <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center">
            <i class="bi bi-clock text-2xl text-amber-400"></i>
        </div>
        <div>
            <h3 class="font-bold text-amber-400">Winkeltijden</h3>
            <p class="text-sm" style="color: var(--text-muted);">Nu gesloten, maar vandaag geopend van 19:00 tot 21:00</p>
        </div>
    </div>
</div>

<!-- WINKEL MENU CARDS -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 stagger-children">
    
    <a href="/admin/" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500/30 to-cyan-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-house-fill text-2xl text-teal-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Admin Home</h3>
        <p class="text-sm" style="color: var(--text-muted);">Terug naar dashboard</p>
    </a>
    
    <a href="/images/products/product_img_folder.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500/30 to-purple-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-images text-2xl text-violet-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Afbeelding album</h3>
        <p class="text-sm" style="color: var(--text-muted);">Product afbeeldingen beheren</p>
    </a>
    
    <a href="/admin/pages/winkel/producten" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/30 to-green-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-box-seam-fill text-2xl text-emerald-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Producten</h3>
        <p class="text-sm" style="color: var(--text-muted);">Winkelproducten beheren</p>
    </a>
    
    <a href="/admin/pages/winkel/schappenplan/index.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-grid-3x3-gap-fill text-2xl text-blue-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Schappenplan</h3>
        <p class="text-sm" style="color: var(--text-muted);">Schapindeling beheren</p>
    </a>
    
    <a href="/admin/pages/winkel/schaplabel/index.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/30 to-orange-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-tag-fill text-2xl text-amber-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Schaplabels</h3>
        <p class="text-sm" style="color: var(--text-muted);">Labels printen en beheren</p>
    </a>
    
    <a href="/admin/pages/winkel/orders/orders_view.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500/30 to-pink-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-cart-fill text-2xl text-rose-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Bestellingen</h3>
        <p class="text-sm" style="color: var(--text-muted);">Winkelbestellingen inzien</p>
    </a>
    
    <a href="/admin/voedselproblemen/index.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500/30 to-rose-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-shield-exclamation text-2xl text-red-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">FAVV</h3>
        <p class="text-sm" style="color: var(--text-muted);">Voedselveiligheid beheer</p>
    </a>
    
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
