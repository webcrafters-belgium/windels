<?php
require_once '../../includes/header.php';

// Check database connection
$dbAvailable = isset($conn) && $conn !== null;

// Filters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = defined('ITEMS_PER_PAGE') ? ITEMS_PER_PAGE : 20;
$offset = ($page - 1) * $limit;

// Initialize variables
$total = 0;
$totalPages = 1;
$orders = null;

if ($dbAvailable) {
    // Build query
    $where = [];
    $params = [];
    $types = '';

    if ($status_filter) {
        $where[] = "o.status = ?";
        $params[] = $status_filter;
        $types .= 's';
    }

    if ($search) {
        $where[] = "(o.name LIKE ? OR o.email LIKE ? OR o.id = ?)";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $search;
        $types .= 'ssi';
    }

    $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    // Count total
    $countQuery = "SELECT COUNT(*) as total FROM orders o $whereClause";
    $stmt = @$conn->prepare($countQuery);
    if ($stmt) {
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $total = $result->fetch_assoc()['total'] ?? 0;
        }
        $totalPages = max(1, ceil($total / $limit));
        $stmt->close();
    }

    // Fetch orders
    $query = "SELECT o.*, COUNT(oi.id) as item_count,
              (SELECT name FROM products WHERE id = oi.product_id LIMIT 1) as first_product
              FROM orders o
              LEFT JOIN order_items oi ON o.id = oi.order_id
              $whereClause
              GROUP BY o.id
              ORDER BY o.created_at DESC
              LIMIT ? OFFSET ?";

    $stmt = @$conn->prepare($query);
    if ($stmt) {
        $bindParams = $params;
        $bindParams[] = $limit;
        $bindParams[] = $offset;
        $bindTypes = $types . 'ii';
        $stmt->bind_param($bindTypes, ...$bindParams);
        $stmt->execute();
        $orders = $stmt->get_result();
        $stmt->close();
    }
}
?>

<?php if (!$dbAvailable): ?>
<!-- Database Connection Warning -->
<div class="card-glass p-6 mb-6 border-amber-500/50 bg-amber-500/10" data-testid="db-warning">
    <div class="flex items-center space-x-4">
        <div class="w-12 h-12 rounded-xl bg-amber-500/20 flex items-center justify-center">
            <i class="bi bi-database-exclamation text-2xl text-amber-400"></i>
        </div>
        <div>
            <h3 class="font-bold text-amber-400">Database niet beschikbaar</h3>
            <p class="text-sm" style="color: var(--text-muted);">De database verbinding is niet actief. Bestellingen kunnen niet worden geladen.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">
                <i class="bi bi-receipt accent-primary mr-3"></i>Bestellingen Beheer
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Beheer alle bestellingen en hun status</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card-glass p-6 mb-8">
    <form method="get" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Zoeken</label>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Naam, email of order #..."
                   class="w-full px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);">
        </div>
        
        <!-- Status -->
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Status</label>
            <select name="status" class="w-full px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);">
                <option value="">Alle statussen</option>
                <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="processing" <?= $status_filter === 'processing' ? 'selected' : '' ?>>Processing</option>
                <option value="completed" <?= $status_filter === 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        
        <!-- Submit -->
        <div class="flex items-end gap-4">
            <button type="submit" class="accent-bg text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                <i class="bi bi-search mr-2"></i>Filteren
            </button>
            <a href="/admin/pages/orders/index.php" class="glass px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                <i class="bi bi-x-circle mr-2"></i>Reset
            </a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="card-glass p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Bestellingen (<?= number_format($total) ?>)</h2>
        <span class="text-sm" style="color: var(--text-muted);">Pagina <?= $page ?> van <?= $totalPages ?></span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass);">
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">#</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Klant</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Artikelen</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Totaal</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Verzending</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Status</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Datum</th>
                    <th class="text-right py-3 px-4 font-semibold" style="color: var(--text-secondary);">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders && $orders->num_rows > 0): ?>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-opacity-50 transition" style="border-color: var(--border-glass);">
                            <td class="py-4 px-4 font-mono font-bold">#<?= $order['id'] ?></td>
                            <td class="py-4 px-4">
                                <div class="font-semibold"><?= htmlspecialchars($order['name']) ?></div>
                                <div class="text-sm" style="color: var(--text-muted);"><?= htmlspecialchars($order['email']) ?></div>
                            </td>
                            <td class="py-4 px-4"><?= $order['item_count'] ?> artikel(en)</td>
                            <td class="py-4 px-4 font-semibold">€<?= number_format($order['total_price'], 2, ',', '.') ?></td>
                            <td class="py-4 px-4">
                                <?php if ($order['tracking_code']): ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-green-500/20 text-green-600 border border-green-500">
                                        <i class="bi bi-truck"></i> <?= htmlspecialchars($order['tracking_code']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-sm" style="color: var(--text-muted);">-</span>
                                <?php endif; ?>
                            </td>
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
                                <a href="/admin/pages/orders/view.php?id=<?= $order['id'] ?>" 
                                   class="inline-flex items-center px-3 py-1 rounded-lg glass-hover accent-primary font-semibold text-sm">
                                    <i class="bi bi-eye mr-1"></i> Bekijk
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center" style="color: var(--text-muted);">
                            <i class="bi bi-inbox text-6xl mb-4 block accent-primary"></i>
                            <p class="text-xl font-semibold mb-2">Geen bestellingen gevonden</p>
                            <p>Probeer je filters aan te passen</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="mt-8 flex items-center justify-between">
            <div class="flex gap-2">
                <?php if ($page > 1): ?>
                    <a href="?page=1<?= $status_filter ? '&status='.$status_filter : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>" 
                       class="px-4 py-2 rounded-lg glass-hover font-semibold">« Eerste</a>
                    <a href="?page=<?= $page - 1 ?><?= $status_filter ? '&status='.$status_filter : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>" 
                       class="px-4 py-2 rounded-lg glass-hover font-semibold">‹ Vorige</a>
                <?php endif; ?>
                
                <span class="px-4 py-2 rounded-lg accent-bg text-white font-bold">Pagina <?= $page ?></span>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?><?= $status_filter ? '&status='.$status_filter : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>" 
                       class="px-4 py-2 rounded-lg glass-hover font-semibold">Volgende ›</a>
                    <a href="?page=<?= $totalPages ?><?= $status_filter ? '&status='.$status_filter : '' ?><?= $search ? '&search='.urlencode($search) : '' ?>" 
                       class="px-4 py-2 rounded-lg glass-hover font-semibold">Laatste »</a>
                <?php endif; ?>
            </div>
            
            <span style="color: var(--text-muted);">
                Toon <?= $offset + 1 ?> - <?= min($offset + $limit, $total) ?> van <?= $total ?>
            </span>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../../includes/footer.php'; ?>