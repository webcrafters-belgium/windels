<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

// Filters ophalen
$search    = $_GET['search'] ?? '';
$status    = $_GET['status'] ?? '';
$payment   = $_GET['payment'] ?? '';
$startDate = $_GET['start'] ?? '';
$endDate   = $_GET['end'] ?? '';

// QUERY
$q = "SELECT * FROM orders WHERE 1";
$p = [];

if ($search !== '') {
    $q .= " AND (name LIKE ? OR email LIKE ? OR id = ?)";
    $term = "%$search%";
    $p[] = $term; $p[] = $term; $p[] = $search;
}
if ($status !== '') {
    $q .= " AND status = ?";
    $p[] = $status;
}
if ($payment !== '') {
    $q .= " AND payment_method = ?";
    $p[] = $payment;
}
if ($startDate !== '') {
    $q .= " AND created_at >= ?";
    $p[] = $startDate . " 00:00:00";
}
if ($endDate !== '') {
    $q .= " AND created_at <= ?";
    $p[] = $endDate . " 23:59:59";
}

$q .= " ORDER BY created_at DESC LIMIT 200";
$stmt = $conn->prepare($q);
$stmt->execute($p);
$result = $stmt->get_result();
?>

<style>
    .invoice-dropdown-panel {
        background: rgba(15, 23, 42, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.35);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
        min-width: 11rem;
        padding: 0.35rem 0;
        border-radius: 1rem;
        backdrop-filter: blur(18px) saturate(180%);
    }

    .invoice-dropdown-panel a {
        color: var(--text-secondary);
    }

    .invoice-dropdown-panel a:hover {
        background: rgba(255, 255, 255, 0.08);
        color: var(--text-primary);
    }
</style>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-receipt accent-primary mr-3"></i>Bestellingen
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Overzicht van recente orders</p>
        </div>
    </div>
</div>

<!-- FILTERS -->
<div class="card-glass p-6 mb-8">
    <form method="get" class="grid grid-cols-1 md:grid-cols-6 gap-4">

        <!-- Zoekbalk -->
        <div class="flex flex-col col-span-2">
            <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Zoek klant / e-mail / ID</label>
            <input type="text" name="search"
                   value="<?= htmlspecialchars($search) ?>"
                   placeholder="Bijv: Jan, 123, email"
                   class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
        </div>

        <!-- Status -->
        <div class="flex flex-col">
            <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Status</label>
            <select name="status" class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
                <option value="">Alle</option>
                <option value="pending" <?= $status=='pending'?'selected':'' ?>>In afwachting</option>
                <option value="paid" <?= $status=='paid'?'selected':'' ?>>Betaald</option>
                <option value="shipped" <?= $status=='shipped'?'selected':'' ?>>Verzonden</option>
                <option value="completed" <?= $status=='completed'?'selected':'' ?>>Afgerond</option>
                <option value="cancelled" <?= $status=='cancelled'?'selected':'' ?>>Geannuleerd</option>
            </select>
        </div>

        <!-- Betaalmethode -->
        <div class="flex flex-col">
            <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Betaalwijze</label>
            <select name="payment" class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
                <option value="">Alle</option>
                <option value="bancontact" <?= $payment=='bancontact'?'selected':'' ?>>Bancontact</option>
                <option value="creditcard" <?= $payment=='creditcard'?'selected':'' ?>>Creditcard</option>
                <option value="mollie" <?= $payment=='mollie'?'selected':'' ?>>Mollie</option>
            </select>
        </div>

        <!-- Startdatum -->
        <div class="flex flex-col">
            <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Vanaf</label>
            <input type="date" name="start"
                   value="<?= htmlspecialchars($startDate) ?>"
                   class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
        </div>

        <!-- Einddatum -->
        <div class="flex flex-col">
            <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Tot</label>
            <input type="date" name="end"
                   value="<?= htmlspecialchars($endDate) ?>"
                   class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
        </div>

        <!-- Submit Buttons -->
        <div class="md:col-span-6 flex gap-3 mt-2">
            <button type="submit" class="accent-bg text-white px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
                <i class="bi bi-search"></i>Filteren
            </button>
            <a href="/admin/pages/orders/index.php" class="glass px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
                <i class="bi bi-x-circle"></i>Reset
            </a>
        </div>
    </form>
