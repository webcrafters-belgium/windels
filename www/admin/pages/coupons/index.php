<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

// Fetch coupons
$result = $conn->query("SELECT * FROM coupons ORDER BY created_at DESC");
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-tags-fill accent-primary mr-3"></i>Kortingscodes
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Beheer alle kortingscodes</p>
        </div>
        <a href="/admin/customers/coupons/" class="accent-bg text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
            <i class="bi bi-plus-circle"></i>
            Nieuwe code
        </a>
    </div>
</div>

<!-- COUPONS TABLE -->
<div class="card-glass p-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500/30 to-pink-500/30 flex items-center justify-center">
                <i class="bi bi-percent text-xl text-rose-400"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Alle Kortingscodes</h2>
        </div>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
    <div class="overflow-x-auto rounded-xl">
        <table class="w-full">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass); background: var(--bg-glass);">
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Code</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Korting</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Type</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Geldig tot</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Gebruikt</th>
                    <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Status</th>
                    <th class="text-right py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Acties</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="--tw-divide-opacity: 0.1;">
            <?php while ($coupon = $result->fetch_assoc()): 
                $isActive = !isset($coupon['expiry_date']) || strtotime($coupon['expiry_date']) > time();
                $statusColor = $isActive ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border-rose-500/30';
            ?>
                <tr class="group hover:bg-white/5 transition-colors">
                    <td class="py-4 px-4">
                        <span class="font-mono font-bold text-teal-400"><?= htmlspecialchars($coupon['code']) ?></span>
                    </td>
                    <td class="py-4 px-4">
                        <span class="font-bold text-rose-400">
                            <?php if (isset($coupon['discount_type']) && $coupon['discount_type'] === 'percentage'): ?>
                                <?= $coupon['discount_value'] ?>%
                            <?php else: ?>
                                €<?= number_format($coupon['discount_value'] ?? $coupon['discount'] ?? 0, 2, ',', '.') ?>
                            <?php endif; ?>
                        </span>
                    </td>
                    <td class="py-4 px-4 text-sm" style="color: var(--text-muted);">
                        <?= ucfirst($coupon['discount_type'] ?? 'percentage') ?>
                    </td>
                    <td class="py-4 px-4 text-sm" style="color: var(--text-muted);">
                        <?= isset($coupon['expiry_date']) ? date('d/m/Y', strtotime($coupon['expiry_date'])) : 'Geen limiet' ?>
                    </td>
                    <td class="py-4 px-4">
                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-500/20 text-slate-400 border border-slate-500/30">
                            <?= $coupon['times_used'] ?? 0 ?>x
                        </span>
                    </td>
                    <td class="py-4 px-4">
                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold border <?= $statusColor ?>">
                            <?= $isActive ? 'Actief' : 'Verlopen' ?>
                        </span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="/admin/customers/coupons/?edit=<?= $coupon['id'] ?>" class="p-2 rounded-lg glass-hover text-amber-400 hover:bg-amber-500/20" title="Bewerken">
                                <i class="bi bi-pencil-fill"></i>
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
            <i class="bi bi-tags text-4xl" style="color: var(--text-muted);"></i>
        </div>
        <p class="text-lg font-semibold mb-1" style="color: var(--text-secondary);">Geen kortingscodes gevonden</p>
        <p class="text-sm" style="color: var(--text-muted);">Maak je eerste kortingscode aan</p>
    </div>
    <?php endif; ?>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
