<?php
require_once __DIR__ . '/../../../includes/header.php';

$admin_role = $_SESSION['admin_role'] ?? 'admin';

// Haal bestellingen op per status
$conceptOrders = [];
$sentOrders = [];
$endOrders = [];

if ($stmt = $conn->prepare("SELECT * FROM orders WHERE status = 'Concept' ORDER BY created_at DESC")) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $conceptOrders[] = $row;
    }
    $stmt->close();
}

if ($stmt = $conn->prepare("SELECT * FROM orders WHERE status IN ('Verzonden', 'Verwerken') ORDER BY updated_at DESC")) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $sentOrders[] = $row;
    }
    $stmt->close();
}

if ($stmt = $conn->prepare("SELECT * FROM orders WHERE status = 'Afgehandeld' ORDER BY updated_at DESC LIMIT 50")) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $endOrders[] = $row;
    }
    $stmt->close();
}

$canEdit = in_array($admin_role, ['Admin', 'admin', 'Filiaalmanager', 'Kantoormedewerker']);
?>

<!-- Main Content -->
<div class="max-w-7xl mx-auto animate-fadeInUp">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold glow-text">Winkel Bestellingen</h1>
            <p class="text-sm mt-1" style="color: var(--text-muted);">Overzicht van alle winkelbestellingen</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="/admin/pages/winkel/" 
               class="glass px-4 py-2 rounded-xl flex items-center space-x-2 hover:bg-white/10 transition">
                <i class="bi bi-arrow-left"></i>
                <span>Terug</span>
            </a>
            <?php if ($canEdit): ?>
                <a href="/admin/pages/winkel/new_order.php" 
                   class="accent-bg px-5 py-2 rounded-xl font-semibold text-white flex items-center space-x-2">
                    <i class="bi bi-plus-circle"></i>
                    <span>Nieuw Order</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="card-glass p-4 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center">
                <i class="bi bi-pencil-square text-xl text-amber-400"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= count($conceptOrders) ?></p>
                <p class="text-sm" style="color: var(--text-muted);">Concept</p>
            </div>
        </div>
        <div class="card-glass p-4 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                <i class="bi bi-truck text-xl text-blue-400"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= count($sentOrders) ?></p>
                <p class="text-sm" style="color: var(--text-muted);">Verzonden</p>
            </div>
        </div>
        <div class="card-glass p-4 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                <i class="bi bi-check-circle text-xl text-emerald-400"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= count($endOrders) ?></p>
                <p class="text-sm" style="color: var(--text-muted);">Afgehandeld</p>
            </div>
        </div>
    </div>

    <?php if ($canEdit && count($conceptOrders) > 0): ?>
    <!-- Concept Orders -->
    <div class="card-glass mb-8 overflow-hidden">
        <div class="p-4 border-b flex items-center space-x-3" style="border-color: var(--border-glass);">
            <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center">
                <i class="bi bi-pencil-square text-amber-400"></i>
            </div>
            <h2 class="text-lg font-semibold">Concept Bestellingen</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm" style="color: var(--text-muted); background: var(--bg-glass);">
                        <th class="px-4 py-3 font-medium">Order Code</th>
                        <th class="px-4 py-3 font-medium">Datum</th>
                        <th class="px-4 py-3 font-medium text-right">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($conceptOrders as $order): ?>
                        <tr class="border-t transition-colors hover:bg-white/5" style="border-color: var(--border-glass);">
                            <td class="px-4 py-3 font-mono"><?= htmlspecialchars($order['order_code']) ?></td>
                            <td class="px-4 py-3" style="color: var(--text-muted);"><?= date('d-m-Y H:i', strtotime($order['created_at'])) ?></td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="/admin/pages/winkel/view_order.php?order_id=<?= $order['id'] ?>" class="p-2 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition" title="Bekijken">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="/admin/pages/winkel/edit_order.php?id=<?= $order['id'] ?>" class="p-2 rounded-lg bg-amber-500/20 text-amber-400 hover:bg-amber-500/30 transition" title="Bewerken">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/admin/pages/winkel/send_to_warehouse.php?id=<?= $order['id'] ?>" class="p-2 rounded-lg bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30 transition" title="Verzenden">
                                        <i class="bi bi-send"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Verzonden Orders -->
    <div class="card-glass mb-8 overflow-hidden">
        <div class="p-4 border-b flex items-center space-x-3" style="border-color: var(--border-glass);">
            <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
                <i class="bi bi-truck text-blue-400"></i>
            </div>
            <h2 class="text-lg font-semibold">Verzonden Bestellingen</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm" style="color: var(--text-muted); background: var(--bg-glass);">
                        <th class="px-4 py-3 font-medium">Order Code</th>
                        <th class="px-4 py-3 font-medium">Datum</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium text-right">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sentOrders)): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center" style="color: var(--text-muted);">
                                Geen verzonden bestellingen
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($sentOrders as $order): ?>
                            <tr class="border-t transition-colors hover:bg-white/5" style="border-color: var(--border-glass);">
                                <td class="px-4 py-3 font-mono"><?= htmlspecialchars($order['order_code']) ?></td>
                                <td class="px-4 py-3" style="color: var(--text-muted);"><?= date('d-m-Y H:i', strtotime($order['updated_at'])) ?></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-lg text-sm <?= $order['status'] === 'Verzonden' ? 'bg-blue-500/20 text-blue-400' : 'bg-violet-500/20 text-violet-400' ?>">
                                        <?= htmlspecialchars($order['status']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="/admin/pages/winkel/view_order.php?order_id=<?= $order['id'] ?>" class="p-2 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition" title="Bekijken">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if ($order['status'] === 'Verzonden' || $order['status'] === 'Verwerken'): ?>
                                            <a href="/admin/pages/winkel/update_order_status.php?id=<?= $order['id'] ?>" class="p-2 rounded-lg bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30 transition" title="Status wijzigen">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Afgeronde Orders -->
    <div class="card-glass overflow-hidden">
        <div class="p-4 border-b flex items-center space-x-3" style="border-color: var(--border-glass);">
            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                <i class="bi bi-check-circle text-emerald-400"></i>
            </div>
            <h2 class="text-lg font-semibold">Afgeronde Bestellingen</h2>
            <span class="text-xs px-2 py-1 rounded-full glass" style="color: var(--text-muted);">Laatste 50</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm" style="color: var(--text-muted); background: var(--bg-glass);">
                        <th class="px-4 py-3 font-medium">Order Code</th>
                        <th class="px-4 py-3 font-medium">Datum Afgerond</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium text-right">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($endOrders)): ?>
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center" style="color: var(--text-muted);">
                                Geen afgeronde bestellingen
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($endOrders as $order): ?>
                            <tr class="border-t transition-colors hover:bg-white/5" style="border-color: var(--border-glass);">
                                <td class="px-4 py-3 font-mono"><?= htmlspecialchars($order['order_code']) ?></td>
                                <td class="px-4 py-3" style="color: var(--text-muted);"><?= date('d-m-Y H:i', strtotime($order['updated_at'])) ?></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-lg text-sm bg-emerald-500/20 text-emerald-400">
                                        <?= htmlspecialchars($order['status']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="/admin/pages/winkel/view_order.php?order_id=<?= $order['id'] ?>" class="p-2 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition" title="Bekijken">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>
