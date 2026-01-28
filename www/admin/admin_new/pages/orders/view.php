<?php
require_once '../../includes/header.php';

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$orderId) {
    header('Location: /admin_new/pages/orders/index.php');
    exit;
}

// Fetch order details
$stmt = $conn->prepare(
    "SELECT o.* FROM orders o WHERE o.id = ?"
);
$stmt->bind_param('i', $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    header('Location: /admin_new/pages/orders/index.php');
    exit;
}

// Fetch order items
$stmt = $conn->prepare(
    "SELECT oi.*, p.name as product_name, p.sku
     FROM order_items oi
     LEFT JOIN products p ON oi.product_id = p.id
     WHERE oi.order_id = ?"
);
$stmt->bind_param('i', $orderId);
$stmt->execute();
$items = $stmt->get_result();
$stmt->close();
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">
                Bestelling #<?= $order['id'] ?>
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Geplaatst op <?= date('d-m-Y H:i', strtotime($order['created_at'])) ?></p>
        </div>
        <a href="/admin_new/pages/orders/index.php" class="glass px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
            <i class="bi bi-arrow-left mr-2"></i>Terug naar Bestellingen
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Items -->
    <div class="lg:col-span-2">
        <div class="card-glass p-8">
            <h2 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">
                <i class="bi bi-cart accent-primary mr-2"></i>Bestelde Artikelen
            </h2>
            
            <div class="space-y-4">
                <?php while ($item = $items->fetch_assoc()): ?>
                    <div class="flex items-center justify-between p-4 rounded-lg" style="background: var(--bg-glass); border: 1px solid var(--border-glass);">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg"><?= htmlspecialchars($item['product_name']) ?></h3>
                            <p class="text-sm" style="color: var(--text-muted);">SKU: <?= htmlspecialchars($item['sku']) ?></p>
                            <p class="text-sm" style="color: var(--text-muted);">Aantal: <?= $item['quantity'] ?> x €<?= number_format($item['unit_price'], 2, ',', '.') ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-xl">€<?= number_format($item['total_price'], 2, ',', '.') ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <!-- Totals -->
            <div class="mt-6 pt-6 border-t" style="border-color: var(--border-glass);">
                <div class="flex justify-between text-lg mb-2">
                    <span>Subtotaal:</span>
                    <span>€<?= number_format($order['total_price'] - $order['shipping_cost'], 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between text-lg mb-2">
                    <span>Verzendkosten:</span>
                    <span>€<?= number_format($order['shipping_cost'], 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between text-2xl font-bold" style="color: var(--accent);">
                    <span>Totaal:</span>
                    <span>€<?= number_format($order['total_price'], 2, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Details -->
    <div class="space-y-6">
        <!-- Customer Info -->
        <div class="card-glass p-6">
            <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
                <i class="bi bi-person accent-primary mr-2"></i>Klantgegevens
            </h3>
            <div class="space-y-2 text-sm">
                <p><strong>Naam:</strong> <?= htmlspecialchars($order['name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                <p><strong>Telefoon:</strong> <?= htmlspecialchars($order['phone'] ?? '-') ?></p>
            </div>
        </div>
        
        <!-- Shipping Info -->
        <div class="card-glass p-6">
            <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
                <i class="bi bi-truck accent-primary mr-2"></i>Verzending
            </h3>
            <div class="space-y-2 text-sm">
                <p><strong>Adres:</strong><br>
                <?= htmlspecialchars($order['street']) ?> <?= htmlspecialchars($order['number']) ?><br>
                <?= htmlspecialchars($order['zipcode']) ?> <?= htmlspecialchars($order['city']) ?><br>
                <?= htmlspecialchars($order['country']) ?></p>
                <p><strong>Methode:</strong> <?= htmlspecialchars($order['shipping_method']) ?></p>
                <?php if ($order['tracking_code']): ?>
                    <p><strong>Track & Trace:</strong><br>
                    <a href="<?= htmlspecialchars($order['tracking_url'] ?? '#') ?>" target="_blank" class="accent-primary font-mono">
                        <?= htmlspecialchars($order['tracking_code']) ?>
                    </a></p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Status -->
        <div class="card-glass p-6">
            <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
                <i class="bi bi-flag accent-primary mr-2"></i>Status
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Bestelstatus</label>
                    <select class="w-full px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);">
                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Betaalstatus</label>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-green-500/20 text-green-600 border-green-500">
                        <?= htmlspecialchars($order['payment_status'] ?? 'Betaald') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>