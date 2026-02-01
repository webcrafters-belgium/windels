<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Main Content -->
<div class="max-w-6xl mx-auto animate-fadeInUp">
    
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold glow-text">Winkel Beheer</h1>
        <p class="text-sm mt-1" style="color: var(--text-muted);">Beheer je fysieke winkel producten en schappen</p>
    </div>

    <!-- Quick Links Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 stagger-children">
        
        <!-- Producten -->
        <a href="/admin/pages/winkel/producten/" class="card-glass p-6 group" data-testid="winkel-producten">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-teal-500/30 to-emerald-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="bi bi-box-seam-fill text-2xl text-teal-400"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Producten</h3>
            <p class="text-sm" style="color: var(--text-muted);">Beheer alle winkelproducten en voorraad.</p>
        </a>

        <!-- Schappenplan -->
        <a href="/admin/pages/winkel/schappenplan/" class="card-glass p-6 group" data-testid="winkel-schappenplan">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="bi bi-layout-wtf text-2xl text-blue-400"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Schappenplan</h3>
            <p class="text-sm" style="color: var(--text-muted);">Beheer de indeling van je schappen.</p>
        </a>

        <!-- Schaplabels -->
        <a href="/admin/pages/winkel/schaplabel/" class="card-glass p-6 group" data-testid="winkel-schaplabel">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500/30 to-orange-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="bi bi-tag-fill text-2xl text-amber-400"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Schaplabels</h3>
            <p class="text-sm" style="color: var(--text-muted);">Print schaplabels voor producten.</p>
        </a>

        <!-- Bestellingen -->
        <a href="/admin/pages/winkel/orders/orders_view.php" class="card-glass p-6 group" data-testid="winkel-orders">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-violet-500/30 to-purple-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="bi bi-receipt text-2xl text-violet-400"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Bestellingen</h3>
            <p class="text-sm" style="color: var(--text-muted);">Bekijk en beheer winkelbestellingen.</p>
        </a>

        <!-- Product Afbeeldingen -->
        <a href="/images/products/product_img_folder.php" class="card-glass p-6 group" data-testid="winkel-images">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-rose-500/30 to-pink-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="bi bi-images text-2xl text-rose-400"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Afbeeldingen</h3>
            <p class="text-sm" style="color: var(--text-muted);">Productfoto's en albums beheren.</p>
        </a>

        <!-- FAVV -->
        <a href="/voedselproblemen/" class="card-glass p-6 group" data-testid="winkel-favv">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-slate-500/30 to-gray-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i class="bi bi-clipboard-check text-2xl text-slate-400"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">FAVV</h3>
            <p class="text-sm" style="color: var(--text-muted);">Voedselprobleem meldingen.</p>
        </a>

    </div>

    <!-- Back to Admin -->
    <div class="mt-8">
        <a href="/admin/" class="glass px-4 py-2 rounded-xl inline-flex items-center space-x-2 hover:bg-white/10 transition">
            <i class="bi bi-arrow-left"></i>
            <span>Terug naar Admin</span>
        </a>
    </div>

</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
