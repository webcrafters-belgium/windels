<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<div class="card-glass p-8 text-center"><p style="color: var(--text-muted);">Ongeldig order-ID.</p></div>';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php';
    exit;
}

$order_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    echo '<div class="card-glass p-8 text-center"><p style="color: var(--text-muted);">Order niet gevonden.</p></div>';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php';
    exit;
}

$order = $order_result->fetch_assoc();

$item_stmt = $conn->prepare("SELECT oi.*, p.name AS product_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$order_items = $item_stmt->get_result();

$statusConfig = [
    'pending' => ['bg' => 'bg-amber-500/20', 'text' => 'text-amber-400', 'border' => 'border-amber-500/30', 'icon' => 'bi-hourglass-split'],
    'paid' => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/30', 'icon' => 'bi-check-circle-fill'],
    'shipped' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/30', 'icon' => 'bi-truck'],
    'cancelled' => ['bg' => 'bg-rose-500/20', 'text' => 'text-rose-400', 'border' => 'border-rose-500/30', 'icon' => 'bi-x-circle-fill'],
];
$config = $statusConfig[$order['status']] ?? $statusConfig['pending'];
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <span class="text-teal-400">#<?= $order['id'] ?></span> Order Detail
            </h1>
            <p class="text-lg flex items-center gap-3" style="color: var(--text-muted);">
                <span class="px-3 py-1.5 rounded-lg text-sm font-semibold border <?= $config['bg'] ?> <?= $config['text'] ?> <?= $config['border'] ?>">
                    <i class="bi <?= $config['icon'] ?> mr-1"></i><?= ucfirst($order['status'] ?? 'pending') ?>
                </span>
                <span><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
            </p>
        </div>
        <div class="flex gap-3">
            <a href="/admin/pages/orders/pdf_invoice.php?id=<?= $order['id'] ?>" target="_blank" class="glass px-4 py-2 rounded-xl font-semibold hover:bg-white/10 transition flex items-center gap-2">
                <i class="bi bi-file-earmark-pdf"></i>PDF Factuur
            </a>
            <a href="/pages/orders/index.php" class="glass px-4 py-2 rounded-xl font-semibold hover:bg-white/10 transition flex items-center gap-2">
                <i class="bi bi-arrow-left"></i>Terug
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- LEFT: Order Info & Items -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Status Update Form -->
        <div class="card-glass p-6">
            <h3 class="font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                <i class="bi bi-gear text-teal-400"></i>Status wijzigen
            </h3>
            <form method="post" action="update_status.php" class="flex items-center gap-4">
                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                <select name="status" class="px-4 py-2 rounded-xl glass border flex-1" style="border-color: var(--border-glass);">
                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>In afwachting</option>
                    <option value="paid" <?= $order['status'] == 'paid' ? 'selected' : '' ?>>Betaald</option>
                    <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Verzonden</option>
                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Geannuleerd</option>
                </select>
                <button type="submit" class="accent-bg text-white px-6 py-2 rounded-xl font-semibold hover:opacity-90 transition">
                    Bijwerken
                </button>
            </form>
        </div>

        <!-- Order Items -->
        <div class="card-glass p-6">
            <h3 class="font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                <i class="bi bi-box-seam text-teal-400"></i>Orderinhoud
            </h3>
            <?php if ($order_items->num_rows === 0): ?>
                <p style="color: var(--text-muted);">Geen producten gevonden.</p>
            <?php else: ?>
                <div class="overflow-x-auto rounded-xl">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b" style="border-color: var(--border-glass);">
                                <th class="text-left py-3 px-4 text-sm font-semibold" style="color: var(--text-muted);">Product</th>
                                <th class="text-right py-3 px-4 text-sm font-semibold" style="color: var(--text-muted);">Aantal</th>
                                <th class="text-right py-3 px-4 text-sm font-semibold" style="color: var(--text-muted);">Prijs</th>
                                <th class="text-right py-3 px-4 text-sm font-semibold" style="color: var(--text-muted);">Subtotaal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="--tw-divide-opacity: 0.1;">
                        <?php
                        $totaal = 0;
                        while ($item = $order_items->fetch_assoc()):
                            $sub = $item['total_price'];
                            $totaal += $sub;
                        ?>
                            <tr class="hover:bg-white/5">
                                <td class="py-3 px-4 font-medium"><?= $item['product_name'] ? htmlspecialchars($item['product_name']) : '<i style="color: var(--text-muted);">Product verwijderd</i>' ?></td>
                                <td class="py-3 px-4 text-right"><?= (int) $item['quantity'] ?></td>
                                <td class="py-3 px-4 text-right" style="color: var(--text-muted);">€ <?= number_format(($item['quantity'] > 0 ? $item['total_price'] / $item['quantity'] : 0), 2, ',', '.') ?></td>
                                <td class="py-3 px-4 text-right font-semibold text-emerald-400">€ <?= number_format($sub, 2, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Order Summary -->
        <div class="card-glass p-6">
            <h3 class="font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                <i class="bi bi-calculator text-teal-400"></i>Overzicht
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span style="color: var(--text-muted);">Subtotaal</span>
                    <span>€ <?= number_format($totaal, 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between">
                    <span style="color: var(--text-muted);">Verzendkosten</span>
                    <span>€ <?= number_format($order['shipping_cost'] ?? 0, 2, ',', '.') ?></span>
                </div>
                <div class="h-px bg-gradient-to-r from-transparent via-teal-500/30 to-transparent"></div>
                <div class="flex justify-between text-xl font-bold">
                    <span>Totaal</span>
                    <span class="text-emerald-400">€ <?= number_format($order['total_price'], 2, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: Customer Info -->
    <div class="space-y-6">
        <!-- Customer Info Card -->
        <div class="card-glass p-6">
            <h3 class="font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                <i class="bi bi-person text-teal-400"></i>Klantinformatie
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs uppercase tracking-wider" style="color: var(--text-muted);">Naam</label>
                    <p class="font-semibold"><?= htmlspecialchars($order['name'] ?? 'Onbekend') ?></p>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wider" style="color: var(--text-muted);">Email</label>
                    <p><?= htmlspecialchars($order['email'] ?? '-') ?></p>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wider" style="color: var(--text-muted);">Telefoon</label>
                    <p><?= htmlspecialchars($order['phone'] ?? '-') ?></p>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-wider" style="color: var(--text-muted);">Adres</label>
                    <p>
                        <?= htmlspecialchars(trim(($order['street'] ?? '') . ' ' . ($order['number'] ?? ''))) ?><br>
                        <?= htmlspecialchars(trim(($order['zipcode'] ?? '') . ' ' . ($order['city'] ?? ''))) ?><br>
                        <?= htmlspecialchars($order['country'] ?? '-') ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card-glass p-6">
            <h3 class="font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                <i class="bi bi-lightning text-teal-400"></i>Acties
            </h3>
            <div class="space-y-3">
                <form method="post" action="resend_invoice.php">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <button type="submit" class="w-full glass px-4 py-3 rounded-xl font-semibold hover:bg-white/10 transition flex items-center justify-center gap-2">
                        <i class="bi bi-send"></i>Factuur opnieuw verzenden
                    </button>
                </form>
                <a href="edit.php?id=<?= $order['id'] ?>" class="w-full glass px-4 py-3 rounded-xl font-semibold hover:bg-white/10 transition flex items-center justify-center gap-2">
                    <i class="bi bi-pencil"></i>Order bewerken
                </a>
            </div>
        </div>

        <!-- Tracking Card -->
        <div class="card-glass p-6">
            <h3 class="font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                <i class="bi bi-truck text-teal-400"></i>Tracking
            </h3>
            <?php if (!empty($order['tracking_url'])): ?>
                <a href="<?= htmlspecialchars($order['tracking_url']) ?>" target="_blank" class="accent-bg text-white px-4 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center justify-center gap-2">
                    <i class="bi bi-box-arrow-up-right"></i>
                    <?= htmlspecialchars($order['tracking_code'] ?? 'Volg zending') ?>
                </a>
            <?php else: ?>
                <p style="color: var(--text-muted);">Geen tracking beschikbaar.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