</div>

<!-- ORDERS TABLE -->
<div class="card-glass p-8">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center">
                <i class="bi bi-receipt text-xl text-blue-400"></i>
            </div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Resultaten</h2>
        </div>
        <span class="px-4 py-2 rounded-xl glass text-sm font-medium" style="color: var(--text-muted);">
            <?= $result->num_rows ?> bestellingen
        </span>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <div class="overflow-x-auto rounded-xl">
            <table class="w-full">
                <thead>
                    <tr class="border-b" style="border-color: var(--border-glass); background: var(--bg-glass);">
                        <th class="py-4 px-4 text-left"><input type="checkbox" id="select-all" class="rounded"></th>
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">ID</th>
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Klant</th>
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">E-mail</th>
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Datum</th>
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Status</th>
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Betaalwijze</th>
                        <th class="text-left py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Totaal</th>
                        <th class="text-right py-4 px-4 font-semibold text-sm uppercase tracking-wider" style="color: var(--text-muted);">Acties</th>
                    </tr>
                </thead>

                <tbody class="divide-y" style="--tw-divide-opacity: 0.1;">
                <?php while ($order = $result->fetch_assoc()): ?>
                    <?php
                    $statusConfig = [
                        'pending' => ['bg' => 'bg-amber-500/20', 'text' => 'text-amber-400', 'border' => 'border-amber-500/30', 'icon' => 'bi-hourglass-split'],
                        'paid' => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/30', 'icon' => 'bi-check-circle-fill'],
                        'shipped' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/30', 'icon' => 'bi-truck'],
                        'completed' => ['bg' => 'bg-green-500/20', 'text' => 'text-green-400', 'border' => 'border-green-500/30', 'icon' => 'bi-check2-all'],
                        'cancelled' => ['bg' => 'bg-rose-500/20', 'text' => 'text-rose-400', 'border' => 'border-rose-500/30', 'icon' => 'bi-x-circle-fill'],
                        // Legacy NL statuses
                        'in behandeling' => ['bg' => 'bg-amber-500/20', 'text' => 'text-amber-400', 'border' => 'border-amber-500/30', 'icon' => 'bi-hourglass-split'],
                        'betaald' => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/30', 'icon' => 'bi-check-circle-fill'],
                        'verzonden' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/30', 'icon' => 'bi-truck'],
                        'afgerond' => ['bg' => 'bg-green-500/20', 'text' => 'text-green-400', 'border' => 'border-green-500/30', 'icon' => 'bi-check2-all'],
                        'geannuleerd' => ['bg' => 'bg-rose-500/20', 'text' => 'text-rose-400', 'border' => 'border-rose-500/30', 'icon' => 'bi-x-circle-fill'],
                    ];
                    $statusLabels = [
                        'pending' => 'In afwachting',
                        'paid' => 'Betaald',
                        'shipped' => 'Verzonden',
                        'completed' => 'Afgerond',
                        'cancelled' => 'Geannuleerd',
                        'in behandeling' => 'In behandeling',
                        'betaald' => 'Betaald',
                        'verzonden' => 'Verzonden',
                        'afgerond' => 'Afgerond',
                        'geannuleerd' => 'Geannuleerd',
                    ];
                    $config = $statusConfig[$order['status']] ?? ['bg' => 'bg-slate-500/20', 'text' => 'text-slate-400', 'border' => 'border-slate-500/30', 'icon' => 'bi-question-circle'];
                    ?>
                    <tr class="group hover:bg-white/5 transition-colors">
                        <td class="py-4 px-4">
                            <input type="checkbox" class="order-checkbox rounded" value="<?= $order['id'] ?>">
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-mono font-bold text-teal-400">#<?= $order['id'] ?></span>
                        </td>
                        <td class="py-4 px-4 font-semibold"><?= htmlspecialchars($order['name']) ?></td>
                        <td class="py-4 px-4 text-sm" style="color: var(--text-muted);"><?= htmlspecialchars($order['email']) ?></td>
                        <td class="py-4 px-4 text-sm" style="color: var(--text-muted);"><?= date('d-m-Y H:i', strtotime($order['created_at'])) ?></td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border <?= $config['bg'] ?> <?= $config['text'] ?> <?= $config['border'] ?>">
                                <i class="bi <?= $config['icon'] ?>"></i>
                                <span><?= htmlspecialchars($statusLabels[$order['status']] ?? (string)$order['status']) ?></span>
                            </span>
                        </td>
                        <td class="py-4 px-4" style="color: var(--text-muted);"><?= htmlspecialchars($order['payment_method'] ?: '-') ?></td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-emerald-400">€<?= number_format($order['total_price'], 2, ',', '.') ?></span>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="detail.php?id=<?= $order['id'] ?>" class="p-2 rounded-lg glass-hover text-blue-400 hover:bg-blue-500/20" title="Bekijk">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="/API/orders/resend_confirmation/resend_confirmation.php?order_id=<?= $order['id'] ?>" class="p-2 rounded-lg glass-hover text-indigo-400 hover:bg-indigo-500/20" title="Verstuur">
                                    <i class="bi bi-send-fill"></i>
                                </a>
                                <div class="relative group">
                                    <button type="button" class="p-2 rounded-lg glass-hover hover:bg-white/10 flex items-center gap-1 text-sm" title="Factuuropties" style="color: var(--text-muted);">
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                        <span class="sr-only">Factuur</span>
                                    </button>
                                    <div class="absolute right-0 mt-2 w-40 invoice-dropdown-panel shadow-lg glass text-sm z-10 hidden group-hover:block">
                                        <a href="/API/orders/download_invoice.php?order_id=<?= $order['id'] ?>" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-800" target="_blank">
                                            <i class="bi bi-file-earmark-pdf-fill me-2"></i>PDF
                                        </a>
                                        <a href="/admin/pages/orders/xml_invoice.php?id=<?= $order['id'] ?>" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-800" target="_blank">
                                            <i class="bi bi-code-slash me-2"></i>XML
                                        </a>
                                    </div>
                                </div>
                                <a href="/API/orders/packing_slip.php?order_id=<?= $order['id'] ?>" class="p-2 rounded-lg glass-hover text-emerald-400 hover:bg-emerald-500/20" title="Pakbon">
                                    <i class="bi bi-box-seam-fill"></i>
                                </a>
                                <a href="edit.php?id=<?= $order['id'] ?>" class="p-2 rounded-lg glass-hover text-amber-400 hover:bg-amber-500/20" title="Bewerken">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <button onclick="deleteOrder(<?= $order['id'] ?>)" class="p-2 rounded-lg glass-hover text-rose-400 hover:bg-rose-500/20" title="Verwijderen">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
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
                <i class="bi bi-inbox text-4xl" style="color: var(--text-muted);"></i>
            </div>
            <p class="text-lg font-semibold mb-1" style="color: var(--text-secondary);">Geen bestellingen gevonden</p>
            <p class="text-sm" style="color: var(--text-muted);">Probeer je filters aan te passen</p>
        </div>
    <?php endif; ?>
</div>

<script>
    function deleteOrder(id) {
        if (!confirm("Weet je zeker dat je bestelling #" + id + " wilt verwijderen?")) return;

        fetch('/API/orders/delete.php?id=' + id)
            .then(r => r.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            });
    }

    document.getElementById('select-all')?.addEventListener('click', function () {
        document.querySelectorAll('.order-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });
</script>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
