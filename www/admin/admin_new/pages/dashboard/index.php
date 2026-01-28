<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/admin_new/includes/header.php';

// Dashboard statistics
$stats = [];

// Total products
$result = $conn->query("SELECT COUNT(*) as count FROM products");
$stats['products'] = $result->fetch_assoc()['count'];

// Total orders
$result = $conn->query("SELECT COUNT(*) as count FROM orders");
$stats['orders'] = $result->fetch_assoc()['count'];

// Pending orders
$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'pending'");
$stats['pending_orders'] = $result->fetch_assoc()['count'];

// Recent orders (last 30 days)
$result = $conn->query("SELECT COUNT(*) as count FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$stats['recent_orders'] = $result->fetch_assoc()['count'];

// Low stock products
$result = $conn->query("SELECT COUNT(*) as count FROM products WHERE stock_quantity < 5");
$stats['low_stock'] = $result->fetch_assoc()['count'];

// Revenue this month
$result = $conn->query("SELECT COALESCE(SUM(total_price), 0) as revenue FROM orders WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) AND status != 'cancelled'");
$stats['monthly_revenue'] = $result->fetch_assoc()['revenue'];

// Recent orders for table
$recentOrders = $conn->query("
    SELECT o.id, o.name, o.email, o.total_price, o.status, o.created_at,
           COUNT(oi.id) as item_count
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    GROUP BY o.id
    ORDER BY o.created_at DESC
    LIMIT 10
");
?>

<!-- Dashboard Header -->
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">Welkom terug, <?= htmlspecialchars($currentUser['name']) ?>! 👋</h1>
    <p class="text-lg" style="color: var(--text-muted);">Hier is een overzicht van je webshop</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
    <!-- Total Products -->
    <div class="card-glass p-6 glass-hover cursor-pointer" onclick="location.href='/admin_new/pages/products/index.php'">
        <div class="flex items-center justify-between mb-4">
            <i class="bi bi-box-seam text-4xl accent-primary"></i>
            <span class="text-3xl font-bold"><?= number_format($stats['products']) ?></span>
        </div>
        <h3 class="text-lg font-semibold" style="color: var(--text-secondary);">Totaal Producten</h3>
        <p class="text-sm" style="color: var(--text-muted);">In catalogus</p>
    </div>
    
    <!-- Total Orders -->
    <div class="card-glass p-6 glass-hover cursor-pointer" onclick="location.href='/admin_new/pages/orders/index.php'">
        <div class="flex items-center justify-between mb-4">
            <i class="bi bi-receipt text-4xl text-blue-500"></i>
            <span class="text-3xl font-bold"><?= number_format($stats['orders']) ?></span>
        </div>
        <h3 class="text-lg font-semibold" style="color: var(--text-secondary);">Totaal Bestellingen</h3>
        <p class="text-sm" style="color: var(--text-muted);">Alle tijd</p>
    </div>
    
    <!-- Pending Orders -->
    <div class="card-glass p-6 glass-hover cursor-pointer" onclick="location.href='/admin_new/pages/orders/index.php?status=pending'">
        <div class="flex items-center justify-between mb-4">
            <i class="bi bi-hourglass-split text-4xl text-yellow-500"></i>
            <span class="text-3xl font-bold"><?= number_format($stats['pending_orders']) ?></span>
        </div>
        <h3 class="text-lg font-semibold" style="color: var(--text-secondary);">Openstaand</h3>
        <p class="text-sm" style="color: var(--text-muted);">Te verwerken</p>
    </div>
    
    <!-- Monthly Revenue -->
    <div class="card-glass p-6 glass-hover">
        <div class="flex items-center justify-between mb-4">
            <i class="bi bi-currency-euro text-4xl text-green-500"></i>
            <span class="text-3xl font-bold">€<?= number_format($stats['monthly_revenue'], 2, ',', '.') ?></span>
        </div>
        <h3 class="text-lg font-semibold" style="color: var(--text-secondary);">Omzet Deze Maand</h3>
        <p class="text-sm" style="color: var(--text-muted);"><?= date('F Y') ?></p>
    </div>
    
    <!-- Recent Orders -->
    <div class="card-glass p-6 glass-hover">
        <div class="flex items-center justify-between mb-4">
            <i class="bi bi-clock-history text-4xl text-purple-500"></i>
            <span class="text-3xl font-bold"><?= number_format($stats['recent_orders']) ?></span>
        </div>
        <h3 class="text-lg font-semibold" style="color: var(--text-secondary);">Laatste 30 Dagen</h3>
        <p class="text-sm" style="color: var(--text-muted);">Nieuwe bestellingen</p>
    </div>
    
    <!-- Low Stock -->
    <div class="card-glass p-6 glass-hover cursor-pointer <?= $stats['low_stock'] > 0 ? 'border-red-500' : '' ?>" onclick="location.href='/admin_new/pages/products/index.php?stock=low'">
        <div class="flex items-center justify-between mb-4">
            <i class="bi bi-exclamation-triangle text-4xl text-red-500"></i>
            <span class="text-3xl font-bold"><?= number_format($stats['low_stock']) ?></span>
        </div>
        <h3 class="text-lg font-semibold" style="color: var(--text-secondary);">Lage Voorraad</h3>
        <p class="text-sm" style="color: var(--text-muted);">Minder dan 5 stuks</p>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="card-glass p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            <i class="bi bi-clock-history accent-primary mr-2"></i>
            Recente Bestellingen
        </h2>
        <a href="/admin_new/pages/orders/index.php" class="accent-bg text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
            Bekijk Alle
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass);">
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Bestelling #</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Klant</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Artikelen</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Totaal</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Status</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Datum</th>
                    <th class="text-right py-3 px-4 font-semibold" style="color: var(--text-secondary);">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recentOrders && $recentOrders->num_rows > 0): ?>
                    <?php while ($order = $recentOrders->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-opacity-50 transition" style="border-color: var(--border-glass);">
                            <td class="py-4 px-4 font-mono">#<?= $order['id'] ?></td>
                            <td class="py-4 px-4">
                                <div class="font-semibold"><?= htmlspecialchars($order['name']) ?></div>
                                <div class="text-sm" style="color: var(--text-muted);"><?= htmlspecialchars($order['email']) ?></div>
                            </td>
                            <td class="py-4 px-4"><?= $order['item_count'] ?> artikel(en)</td>
                            <td class="py-4 px-4 font-semibold">€<?= number_format($order['total_price'], 2, ',', '.') ?></td>
                            <td class="py-4 px-4">
                                <?php
                                $statusColors = [
                                    'pending' => 'bg-yellow-500/20 text-yellow-600 border-yellow-500',
                                    'processing' => 'bg-blue-500/20 text-blue-600 border-blue-500',
                                    'completed' => 'bg-green-500/20 text-green-600 border-green-500',
                                    'cancelled' => 'bg-red-500/20 text-red-600 border-red-500',
                                ];
                                $colorClass = $statusColors[$order['status']] ?? 'bg-gray-500/20 text-gray-600 border-gray-500';
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border <?= $colorClass ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td class="py-4 px-4 text-sm"><?= date('d-m-Y H:i', strtotime($order['created_at'])) ?></td>
                            <td class="py-4 px-4 text-right">
                                <a href="/admin_new/pages/orders/view.php?id=<?= $order['id'] ?>" 
                                   class="inline-flex items-center px-3 py-1 rounded-lg glass-hover accent-primary font-semibold text-sm">
                                    <i class="bi bi-eye mr-1"></i> Bekijk
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="py-8 text-center" style="color: var(--text-muted);">
                            <i class="bi bi-inbox text-5xl mb-2 block"></i>
                            Geen recente bestellingen
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/admin_new/includes/footer.php'; ?>
