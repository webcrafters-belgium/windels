<?php
require_once '../../includes/header.php';

// Check database connection
$dbAvailable = isset($conn) && $conn !== null;

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$orderId) {
    header('Location: /admin/pages/orders/index.php');
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $dbAvailable) {
    header('Content-Type: application/json');
    
    $newStatus = $_POST['status'] ?? '';
    $trackingCode = trim($_POST['tracking_code'] ?? '');
    $trackingUrl = trim($_POST['tracking_url'] ?? '');
    
    if ($newStatus) {
        $stmt = @$conn->prepare("UPDATE orders SET status = ?, tracking_code = ?, tracking_url = ?, updated_at = NOW() WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param('sssi', $newStatus, $trackingCode, $trackingUrl, $orderId);
            $stmt->execute();
            $stmt->close();
            
            // Log activity
            $userId = getCurrentUser()['id'];
            logAdminActivity($conn, $userId, 'update', 'order', $orderId, "Order status gewijzigd naar '$newStatus'");
            
            echo json_encode(['success' => true, 'message' => 'Status bijgewerkt']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database fout']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ongeldige status']);
    }
    exit;
}

// Initialize variables
$order = null;
$items = null;

if ($dbAvailable) {
    // Fetch order details
    $stmt = @$conn->prepare("SELECT o.* FROM orders o WHERE o.id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }
}

if (!$order) {
    if (!$dbAvailable): ?>
    <!DOCTYPE html>
    <html><body>
    <div class="card-glass p-6 m-6 border-amber-500/50 bg-amber-500/10">
        <h3 class="font-bold text-amber-400">Database niet beschikbaar</h3>
        <p>De bestelgegevens kunnen niet worden geladen.</p>
        <a href="/admin/pages/orders/index.php" class="btn-glass mt-4">Terug naar Bestellingen</a>
    </div>
    </body></html>
    <?php exit;
    endif;
    header('Location: /admin/pages/orders/index.php');
    exit;
}

if ($dbAvailable && $order) {
    // Fetch order items
    $stmt = @$conn->prepare("
        SELECT oi.*, p.name as product_name, p.sku,
               (SELECT image_path FROM product_images WHERE product_id = oi.product_id AND is_main = 1 LIMIT 1) as image
        FROM order_items oi
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ");
    if ($stmt) {
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        $items = $stmt->get_result();
        $stmt->close();
    }
}

// Calculate subtotal
$subtotal = ($order['total_price'] ?? 0) - ($order['shipping_cost'] ?? 0);
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
        <div class="flex gap-3">
            <a href="/admin/pages/orders/pdf_invoice.php?id=<?= $order['id'] ?>" target="_blank" 
               class="glass px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                <i class="bi bi-file-pdf mr-2"></i>PDF Factuur
            </a>
            <a href="/admin/pages/orders/index.php" class="glass px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition">
                <i class="bi bi-arrow-left mr-2"></i>Terug
            </a>
        </div>
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
                        <div class="flex items-center gap-4">
                            <img src="<?= htmlspecialchars($item['image'] ?? '/images/products/placeholder.png') ?>" 
                                 class="w-16 h-16 object-cover rounded-lg" alt="">
                            <div>
                                <h3 class="font-semibold text-lg"><?= htmlspecialchars($item['product_name'] ?? 'Product verwijderd') ?></h3>
                                <p class="text-sm" style="color: var(--text-muted);">SKU: <?= htmlspecialchars($item['sku'] ?? '-') ?></p>
                                <p class="text-sm" style="color: var(--text-muted);">Aantal: <?= $item['quantity'] ?> x €<?= number_format($item['unit_price'] ?? ($item['total_price'] / $item['quantity']), 2, ',', '.') ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-xl accent-primary">€<?= number_format($item['total_price'], 2, ',', '.') ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <!-- Totals -->
            <div class="mt-6 pt-6 border-t" style="border-color: var(--border-glass);">
                <div class="flex justify-between text-lg mb-2">
                    <span>Subtotaal:</span>
                    <span>€<?= number_format($subtotal, 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between text-lg mb-2">
                    <span>Verzendkosten:</span>
                    <span>€<?= number_format($order['shipping_cost'] ?? 0, 2, ',', '.') ?></span>
                </div>
                <?php if (!empty($order['discount_amount']) && $order['discount_amount'] > 0): ?>
                    <div class="flex justify-between text-lg mb-2 text-green-500">
                        <span>Korting:</span>
                        <span>- €<?= number_format($order['discount_amount'], 2, ',', '.') ?></span>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between text-2xl font-bold mt-4 pt-4 border-t" style="border-color: var(--border-glass); color: var(--accent);">
                    <span>Totaal:</span>
                    <span>€<?= number_format($order['total_price'], 2, ',', '.') ?></span>
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        <?php if (!empty($order['notes'])): ?>
            <div class="card-glass p-6 mt-6">
                <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
                    <i class="bi bi-sticky accent-primary mr-2"></i>Klant Opmerkingen
                </h3>
                <p class="whitespace-pre-wrap"><?= htmlspecialchars($order['notes']) ?></p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Order Details Sidebar -->
    <div class="space-y-6">
        <!-- Status Update -->
        <div class="card-glass p-6">
            <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
                <i class="bi bi-flag accent-primary mr-2"></i>Status Beheer
            </h3>
            <form id="statusForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Bestelstatus</label>
                    <select name="status" id="statusSelect" 
                            class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>In afwachting</option>
                        <option value="paid" <?= $order['status'] === 'paid' ? 'selected' : '' ?>>Betaald</option>
                        <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>In behandeling</option>
                        <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Verzonden</option>
                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Afgerond</option>
                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Geannuleerd</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2">Track & Trace Code</label>
                    <input type="text" name="tracking_code" value="<?= htmlspecialchars($order['tracking_code'] ?? '') ?>"
                           class="w-full px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);"
                           placeholder="3STEST123456789">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2">Tracking URL</label>
                    <input type="url" name="tracking_url" value="<?= htmlspecialchars($order['tracking_url'] ?? '') ?>"
                           class="w-full px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);"
                           placeholder="https://postnl.nl/track/...">
                </div>
                
                <button type="submit" class="w-full accent-bg text-white px-4 py-3 rounded-lg font-bold hover:opacity-90 transition">
                    <i class="bi bi-check-circle mr-2"></i>Status Opslaan
                </button>
            </form>
        </div>
        
        <!-- Customer Info -->
        <div class="card-glass p-6">
            <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
                <i class="bi bi-person accent-primary mr-2"></i>Klantgegevens
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center">
                    <i class="bi bi-person text-lg mr-3" style="color: var(--text-muted);"></i>
                    <span class="font-semibold"><?= htmlspecialchars($order['name']) ?></span>
                </div>
                <div class="flex items-center">
                    <i class="bi bi-envelope text-lg mr-3" style="color: var(--text-muted);"></i>
                    <a href="mailto:<?= htmlspecialchars($order['email']) ?>" class="accent-primary">
                        <?= htmlspecialchars($order['email']) ?>
                    </a>
                </div>
                <div class="flex items-center">
                    <i class="bi bi-telephone text-lg mr-3" style="color: var(--text-muted);"></i>
                    <span><?= htmlspecialchars($order['phone'] ?? 'Niet opgegeven') ?></span>
                </div>
            </div>
        </div>
        
        <!-- Shipping Info -->
        <div class="card-glass p-6">
            <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
                <i class="bi bi-truck accent-primary mr-2"></i>Verzendadres
            </h3>
            <div class="text-sm space-y-1">
                <p class="font-semibold"><?= htmlspecialchars($order['name']) ?></p>
                <p><?= htmlspecialchars($order['street']) ?> <?= htmlspecialchars($order['number'] ?? '') ?></p>
                <p><?= htmlspecialchars($order['zipcode']) ?> <?= htmlspecialchars($order['city']) ?></p>
                <p><?= htmlspecialchars($order['country'] ?? 'Nederland') ?></p>
            </div>
            
            <?php if (!empty($order['tracking_code'])): ?>
                <div class="mt-4 pt-4 border-t" style="border-color: var(--border-glass);">
                    <p class="text-sm font-semibold mb-2">Track & Trace:</p>
                    <a href="<?= htmlspecialchars($order['tracking_url'] ?? '#') ?>" target="_blank" 
                       class="px-3 py-2 rounded-lg bg-green-500/20 text-green-600 font-mono text-sm inline-flex items-center">
                        <i class="bi bi-truck mr-2"></i><?= htmlspecialchars($order['tracking_code']) ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Payment Info -->
        <div class="card-glass p-6">
            <h3 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
                <i class="bi bi-credit-card accent-primary mr-2"></i>Betaling
            </h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span style="color: var(--text-muted);">Methode:</span>
                    <span class="font-semibold"><?= htmlspecialchars($order['payment_method'] ?? 'Mollie') ?></span>
                </div>
                <div class="flex justify-between">
                    <span style="color: var(--text-muted);">Status:</span>
                    <?php
                    $paymentStatus = $order['payment_status'] ?? $order['status'];
                    $isPaid = in_array($paymentStatus, ['paid', 'completed', 'shipped']);
                    ?>
                    <span class="px-2 py-1 rounded text-xs font-semibold <?= $isPaid ? 'bg-green-500/20 text-green-600' : 'bg-yellow-500/20 text-yellow-600' ?>">
                        <?= $isPaid ? 'Betaald' : 'In afwachting' ?>
                    </span>
                </div>
                <?php if (!empty($order['mollie_payment_id'])): ?>
                    <div class="flex justify-between">
                        <span style="color: var(--text-muted);">Mollie ID:</span>
                        <span class="font-mono text-xs"><?= htmlspecialchars($order['mollie_payment_id']) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('statusForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        if (result.success) {
            alert('✅ ' + result.message);
            location.reload();
        } else {
            alert('❌ ' + result.message);
        }
    } catch (error) {
        alert('❌ Er is een fout opgetreden');
    }
});
</script>

<?php require_once '../../includes/footer.php'; ?>
