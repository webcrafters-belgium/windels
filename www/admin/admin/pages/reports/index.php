<?php
require_once '../../includes/header.php';

// Check database connection
$dbAvailable = isset($conn) && $conn !== null;

// Date range filter
$startDate = isset($_GET['start']) ? $_GET['start'] : date('Y-m-01');
$endDate = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d');

// Initialize default values
$revenue = ['total_revenue' => 0, 'total_orders' => 0, 'avg_order_value' => 0];
$dailyData = [];
$topProducts = null;
$statusData = [];
$newCustomers = 0;
$prevRevenue = ['total_revenue' => 0, 'total_orders' => 0];
$revenueChange = 0;
$ordersChange = 0;

if ($dbAvailable) {
    // Revenue statistics
    $revenueQuery = @$conn->prepare("
        SELECT 
            COALESCE(SUM(total_price), 0) as total_revenue,
            COUNT(*) as total_orders,
            AVG(total_price) as avg_order_value
        FROM orders 
        WHERE created_at BETWEEN ? AND DATE_ADD(?, INTERVAL 1 DAY)
        AND status != 'cancelled'
    ");
    if ($revenueQuery) {
        $revenueQuery->bind_param('ss', $startDate, $endDate);
        $revenueQuery->execute();
        $revenue = $revenueQuery->get_result()->fetch_assoc() ?: $revenue;
        $revenueQuery->close();
    }

    // Daily revenue for chart
    $dailyRevenue = @$conn->prepare("
        SELECT 
            DATE(created_at) as date,
            COALESCE(SUM(total_price), 0) as revenue,
            COUNT(*) as orders
        FROM orders 
        WHERE created_at BETWEEN ? AND DATE_ADD(?, INTERVAL 1 DAY)
        AND status != 'cancelled'
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    if ($dailyRevenue) {
        $dailyRevenue->bind_param('ss', $startDate, $endDate);
        $dailyRevenue->execute();
        $dailyData = $dailyRevenue->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
        $dailyRevenue->close();
    }

    // Top selling products
    $topProducts = @$conn->query("
        SELECT p.id, p.name, p.sku, 
               SUM(oi.quantity) as total_sold,
               SUM(oi.total_price) as total_revenue
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        JOIN orders o ON oi.order_id = o.id
        WHERE o.created_at BETWEEN '$startDate' AND DATE_ADD('$endDate', INTERVAL 1 DAY)
        AND o.status != 'cancelled'
        GROUP BY p.id
        ORDER BY total_sold DESC
        LIMIT 10
    ");

    // Order status distribution
    $statusDist = @$conn->query("
        SELECT status, COUNT(*) as count 
        FROM orders 
        WHERE created_at BETWEEN '$startDate' AND DATE_ADD('$endDate', INTERVAL 1 DAY)
        GROUP BY status
    ");
    if ($statusDist) {
        while ($row = $statusDist->fetch_assoc()) {
            $statusData[$row['status']] = $row['count'];
        }
    }

    // Customer statistics
    $newCustQuery = @$conn->query("
        SELECT COUNT(*) as count FROM users 
        WHERE created_at BETWEEN '$startDate' AND DATE_ADD('$endDate', INTERVAL 1 DAY)
    ");
    if ($newCustQuery) {
        $newCustomers = $newCustQuery->fetch_assoc()['count'] ?? 0;
    }

    // Comparison with previous period
    $daysDiff = (strtotime($endDate) - strtotime($startDate)) / 86400;
    $prevStartDate = date('Y-m-d', strtotime($startDate) - $daysDiff * 86400);
    $prevEndDate = date('Y-m-d', strtotime($endDate) - $daysDiff * 86400);

    $prevRevenueQuery = @$conn->prepare("
        SELECT COALESCE(SUM(total_price), 0) as total_revenue, COUNT(*) as total_orders
        FROM orders 
        WHERE created_at BETWEEN ? AND DATE_ADD(?, INTERVAL 1 DAY)
        AND status != 'cancelled'
    ");
    if ($prevRevenueQuery) {
        $prevRevenueQuery->bind_param('ss', $prevStartDate, $prevEndDate);
        $prevRevenueQuery->execute();
        $prevRevenue = $prevRevenueQuery->get_result()->fetch_assoc() ?: $prevRevenue;
        $prevRevenueQuery->close();
    }

    $revenueChange = $prevRevenue['total_revenue'] > 0 
        ? (($revenue['total_revenue'] - $prevRevenue['total_revenue']) / $prevRevenue['total_revenue']) * 100 
        : 0;
    $ordersChange = $prevRevenue['total_orders'] > 0 
        ? (($revenue['total_orders'] - $prevRevenue['total_orders']) / $prevRevenue['total_orders']) * 100 
        : 0;
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
            <p class="text-sm" style="color: var(--text-muted);">De database verbinding is niet actief. Rapportages kunnen niet worden gegenereerd.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">
                <i class="bi bi-graph-up accent-primary mr-3"></i>Rapporten & Analytics
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Inzicht in je webshop prestaties</p>
        </div>
        
        <!-- Date Range Filter -->
        <form method="get" class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <input type="date" name="start" value="<?= $startDate ?>" 
                       class="px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);">
                <span style="color: var(--text-muted);">tot</span>
                <input type="date" name="end" value="<?= $endDate ?>" 
                       class="px-4 py-2 rounded-lg glass border" style="border-color: var(--border-glass);">
            </div>
            <button type="submit" class="accent-bg text-white px-4 py-2 rounded-lg font-semibold">
                <i class="bi bi-funnel mr-1"></i>Filter
            </button>
        </form>
    </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Revenue -->
    <div class="card-glass p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-sm font-semibold" style="color: var(--text-muted);">Totale Omzet</p>
                <p class="text-3xl font-bold accent-primary">€<?= number_format($revenue['total_revenue'], 2, ',', '.') ?></p>
            </div>
            <div class="p-3 rounded-lg accent-bg/20">
                <i class="bi bi-currency-euro text-2xl accent-primary"></i>
            </div>
        </div>
        <?php if ($revenueChange != 0): ?>
            <div class="flex items-center text-sm <?= $revenueChange > 0 ? 'text-green-500' : 'text-red-500' ?>">
                <i class="bi bi-arrow-<?= $revenueChange > 0 ? 'up' : 'down' ?> mr-1"></i>
                <?= number_format(abs($revenueChange), 1) ?>% vs vorige periode
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Total Orders -->
    <div class="card-glass p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-sm font-semibold" style="color: var(--text-muted);">Bestellingen</p>
                <p class="text-3xl font-bold text-blue-500"><?= number_format($revenue['total_orders']) ?></p>
            </div>
            <div class="p-3 rounded-lg bg-blue-500/20">
                <i class="bi bi-receipt text-2xl text-blue-500"></i>
            </div>
        </div>
        <?php if ($ordersChange != 0): ?>
            <div class="flex items-center text-sm <?= $ordersChange > 0 ? 'text-green-500' : 'text-red-500' ?>">
                <i class="bi bi-arrow-<?= $ordersChange > 0 ? 'up' : 'down' ?> mr-1"></i>
                <?= number_format(abs($ordersChange), 1) ?>% vs vorige periode
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Average Order Value -->
    <div class="card-glass p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-sm font-semibold" style="color: var(--text-muted);">Gem. Orderwaarde</p>
                <p class="text-3xl font-bold text-purple-500">€<?= number_format($revenue['avg_order_value'] ?? 0, 2, ',', '.') ?></p>
            </div>
            <div class="p-3 rounded-lg bg-purple-500/20">
                <i class="bi bi-cart text-2xl text-purple-500"></i>
            </div>
        </div>
    </div>
    
    <!-- New Customers -->
    <div class="card-glass p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-sm font-semibold" style="color: var(--text-muted);">Nieuwe Klanten</p>
                <p class="text-3xl font-bold text-yellow-500"><?= number_format($newCustomers) ?></p>
            </div>
            <div class="p-3 rounded-lg bg-yellow-500/20">
                <i class="bi bi-person-plus text-2xl text-yellow-500"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Revenue Chart -->
    <div class="card-glass p-6">
        <h2 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
            <i class="bi bi-bar-chart accent-primary mr-2"></i>Omzet per Dag
        </h2>
        <canvas id="revenueChart" height="250"></canvas>
    </div>
    
    <!-- Order Status -->
    <div class="card-glass p-6">
        <h2 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
            <i class="bi bi-pie-chart accent-primary mr-2"></i>Order Status Verdeling
        </h2>
        <canvas id="statusChart" height="250"></canvas>
    </div>
</div>

<!-- Top Products -->
<div class="card-glass p-8">
    <h2 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">
        <i class="bi bi-trophy accent-primary mr-2"></i>Top 10 Producten
    </h2>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass);">
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">#</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Product</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">SKU</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Verkocht</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Omzet</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rank = 1;
                while ($product = $topProducts->fetch_assoc()): 
                ?>
                    <tr class="border-b hover:bg-opacity-50 transition" style="border-color: var(--border-glass);">
                        <td class="py-4 px-4">
                            <?php if ($rank <= 3): ?>
                                <span class="text-2xl"><?= ['🥇', '🥈', '🥉'][$rank - 1] ?></span>
                            <?php else: ?>
                                <span class="font-semibold"><?= $rank ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4 font-semibold"><?= htmlspecialchars($product['name']) ?></td>
                        <td class="py-4 px-4 font-mono text-sm"><?= htmlspecialchars($product['sku']) ?></td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-blue-500/20 text-blue-600 border-blue-500">
                                <?= number_format($product['total_sold']) ?> stuks
                            </span>
                        </td>
                        <td class="py-4 px-4 font-bold accent-primary">€<?= number_format($product['total_revenue'], 2, ',', '.') ?></td>
                    </tr>
                <?php 
                $rank++;
                endwhile; 
                ?>
                <?php if ($rank === 1): ?>
                    <tr>
                        <td colspan="5" class="py-8 text-center" style="color: var(--text-muted);">
                            Geen verkopen in deze periode
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Prepare chart data
const dailyData = <?= json_encode($dailyData) ?>;
const statusData = <?= json_encode($statusData) ?>;

const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
const textColor = isDark ? '#9ca3af' : '#4a5568';
const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)';

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: dailyData.map(d => new Date(d.date).toLocaleDateString('nl-NL', {day: 'numeric', month: 'short'})),
        datasets: [{
            label: 'Omzet (€)',
            data: dailyData.map(d => d.revenue),
            backgroundColor: 'rgba(16, 185, 129, 0.6)',
            borderColor: 'rgb(16, 185, 129)',
            borderWidth: 1,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { color: textColor, callback: v => '€' + v },
                grid: { color: gridColor }
            },
            x: {
                ticks: { color: textColor },
                grid: { display: false }
            }
        }
    }
});

// Status Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusColors = {
    pending: '#f59e0b',
    processing: '#3b82f6',
    completed: '#10b981',
    cancelled: '#ef4444',
    paid: '#10b981',
    shipped: '#8b5cf6'
};
const statusLabels = {
    pending: 'In afwachting',
    processing: 'In behandeling',
    completed: 'Afgerond',
    cancelled: 'Geannuleerd',
    paid: 'Betaald',
    shipped: 'Verzonden'
};

new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(statusData).map(s => statusLabels[s] || s),
        datasets: [{
            data: Object.values(statusData),
            backgroundColor: Object.keys(statusData).map(s => statusColors[s] || '#6b7280'),
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
                labels: { color: textColor, padding: 15 }
            }
        }
    }
});
</script>

<?php require_once '../../includes/footer.php'; ?>
