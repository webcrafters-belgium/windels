<?php
require_once '../../includes/header.php';

// Check database connection
$dbAvailable = isset($conn) && $conn !== null;

// Filters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = defined('ITEMS_PER_PAGE') ? ITEMS_PER_PAGE : 20;
$offset = ($page - 1) * $limit;

// Initialize variables
$total = 0;
$totalPages = 1;
$customers = null;
$stats = ['total_customers' => 0, 'active_customers' => 0, 'new_this_month' => 0];

if ($dbAvailable) {
    // Build query
    $where = [];
    $params = [];
    $types = '';

    if ($search) {
        $where[] = "(u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
        $types .= 'sss';
    }

    if ($status === 'active') {
        $where[] = "u.is_active = 1";
    } elseif ($status === 'inactive') {
        $where[] = "u.is_active = 0";
    }

    $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    // Count total
    $countQuery = "SELECT COUNT(*) as total FROM users u $whereClause";
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

    // Fetch customers with order count and total spent
    $query = "SELECT u.*, 
              COUNT(DISTINCT o.id) as order_count,
              COALESCE(SUM(o.total_price), 0) as total_spent
              FROM users u
              LEFT JOIN orders o ON u.email = o.email
              $whereClause
              GROUP BY u.id
              ORDER BY u.created_at DESC
              LIMIT ? OFFSET ?";

    $stmt = @$conn->prepare($query);
    if ($stmt) {
        $bindParams = $params;
        $bindParams[] = $limit;
        $bindParams[] = $offset;
        $bindTypes = $types . 'ii';
        $stmt->bind_param($bindTypes, ...$bindParams);
        $stmt->execute();
        $customers = $stmt->get_result();
        $stmt->close();
    }

    // Statistics
    $statsQuery = @$conn->query("
        SELECT 
            COUNT(*) as total_customers,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_customers,
            SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as new_this_month
        FROM users
    ");
    if ($statsQuery) {
        $stats = $statsQuery->fetch_assoc() ?: $stats;
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
            <p class="text-sm" style="color: var(--text-muted);">De database verbinding is niet actief. Klantgegevens kunnen niet worden geladen.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">
                <i class="bi bi-people accent-primary mr-3"></i>Klanten Beheer
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Beheer al je klantaccounts en bestelgegevens</p>
        </div>
        <a href="/admin/pages/customers/export.php" class="accent-bg text-white px-6 py-3 rounded-lg font-bold hover:opacity-90 transition">
            <i class="bi bi-download mr-2"></i>Exporteer CSV
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="card-glass p-6">
        <div class="flex items-center justify-between mb-2">
            <i class="bi bi-people text-4xl accent-primary"></i>
            <span class="text-3xl font-bold"><?= number_format($stats['total_customers']) ?></span>
        </div>
        <h3 class="text-lg font-semibold" style="color: var(--text-secondary);">Totaal Klanten</h3>
    </div>
    
    <div class="card-glass p-6">
        <div class="flex items-center justify-between mb-2">
            <i class="bi bi-person-check text-4xl text-green-500"></i>
            <span class="text-3xl font-bold"><?= number_format($stats['active_customers']) ?></span>
        </div>
        <h3 class="text-lg font-semibold" style="color: var(--text-secondary);">Actieve Klanten</h3>
    </div>
    
    <div class="card-glass p-6">
        <div class="flex items-center justify-between mb-2">
            <i class="bi bi-person-plus text-4xl text-blue-500"></i>
            <span class="text-3xl font-bold"><?= number_format($stats['new_this_month']) ?></span>
        </div>
        <h3 class="text-lg font-semibold" style="color: var(--text-secondary);">Nieuw Deze Maand</h3>
    </div>
</div>

<!-- Filters -->
<div class="card-glass p-6 mb-8">
    <form method="get" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Zoeken</label>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Naam, email of telefoon..."
                   class="w-full px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);">
        </div>
        
        <div>
            <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Status</label>
            <select name="status" class="w-full px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);">
                <option value="">Alle statussen</option>
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Actief</option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactief</option>
            </select>
        </div>
        
        <div class="flex items-end gap-4">
            <button type="submit" class="accent-bg text-white px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                <i class="bi bi-search mr-2"></i>Filteren
            </button>
            <a href="/admin/pages/customers/index.php" class="glass px-6 py-2 rounded-lg font-semibold hover:opacity-90 transition">
                <i class="bi bi-x-circle mr-2"></i>Reset
            </a>
        </div>
    </form>
</div>

<!-- Customers Table -->
<div class="card-glass p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Klanten (<?= number_format($total) ?>)</h2>
        <span class="text-sm" style="color: var(--text-muted);">Pagina <?= $page ?> van <?= $totalPages ?></span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass);">
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Klant</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Contact</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Orders</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Totaal Besteed</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Status</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Lid Sinds</th>
                    <th class="text-right py-3 px-4 font-semibold" style="color: var(--text-secondary);">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($customers && $customers->num_rows > 0): ?>
                    <?php while ($customer = $customers->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-opacity-50 transition" style="border-color: var(--border-glass);">
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full accent-bg flex items-center justify-center text-white font-bold mr-3">
                                        <?= strtoupper(substr($customer['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="font-semibold"><?= htmlspecialchars($customer['name']) ?></div>
                                        <div class="text-xs" style="color: var(--text-muted);">ID: <?= $customer['id'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm"><?= htmlspecialchars($customer['email']) ?></div>
                                <div class="text-xs" style="color: var(--text-muted);"><?= htmlspecialchars($customer['phone'] ?? '-') ?></div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-blue-500/20 text-blue-600 border-blue-500">
                                    <?= $customer['order_count'] ?> orders
                                </span>
                            </td>
                            <td class="py-4 px-4 font-semibold">€<?= number_format($customer['total_spent'], 2, ',', '.') ?></td>
                            <td class="py-4 px-4">
                                <?php if ($customer['is_active'] ?? true): ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-green-500/20 text-green-600 border-green-500">Actief</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-gray-500/20 text-gray-600 border-gray-500">Inactief</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 text-sm"><?= date('d-m-Y', strtotime($customer['created_at'])) ?></td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="/admin/pages/customers/view.php?id=<?= $customer['id'] ?>" 
                                       class="px-3 py-1 rounded-lg glass-hover accent-primary font-semibold text-sm" title="Bekijk">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="/admin/pages/orders/index.php?search=<?= urlencode($customer['email']) ?>" 
                                       class="px-3 py-1 rounded-lg glass-hover text-blue-600 font-semibold text-sm" title="Orders">
                                        <i class="bi bi-receipt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="py-12 text-center" style="color: var(--text-muted);">
                            <i class="bi bi-people text-6xl mb-4 block accent-primary"></i>
                            <p class="text-xl font-semibold mb-2">Geen klanten gevonden</p>
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
                    <a href="?page=1<?= $search ? '&search='.urlencode($search) : '' ?><?= $status ? '&status='.$status : '' ?>" 
                       class="px-4 py-2 rounded-lg glass-hover font-semibold">« Eerste</a>
                    <a href="?page=<?= $page - 1 ?><?= $search ? '&search='.urlencode($search) : '' ?><?= $status ? '&status='.$status : '' ?>" 
                       class="px-4 py-2 rounded-lg glass-hover font-semibold">‹ Vorige</a>
                <?php endif; ?>
                
                <span class="px-4 py-2 rounded-lg accent-bg text-white font-bold">Pagina <?= $page ?></span>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?><?= $search ? '&search='.urlencode($search) : '' ?><?= $status ? '&status='.$status : '' ?>" 
                       class="px-4 py-2 rounded-lg glass-hover font-semibold">Volgende ›</a>
                    <a href="?page=<?= $totalPages ?><?= $search ? '&search='.urlencode($search) : '' ?><?= $status ? '&status='.$status : '' ?>" 
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
