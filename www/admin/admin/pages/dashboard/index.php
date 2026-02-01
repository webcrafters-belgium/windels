<?php
require_once '../../includes/header.php';

// Check database connection
$dbAvailable = isset($conn) && $conn !== null;

// Dashboard statistics
$stats = [
    'products' => 0,
    'orders' => 0,
    'pending_orders' => 0,
    'recent_orders' => 0,
    'low_stock' => 0,
    'monthly_revenue' => 0
];
$recentOrders = null;

if ($dbAvailable) {
    // Total products
    $result = @$conn->query("SELECT COUNT(*) as count FROM products");
    if ($result) $stats['products'] = $result->fetch_assoc()['count'] ?? 0;

    // Total orders
    $result = @$conn->query("SELECT COUNT(*) as count FROM orders");
    if ($result) $stats['orders'] = $result->fetch_assoc()['count'] ?? 0;

    // Pending orders
    $result = @$conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
    if ($result) $stats['pending_orders'] = $result->fetch_assoc()['count'] ?? 0;

    // Recent orders (last 30 days)
    $result = @$conn->query("SELECT COUNT(*) as count FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
    if ($result) $stats['recent_orders'] = $result->fetch_assoc()['count'] ?? 0;

    // Low stock products
    $result = @$conn->query("SELECT COUNT(*) as count FROM products WHERE stock_quantity < 5");
    if ($result) $stats['low_stock'] = $result->fetch_assoc()['count'] ?? 0;

    // Revenue this month
    $result = @$conn->query("SELECT COALESCE(SUM(total_price), 0) as revenue FROM orders WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) AND status != 'cancelled'");
    if ($result) $stats['monthly_revenue'] = $result->fetch_assoc()['revenue'] ?? 0;

    // Recent orders for table
    $recentOrders = @$conn->query("
        SELECT o.id, o.name, o.email, o.total_price, o.status, o.created_at,
               COUNT(oi.id) as item_count
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        GROUP BY o.id
        ORDER BY o.created_at DESC
        LIMIT 10
    ");
}
?>

<?php if (!$dbAvailable): ?>
<!-- Database Connection Warning -->
<div class="card-glass p-6 mb-6 border-amber-500/50 bg-amber-500/10" data-testid="db-warning">
    <div class="flex items-center space-x-4">
        <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center">
            <i class="bi bi-database-exclamation text-2xl text-amber-400"></i>
        </div>
        <div>
            <h3 class="font-bold text-amber-400">Database niet beschikbaar</h3>
            <p class="text-sm" style="color: var(--text-muted);">De database verbinding is niet actief. Statistieken kunnen niet worden geladen.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Dashboard Header -->
<div class="mb-10" data-testid="dashboard-header">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-3 glow-text" style="color: var(--text-primary);">
                Welkom terug, <span class="bg-gradient-to-r from-teal-400 to-cyan-400 bg-clip-text text-transparent"><?= htmlspecialchars($currentUser['name']) ?></span>! 
            </h1>
            <p class="text-lg flex items-center" style="color: var(--text-muted);">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse mr-2"></span>
                Hier is een overzicht van je webshop
            </p>
        </div>
        <div class="text-right">
            <p class="text-sm font-medium" style="color: var(--text-muted);">Vandaag</p>
            <p class="text-2xl font-bold" style="color: var(--text-secondary);"><?= date('d M Y') ?></p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mb-10 stagger-children" data-testid="stats-grid">
    <!-- Total Products -->
    <div class="card-glass p-5 glass-hover cursor-pointer group" onclick="location.href='/admin/pages/products/index.php'" data-testid="stat-products">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500/30 to-emerald-500/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-box-seam-fill text-2xl text-teal-400"></i>
            </div>
            <i class="bi bi-arrow-up-right text-sm opacity-0 group-hover:opacity-100 transition-opacity" style="color: var(--accent);"></i>
        </div>
        <span class="text-3xl font-bold block mb-1"><?= number_format($stats['products']) ?></span>
        <h3 class="text-sm font-medium" style="color: var(--text-muted);">Producten</h3>
    </div>
    
    <!-- Total Orders -->
    <div class="card-glass p-5 glass-hover cursor-pointer group" onclick="location.href='/admin/pages/orders/index.php'" data-testid="stat-orders">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-receipt text-2xl text-blue-400"></i>
            </div>
            <i class="bi bi-arrow-up-right text-sm opacity-0 group-hover:opacity-100 transition-opacity text-blue-400"></i>
        </div>
        <span class="text-3xl font-bold block mb-1"><?= number_format($stats['orders']) ?></span>
        <h3 class="text-sm font-medium" style="color: var(--text-muted);">Bestellingen</h3>
    </div>
    
    <!-- Pending Orders -->
    <div class="card-glass p-5 glass-hover cursor-pointer group" onclick="location.href='/admin/pages/orders/index.php?status=pending'" data-testid="stat-pending">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/30 to-orange-500/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-hourglass-split text-2xl text-amber-400 icon-pulse"></i>
            </div>
            <?php if ($stats['pending_orders'] > 0): ?>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500/20 text-amber-400 status-pulse">Actie</span>
            <?php endif; ?>
        </div>
        <span class="text-3xl font-bold block mb-1"><?= number_format($stats['pending_orders']) ?></span>
        <h3 class="text-sm font-medium" style="color: var(--text-muted);">Openstaand</h3>
    </div>
    
    <!-- Monthly Revenue -->
    <div class="card-glass p-5 glass-hover group" data-testid="stat-revenue">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/30 to-green-500/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-currency-euro text-2xl text-emerald-400"></i>
            </div>
        </div>
        <span class="text-2xl font-bold block mb-1 bg-gradient-to-r from-emerald-400 to-green-400 bg-clip-text text-transparent">€<?= number_format($stats['monthly_revenue'], 0, ',', '.') ?></span>
        <h3 class="text-sm font-medium" style="color: var(--text-muted);">Deze Maand</h3>
    </div>
    
    <!-- Recent Orders -->
    <div class="card-glass p-5 glass-hover group" data-testid="stat-recent">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500/30 to-purple-500/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-clock-history text-2xl text-violet-400"></i>
            </div>
        </div>
        <span class="text-3xl font-bold block mb-1"><?= number_format($stats['recent_orders']) ?></span>
        <h3 class="text-sm font-medium" style="color: var(--text-muted);">30 Dagen</h3>
    </div>
    
    <!-- Low Stock -->
    <div class="card-glass p-5 glass-hover cursor-pointer group <?= $stats['low_stock'] > 0 ? 'border-rose-500/50' : '' ?>" onclick="location.href='/admin/pages/products/index.php?stock=low'" data-testid="stat-low-stock">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500/30 to-red-500/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-exclamation-triangle-fill text-2xl text-rose-400 <?= $stats['low_stock'] > 0 ? 'icon-pulse' : '' ?>"></i>
            </div>
            <?php if ($stats['low_stock'] > 0): ?>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-rose-500/20 text-rose-400">Alert</span>
            <?php endif; ?>
        </div>
        <span class="text-3xl font-bold block mb-1 <?= $stats['low_stock'] > 0 ? 'text-rose-400' : '' ?>"><?= number_format($stats['low_stock']) ?></span>
        <h3 class="text-sm font-medium" style="color: var(--text-muted);">Lage Voorraad</h3>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="card-glass p-8" data-testid="recent-orders-table">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500/30 to-cyan-500/30 flex items-center justify-center">
                <i class="bi bi-clock-history text-xl text-teal-400"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
                Recente Bestellingen
            </h2>
        </div>
        <a href="/admin/pages/orders/index.php" class="accent-bg text-white px-5 py-2.5 rounded-xl font-semibold hover:opacity-90 transition flex items-center space-x-2 group" data-testid="view-all-orders-btn">
            <span>Bekijk Alle</span>
            <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>
    
    <div class="overflow-x-auto rounded-xl">
        <table class="w-full" data-testid="orders-table">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass); background: var(--bg-glass);">
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Order #</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Klant</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Items</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Totaal</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Status</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Datum</th>
                    <th class="text-right py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Actie</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="--tw-divide-opacity: 0.1;">
                <?php if ($recentOrders && $recentOrders->num_rows > 0): ?>
                    <?php while ($order = $recentOrders->fetch_assoc()): ?>
                        <tr class="group hover:bg-white/5 transition-colors" data-testid="order-row-<?= $order['id'] ?>">
                            <td class="py-4 px-4">
                                <span class="font-mono font-bold text-teal-400">#<?= $order['id'] ?></span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-500/30 to-slate-600/30 flex items-center justify-center text-sm font-bold">
                                        <?= strtoupper(substr($order['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="font-semibold"><?= htmlspecialchars($order['name']) ?></div>
                                        <div class="text-xs truncate max-w-[150px]" style="color: var(--text-muted);"><?= htmlspecialchars($order['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-500/20"><?= $order['item_count'] ?> items</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="font-bold text-emerald-400">€<?= number_format($order['total_price'], 2, ',', '.') ?></span>
                            </td>
                            <td class="py-4 px-4">
                                <?php
                                $statusConfig = [
                                    'pending' => ['bg' => 'bg-amber-500/20', 'text' => 'text-amber-400', 'border' => 'border-amber-500/50', 'icon' => 'bi-hourglass-split'],
                                    'processing' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/50', 'icon' => 'bi-gear-wide-connected'],
                                    'completed' => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/50', 'icon' => 'bi-check-circle-fill'],
                                    'cancelled' => ['bg' => 'bg-rose-500/20', 'text' => 'text-rose-400', 'border' => 'border-rose-500/50', 'icon' => 'bi-x-circle-fill'],
                                ];
                                $config = $statusConfig[$order['status']] ?? ['bg' => 'bg-slate-500/20', 'text' => 'text-slate-400', 'border' => 'border-slate-500/50', 'icon' => 'bi-question-circle'];
                                ?>
                                <span class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border <?= $config['bg'] ?> <?= $config['text'] ?> <?= $config['border'] ?>">
                                    <i class="bi <?= $config['icon'] ?>"></i>
                                    <span><?= ucfirst($order['status']) ?></span>
                                </span>
                            </td>
                            <td class="py-4 px-4 text-sm" style="color: var(--text-muted);"><?= date('d M, H:i', strtotime($order['created_at'])) ?></td>
                            <td class="py-4 px-4 text-right">
                                <a href="/admin/pages/orders/detail.php?id=<?= $order['id'] ?>" 
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold text-teal-400 hover:bg-teal-500/20 transition-colors"
                                   data-testid="view-order-<?= $order['id'] ?>">
                                    <i class="bi bi-eye-fill mr-1.5"></i> Bekijk
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="py-16 text-center" data-testid="no-orders-message">
                            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-500/20 to-slate-600/20 flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-inbox text-4xl" style="color: var(--text-muted);"></i>
                            </div>
                            <p class="text-lg font-semibold mb-1" style="color: var(--text-secondary);">Geen bestellingen</p>
                            <p class="text-sm" style="color: var(--text-muted);">Bestellingen verschijnen hier zodra ze binnenkomen</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
