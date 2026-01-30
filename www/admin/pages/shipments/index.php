<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

// Fetch orders with shipping status
$result = $conn->query("
    SELECT * FROM orders 
    WHERE status IN ('betaald', 'verzonden') 
    ORDER BY created_at DESC 
    LIMIT 100
");
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
        <i class="bi bi-truck accent-primary mr-3"></i>Verzendingen
    </h1>
    <p class="text-lg" style="color: var(--text-muted);">Beheer verzendingen en tracking</p>
</div>

<!-- SHIPMENTS TABLE -->
<div class="card-glass p-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center">
                <i class="bi bi-box-seam text-xl text-blue-400"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Verzendlijst</h2>
        </div>
        <span class="px-4 py-2 rounded-xl glass text-sm font-medium" style="color: var(--text-muted);">
            <?= $result ? $result->num_rows : 0 ?> verzendingen
        </span>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
    <div class="overflow-x-auto rounded-xl">
        <table class="w-full">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass); background: var(--bg-glass);">
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Order ID</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Klant</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Adres</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Status</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Tracking</th>
                    <th class="text-right py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Acties</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="--tw-divide-opacity: 0.1;">
            <?php while ($order = $result->fetch_assoc()): 
                $isShipped = $order['status'] === 'verzonden';
                $statusColor = $isShipped ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-amber-500/20 text-amber-400 border-amber-500/30';
            ?>
                <tr class="group hover:bg-white/5 transition-colors">
                    <td class="py-4 px-4">
                        <span class="font-mono font-bold text-teal-400">#<?= $order['id'] ?></span>
                    </td>
                    <td class="py-4 px-4">
                        <div>
                            <span class="font-semibold"><?= htmlspecialchars($order['name']) ?></span>
                            <p class="text-xs" style="color: var(--text-muted);"><?= htmlspecialchars($order['email']) ?></p>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-sm" style="color: var(--text-muted);">
                        <?= htmlspecialchars($order['shipping_address'] ?? $order['address'] ?? '-') ?><br>
                        <?= htmlspecialchars(($order['shipping_postal_code'] ?? $order['postal_code'] ?? '') . ' ' . ($order['shipping_city'] ?? $order['city'] ?? '')) ?>
                    </td>
                    <td class="py-4 px-4">
                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold border <?= $statusColor ?>">
                            <?= $isShipped ? 'Verzonden' : 'Klaar voor verzending' ?>
                        </span>
                    </td>
                    <td class="py-4 px-4">
                        <?php if (!empty($order['tracking_code'])): ?>
                            <a href="<?= htmlspecialchars($order['tracking_url'] ?? '#') ?>" target="_blank" class="text-teal-400 hover:underline text-sm">
                                <?= htmlspecialchars($order['tracking_code']) ?>
                            </a>
                        <?php else: ?>
                            <span class="text-sm" style="color: var(--text-muted);">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/admin/pages/orders/detail.php?id=<?= $order['id'] ?>" class="p-2 rounded-lg glass-hover text-blue-400 hover:bg-blue-500/20" title="Bekijk">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="/API/orders/packing_slip.php?order_id=<?= $order['id'] ?>" class="p-2 rounded-lg glass-hover text-emerald-400 hover:bg-emerald-500/20" title="Pakbon">
                                <i class="bi bi-file-earmark-text"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="py-16 text-center">
        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-500/20 to-slate-600/20 flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-box text-4xl" style="color: var(--text-muted);"></i>
        </div>
        <p class="text-lg font-semibold mb-1" style="color: var(--text-secondary);">Geen verzendingen gevonden</p>
        <p class="text-sm" style="color: var(--text-muted);">Betaalde bestellingen verschijnen hier</p>
    </div>
    <?php endif; ?>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
