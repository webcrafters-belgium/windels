<?php
require_once '../../includes/header.php';

// Check database connection
$dbAvailable = isset($conn) && $conn !== null;

$customerId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$customerId) {
    header('Location: /admin/pages/customers/index.php');
    exit;
}

// Initialize variables
$customer = null;
$orders = null;
$stats = ['total_orders' => 0, 'total_spent' => 0, 'avg_order_value' => 0];

if ($dbAvailable) {
    // Fetch customer
    $stmt = @$conn->prepare("SELECT * FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $customerId);
        $stmt->execute();
        $customer = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }
}

if (!$customer) {
    // Show error message instead of redirecting when DB not available
    if (!$dbAvailable): ?>
    <!DOCTYPE html>
    <html><body>
    <div class="card-glass p-6 m-6 border-amber-500/50 bg-amber-500/10">
        <h3 class="font-bold text-amber-400">Database niet beschikbaar</h3>
        <p>De klantgegevens kunnen niet worden geladen.</p>
        <a href="/admin/pages/customers/index.php" class="btn-glass mt-4">Terug naar Klanten</a>
    </div>
    </body></html>
    <?php exit;
    endif;
    header('Location: /admin/pages/customers/index.php');
    exit;
}

if ($dbAvailable && $customer) {
    // Fetch customer orders
    $stmt = @$conn->prepare("
        SELECT o.*, COUNT(oi.id) as item_count 
        FROM orders o 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        WHERE o.email = ? 
        GROUP BY o.id 
        ORDER BY o.created_at DESC 
        LIMIT 20
    ");
    if ($stmt) {
        $stmt->bind_param('s', $customer['email']);
        $stmt->execute();
        $orders = $stmt->get_result();
        $stmt->close();
    }

    // Statistics
    $stmt = @$conn->prepare("
        SELECT 
            COUNT(*) as total_orders,
            COALESCE(SUM(total_price), 0) as total_spent,
            AVG(total_price) as avg_order_value
        FROM orders 
        WHERE email = ?
    ");
    if ($stmt) {
        $stmt->bind_param('s', $customer['email']);
        $stmt->execute();
        $stats = $stmt->get_result()->fetch_assoc() ?: $stats;
        $stmt->close();
    }
}
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">
                <i class="bi bi-person accent-primary mr-3"></i>Klant Details
            </h1>
            <p class="text-lg" style="color: var(--text-muted);"><?= htmlspecialchars($customer['name'] ?? 'Onbekend') ?></p>
        </div>
        <a href="/admin/pages/customers/index.php" class="glass px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
            <i class="bi bi-arrow-left mr-2"></i>Terug naar Klanten
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Customer Info -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Profile Card -->
        <div class="card-glass p-8 text-center">
            <div class="w-24 h-24 rounded-full accent-bg flex items-center justify-center text-white text-4xl font-bold mx-auto mb-4">
                <?= strtoupper(substr($customer['name'], 0, 1)) ?>
            </div>
            <h2 class="text-2xl font-bold mb-2"><?= htmlspecialchars($customer['name']) ?></h2>
            <p style="color: var(--text-muted);">Klant #<?= $customer['id'] ?></p>
            
            <?php if ($customer['is_active'] ?? true): ?>
                <span class="inline-block mt-4 px-4 py-2 rounded-full text-sm font-semibold border bg-green-500/20 text-green-600 border-green-500">
                    <i class="bi bi-check-circle mr-1"></i>Actief Account
                </span>
            <?php else: ?>
                <span class="inline-block mt-4 px-4 py-2 rounded-full text-sm font-semibold border bg-gray-500/20 text-gray-600 border-gray-500">
                    <i class="bi bi-x-circle mr-1"></i>Inactief
                </span>
            <?php endif; ?>
        </div>
        
        <!-- Contact Info -->
        <div class="card-glass p-6">
            <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
                <i class="bi bi-envelope accent-primary mr-2"></i>Contactgegevens
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center">
                    <i class="bi bi-envelope text-lg mr-3" style="color: var(--text-muted);"></i>
                    <a href="mailto:<?= htmlspecialchars($customer['email']) ?>" class="accent-primary">
                        <?= htmlspecialchars($customer['email']) ?>
                    </a>
                </div>
                <div class="flex items-center">
                    <i class="bi bi-telephone text-lg mr-3" style="color: var(--text-muted);"></i>
                    <span><?= htmlspecialchars($customer['phone'] ?? 'Niet opgegeven') ?></span>
                </div>
                <div class="flex items-start">
                    <i class="bi bi-geo-alt text-lg mr-3" style="color: var(--text-muted);"></i>
                    <span>
                        <?php if (!empty($customer['street'])): ?>
                            <?= htmlspecialchars($customer['street']) ?> <?= htmlspecialchars($customer['number'] ?? '') ?><br>
                            <?= htmlspecialchars($customer['zipcode'] ?? '') ?> <?= htmlspecialchars($customer['city'] ?? '') ?><br>
                            <?= htmlspecialchars($customer['country'] ?? '') ?>
                        <?php else: ?>
                            Geen adres opgegeven
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="card-glass p-6">
            <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
                <i class="bi bi-graph-up accent-primary mr-2"></i>Statistieken
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span style="color: var(--text-muted);">Totaal Orders</span>
                    <span class="text-xl font-bold"><?= number_format($stats['total_orders']) ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--text-muted);">Totaal Besteed</span>
                    <span class="text-xl font-bold accent-primary">€<?= number_format($stats['total_spent'], 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--text-muted);">Gem. Orderwaarde</span>
                    <span class="text-xl font-bold">€<?= number_format($stats['avg_order_value'] ?? 0, 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span style="color: var(--text-muted);">Lid Sinds</span>
                    <span class="font-semibold"><?= date('d-m-Y', strtotime($customer['created_at'])) ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orders -->
    <div class="lg:col-span-2">
        <div class="card-glass p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
                    <i class="bi bi-receipt accent-primary mr-2"></i>Bestelgeschiedenis
                </h2>
                <a href="/admin/pages/orders/index.php?search=<?= urlencode($customer['email']) ?>" 
                   class="accent-primary font-semibold">Bekijk Alle →</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b" style="border-color: var(--border-glass);">
                            <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">#</th>
                            <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Datum</th>
                            <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Items</th>
                            <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Totaal</th>
                            <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Status</th>
                            <th class="text-right py-3 px-4 font-semibold" style="color: var(--text-secondary);">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($orders && $orders->num_rows > 0): ?>
                            <?php while ($order = $orders->fetch_assoc()): ?>
                                <tr class="border-b hover:bg-opacity-50 transition" style="border-color: var(--border-glass);">
                                    <td class="py-4 px-4 font-mono font-bold">#<?= $order['id'] ?></td>
                                    <td class="py-4 px-4 text-sm"><?= date('d-m-Y', strtotime($order['created_at'])) ?></td>
                                    <td class="py-4 px-4"><?= $order['item_count'] ?> items</td>
                                    <td class="py-4 px-4 font-semibold">€<?= number_format($order['total_price'], 2, ',', '.') ?></td>
                                    <td class="py-4 px-4">
                                        <?php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-500/20 text-yellow-600 border-yellow-500',
                                            'processing' => 'bg-blue-500/20 text-blue-600 border-blue-500',
                                            'completed' => 'bg-green-500/20 text-green-600 border-green-500',
                                            'cancelled' => 'bg-red-500/20 text-red-600 border-red-500',
                                            'paid' => 'bg-green-500/20 text-green-600 border-green-500',
                                            'shipped' => 'bg-purple-500/20 text-purple-600 border-purple-500',
                                        ];
                                        $colorClass = $statusColors[$order['status']] ?? 'bg-gray-500/20 text-gray-600 border-gray-500';
                                        ?>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold border <?= $colorClass ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <a href="/admin/pages/orders/detail.php?id=<?= $order['id'] ?>" 
                                           class="px-3 py-1 rounded-lg glass-hover accent-primary font-semibold text-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="py-12 text-center" style="color: var(--text-muted);">
                                    <i class="bi bi-receipt text-5xl mb-4 block"></i>
                                    <p class="text-lg">Nog geen bestellingen</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
