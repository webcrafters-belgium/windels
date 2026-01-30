<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

// Fetch statistics
$totalProducts = $conn->query("SELECT COUNT(*) as cnt FROM products")->fetch_assoc()['cnt'] ?? 0;
$totalOrders = $conn->query("SELECT COUNT(*) as cnt FROM orders")->fetch_assoc()['cnt'] ?? 0;
$totalCustomers = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE role = 'customer'")->fetch_assoc()['cnt'] ?? 0;
$pendingOrders = $conn->query("SELECT COUNT(*) as cnt FROM orders WHERE status = 'in behandeling'")->fetch_assoc()['cnt'] ?? 0;

// Recent orders
$recentOrders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
        <i class="bi bi-grid-1x2-fill accent-primary mr-3"></i>Dashboard
    </h1>
    <p class="text-lg" style="color: var(--text-muted);">Welkom terug, <?= htmlspecialchars($username) ?>!</p>
</div>

<!-- STATS GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 stagger-children">
    <a href="/admin/pages/products/" class="card-glass p-6 group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500/30 to-cyan-500/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-box-seam-fill text-2xl text-teal-400"></i>
            </div>
            <span class="text-3xl font-bold" style="color: var(--text-primary);"><?= number_format($totalProducts) ?></span>
        </div>
        <h3 class="font-semibold" style="color: var(--text-secondary);">Producten</h3>
        <p class="text-sm" style="color: var(--text-muted);">Totaal aantal</p>
    </a>

    <a href="/admin/pages/orders/" class="card-glass p-6 group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-receipt text-2xl text-blue-400"></i>
            </div>
            <span class="text-3xl font-bold" style="color: var(--text-primary);"><?= number_format($totalOrders) ?></span>
        </div>
        <h3 class="font-semibold" style="color: var(--text-secondary);">Bestellingen</h3>
        <p class="text-sm" style="color: var(--text-muted);">Totaal aantal</p>
    </a>

    <a href="/admin/pages/customers/" class="card-glass p-6 group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500/30 to-purple-500/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-people-fill text-2xl text-violet-400"></i>
            </div>
            <span class="text-3xl font-bold" style="color: var(--text-primary);"><?= number_format($totalCustomers) ?></span>
        </div>
        <h3 class="font-semibold" style="color: var(--text-secondary);">Klanten</h3>
        <p class="text-sm" style="color: var(--text-muted);">Geregistreerd</p>
    </a>

    <a href="/admin/pages/orders/?status=in+behandeling" class="card-glass p-6 group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/30 to-orange-500/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="bi bi-hourglass-split text-2xl text-amber-400"></i>
            </div>
            <span class="text-3xl font-bold" style="color: var(--text-primary);"><?= number_format($pendingOrders) ?></span>
        </div>
        <h3 class="font-semibold" style="color: var(--text-secondary);">In behandeling</h3>
        <p class="text-sm" style="color: var(--text-muted);">Openstaande orders</p>
    </a>
</div>

<!-- RECENT ORDERS -->
<div class="card-glass p-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center">
                <i class="bi bi-clock-history text-xl text-blue-400"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Recente Bestellingen</h2>
        </div>
        <a href="/admin/pages/orders/" class="accent-bg text-white px-4 py-2 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
            Bekijk alle <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <?php if ($recentOrders && $recentOrders->num_rows > 0): ?>
    <div class="overflow-x-auto rounded-xl">
        <table class="w-full">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass); background: var(--bg-glass);">
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">ID</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Klant</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Datum</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Status</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Totaal</th>
                    <th class="text-right py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Acties</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="--tw-divide-opacity: 0.1;">
            <?php while ($order = $recentOrders->fetch_assoc()): 
                $statusColors = [
                    'in behandeling' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                    'betaald' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                    'verzonden' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                    'geannuleerd' => 'bg-rose-500/20 text-rose-400 border-rose-500/30',
                ];
                $statusColor = $statusColors[$order['status']] ?? 'bg-slate-500/20 text-slate-400 border-slate-500/30';
            ?>
                <tr class="group hover:bg-white/5 transition-colors">
                    <td class="py-4 px-4">
                        <span class="font-mono font-bold text-teal-400">#<?= $order['id'] ?></span>
                    </td>
                    <td class="py-4 px-4 font-semibold"><?= htmlspecialchars($order['name']) ?></td>
                    <td class="py-4 px-4 text-sm" style="color: var(--text-muted);"><?= date('d-m-Y H:i', strtotime($order['created_at'])) ?></td>
                    <td class="py-4 px-4">
                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold border <?= $statusColor ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </td>
                    <td class="py-4 px-4">
                        <span class="font-bold text-emerald-400">€<?= number_format($order['total_price'], 2, ',', '.') ?></span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <a href="/admin/pages/orders/detail.php?id=<?= $order['id'] ?>" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold text-teal-400 hover:bg-teal-500/20 transition-colors">
                            <i class="bi bi-eye-fill mr-1"></i> Bekijk
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="py-12 text-center">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-slate-500/20 to-slate-600/20 flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-inbox text-3xl" style="color: var(--text-muted);"></i>
        </div>
        <p class="text-lg font-semibold mb-1" style="color: var(--text-secondary);">Geen recente bestellingen</p>
        <p class="text-sm" style="color: var(--text-muted);">Nieuwe bestellingen verschijnen hier</p>
    </div>
    <?php endif; ?>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
