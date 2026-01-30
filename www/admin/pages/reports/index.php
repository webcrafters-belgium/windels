<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

// Calculate basic stats
$todaySales = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE DATE(created_at) = CURDATE() AND status != 'geannuleerd'")->fetch_assoc()['total'] ?? 0;
$weekSales = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND status != 'geannuleerd'")->fetch_assoc()['total'] ?? 0;
$monthSales = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND status != 'geannuleerd'")->fetch_assoc()['total'] ?? 0;
$yearSales = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE YEAR(created_at) = YEAR(CURDATE()) AND status != 'geannuleerd'")->fetch_assoc()['total'] ?? 0;

// Orders count
$todayOrders = $conn->query("SELECT COUNT(*) as cnt FROM orders WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['cnt'] ?? 0;
$weekOrders = $conn->query("SELECT COUNT(*) as cnt FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)")->fetch_assoc()['cnt'] ?? 0;
$monthOrders = $conn->query("SELECT COUNT(*) as cnt FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)")->fetch_assoc()['cnt'] ?? 0;
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
        <i class="bi bi-graph-up accent-primary mr-3"></i>Rapporten
    </h1>
    <p class="text-lg" style="color: var(--text-muted);">Overzicht van verkoop en statistieken</p>
</div>

<!-- SALES STATS -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 stagger-children">
    <div class="card-glass p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/30 to-green-500/30 flex items-center justify-center">
                <i class="bi bi-calendar-day text-2xl text-emerald-400"></i>
            </div>
            <span class="text-2xl font-bold text-emerald-400">€<?= number_format($todaySales, 2, ',', '.') ?></span>
        </div>
        <h3 class="font-semibold" style="color: var(--text-secondary);">Vandaag</h3>
        <p class="text-sm" style="color: var(--text-muted);"><?= $todayOrders ?> bestellingen</p>
    </div>

    <div class="card-glass p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center">
                <i class="bi bi-calendar-week text-2xl text-blue-400"></i>
            </div>
            <span class="text-2xl font-bold text-blue-400">€<?= number_format($weekSales, 2, ',', '.') ?></span>
        </div>
        <h3 class="font-semibold" style="color: var(--text-secondary);">Deze week</h3>
        <p class="text-sm" style="color: var(--text-muted);"><?= $weekOrders ?> bestellingen</p>
    </div>

    <div class="card-glass p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500/30 to-purple-500/30 flex items-center justify-center">
                <i class="bi bi-calendar-month text-2xl text-violet-400"></i>
            </div>
            <span class="text-2xl font-bold text-violet-400">€<?= number_format($monthSales, 2, ',', '.') ?></span>
        </div>
        <h3 class="font-semibold" style="color: var(--text-secondary);">Deze maand</h3>
        <p class="text-sm" style="color: var(--text-muted);"><?= $monthOrders ?> bestellingen</p>
    </div>

    <div class="card-glass p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/30 to-orange-500/30 flex items-center justify-center">
                <i class="bi bi-calendar text-2xl text-amber-400"></i>
            </div>
            <span class="text-2xl font-bold text-amber-400">€<?= number_format($yearSales, 2, ',', '.') ?></span>
        </div>
        <h3 class="font-semibold" style="color: var(--text-secondary);">Dit jaar</h3>
        <p class="text-sm" style="color: var(--text-muted);">Totale omzet</p>
    </div>
</div>

<!-- QUICK REPORTS -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- TOP PRODUCTS -->
    <div class="card-glass p-8">
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500/30 to-cyan-500/30 flex items-center justify-center">
                <i class="bi bi-trophy text-xl text-teal-400"></i>
            </div>
            <h2 class="text-xl font-bold" style="color: var(--text-primary);">Best Verkochte Producten</h2>
        </div>
        
        <?php
        $topProducts = $conn->query("
            SELECT p.name, p.sku, SUM(oi.quantity) as total_sold
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            JOIN orders o ON o.id = oi.order_id
            WHERE o.status != 'geannuleerd'
            GROUP BY p.id
            ORDER BY total_sold DESC
            LIMIT 5
        ");
        ?>
        
        <?php if ($topProducts && $topProducts->num_rows > 0): ?>
        <div class="space-y-3">
            <?php $rank = 1; while ($product = $topProducts->fetch_assoc()): ?>
            <div class="flex items-center justify-between p-3 rounded-xl glass-hover">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-teal-500/20 flex items-center justify-center text-teal-400 font-bold text-sm"><?= $rank++ ?></span>
                    <div>
                        <p class="font-semibold text-sm"><?= htmlspecialchars($product['name']) ?></p>
                        <p class="text-xs" style="color: var(--text-muted);">SKU: <?= htmlspecialchars($product['sku']) ?></p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-lg bg-emerald-500/20 text-emerald-400 text-sm font-semibold"><?= $product['total_sold'] ?>x</span>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <p class="text-center py-8" style="color: var(--text-muted);">Nog geen verkoopdata beschikbaar</p>
        <?php endif; ?>
    </div>
    
    <!-- RECENT ACTIVITY -->
    <div class="card-glass p-8">
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center">
                <i class="bi bi-activity text-xl text-blue-400"></i>
            </div>
            <h2 class="text-xl font-bold" style="color: var(--text-primary);">Recente Activiteit</h2>
        </div>
        
        <?php
        $recentActivity = $conn->query("
            SELECT 'order' as type, id, name as title, created_at, status 
            FROM orders 
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        ?>
        
        <?php if ($recentActivity && $recentActivity->num_rows > 0): ?>
        <div class="space-y-3">
            <?php while ($activity = $recentActivity->fetch_assoc()): ?>
            <div class="flex items-center justify-between p-3 rounded-xl glass-hover">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
                        <i class="bi bi-cart-check text-blue-400"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm">Order #<?= $activity['id'] ?></p>
                        <p class="text-xs" style="color: var(--text-muted);"><?= htmlspecialchars($activity['title']) ?></p>
                    </div>
                </div>
                <span class="text-xs" style="color: var(--text-muted);"><?= date('d/m H:i', strtotime($activity['created_at'])) ?></span>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <p class="text-center py-8" style="color: var(--text-muted);">Nog geen activiteit</p>
        <?php endif; ?>
    </div>
    
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
