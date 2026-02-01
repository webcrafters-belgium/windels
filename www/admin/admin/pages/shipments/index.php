<?php
require_once '../../includes/header.php';

// Check database connection
$dbAvailable = isset($conn) && $conn !== null;
$shipments = null;

if ($dbAvailable) {
    // Fetch shipments
    $query = "SELECT s.*, o.name as customer_name, o.email
              FROM shipments s
              LEFT JOIN orders o ON s.order_number = o.id
              ORDER BY s.created_at DESC
              LIMIT 50";
    $shipments = @$conn->query($query);
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
            <p class="text-sm" style="color: var(--text-muted);">De database verbinding is niet actief. Verzendingsgegevens kunnen niet worden geladen.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">
        <i class="bi bi-truck accent-primary mr-3"></i>Verzendingen
    </h1>
    <p class="text-lg" style="color: var(--text-muted);">Beheer alle verzendingen en tracking</p>
</div>

<!-- Shipments Table -->
<div class="card-glass p-8">
    <h2 class="text-2xl font-bold mb-6" style="color: var(--text-primary);">Recente Verzendingen</h2>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass);">
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Order #</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Klant</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Vervoerder</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Tracking</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Status</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Datum</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($shipments && $shipments->num_rows > 0): ?>
                    <?php while ($ship = $shipments->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-opacity-50 transition" style="border-color: var(--border-glass);">
                            <td class="py-4 px-4 font-mono">#<?= htmlspecialchars($ship['order_number']) ?></td>
                            <td class="py-4 px-4">
                                <div class="font-semibold"><?= htmlspecialchars($ship['customer_name'] ?? $ship['recipient_name']) ?></div>
                                <div class="text-sm" style="color: var(--text-muted);"><?= htmlspecialchars($ship['email']) ?></div>
                            </td>
                            <td class="py-4 px-4"><?= htmlspecialchars($ship['carrier'] ?? '-') ?></td>
                            <td class="py-4 px-4">
                                <?php if ($ship['tracking_code']): ?>
                                    <span class="font-mono text-sm"><?= htmlspecialchars($ship['tracking_code']) ?></span>
                                <?php else: ?>
                                    <span style="color: var(--text-muted);">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-blue-500/20 text-blue-600 border-blue-500">
                                    <?= ucfirst($ship['status']) ?>
                                </span>
                            </td>
                            <td class="py-4 px-4 text-sm"><?= date('d-m-Y', strtotime($ship['created_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center" style="color: var(--text-muted);">
                            <i class="bi bi-inbox text-6xl mb-4 block accent-primary"></i>
                            <p class="text-xl font-semibold">Geen verzendingen gevonden</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>