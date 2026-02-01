<?php
require_once '../../includes/header.php';

// Check database connection
$dbAvailable = isset($conn) && $conn !== null;
$coupons = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $dbAvailable) {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create' || $action === 'update') {
        $code = strtoupper(trim($_POST['code'] ?? ''));
        $type = $_POST['type'] ?? 'percentage';
        $value = floatval($_POST['value'] ?? 0);
        $minOrder = floatval($_POST['min_order'] ?? 0);
        $maxUses = intval($_POST['max_uses'] ?? 0);
        $expiresAt = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        if ($action === 'create') {
            $stmt = @$conn->prepare("INSERT INTO coupons (code, type, value, min_order_amount, max_uses, expires_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt) $stmt->bind_param('ssddisi', $code, $type, $value, $minOrder, $maxUses, $expiresAt, $isActive);
        } else {
            $couponId = intval($_POST['coupon_id']);
            $stmt = @$conn->prepare("UPDATE coupons SET code = ?, type = ?, value = ?, min_order_amount = ?, max_uses = ?, expires_at = ?, is_active = ? WHERE id = ?");
            if ($stmt) $stmt->bind_param('ssddsisi', $code, $type, $value, $minOrder, $maxUses, $expiresAt, $isActive, $couponId);
        }
        
        if ($stmt && $stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Kortingscode opgeslagen']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Fout bij opslaan: ' . ($conn->error ?? 'Database niet beschikbaar')]);
        }
        exit;
    }
    
    if ($action === 'delete') {
        $couponId = intval($_POST['coupon_id']);
        $stmt = @$conn->prepare("DELETE FROM coupons WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param('i', $couponId);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => $conn->error]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Database niet beschikbaar']);
        }
        exit;
    }
    
    if ($action === 'toggle') {
        $couponId = intval($_POST['coupon_id']);
        $stmt = @$conn->prepare("UPDATE coupons SET is_active = NOT is_active WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param('i', $couponId);
            $stmt->execute();
        }
        echo json_encode(['success' => true]);
        exit;
    }
}

if ($dbAvailable) {
    // Check if coupons table exists, if not create it
    $tableCheck = @$conn->query("SHOW TABLES LIKE 'coupons'");
    if ($tableCheck && $tableCheck->num_rows === 0) {
        @$conn->query("
            CREATE TABLE coupons (
                id INT AUTO_INCREMENT PRIMARY KEY,
                code VARCHAR(50) UNIQUE NOT NULL,
                type ENUM('percentage', 'fixed') DEFAULT 'percentage',
                value DECIMAL(10,2) NOT NULL DEFAULT 0,
                min_order_amount DECIMAL(10,2) DEFAULT 0,
                max_uses INT DEFAULT 0,
                times_used INT DEFAULT 0,
                expires_at DATE NULL,
                is_active TINYINT(1) DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    // Fetch all coupons
    $coupons = @$conn->query("SELECT * FROM coupons ORDER BY created_at DESC");
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
            <p class="text-sm" style="color: var(--text-muted);">De database verbinding is niet actief. Kortingscodes kunnen niet worden beheerd.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">
                <i class="bi bi-tag accent-primary mr-3"></i>Kortingscodes Beheer
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Beheer al je kortingscodes en promoties</p>
        </div>
        <button onclick="openModal()" class="accent-bg text-white px-6 py-3 rounded-lg font-bold hover:opacity-90 transition">
            <i class="bi bi-plus-circle mr-2"></i>Nieuwe Kortingscode
        </button>
    </div>
</div>

<!-- Coupons Table -->
<div class="card-glass p-8">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b" style="border-color: var(--border-glass);">
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Code</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Type</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Waarde</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Min. Bestelling</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Gebruik</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Verloopt</th>
                    <th class="text-left py-3 px-4 font-semibold" style="color: var(--text-secondary);">Status</th>
                    <th class="text-right py-3 px-4 font-semibold" style="color: var(--text-secondary);">Acties</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($coupons && $coupons->num_rows > 0): ?>
                    <?php while ($coupon = $coupons->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-opacity-50 transition" style="border-color: var(--border-glass);">
                            <td class="py-4 px-4">
                                <span class="font-mono font-bold text-lg accent-primary"><?= htmlspecialchars($coupon['code']) ?></span>
                            </td>
                            <td class="py-4 px-4">
                                <?php if ($coupon['type'] === 'percentage'): ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-500/20 text-blue-600">Percentage</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-green-500/20 text-green-600">Vast Bedrag</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 font-semibold">
                                <?= $coupon['type'] === 'percentage' ? $coupon['value'] . '%' : '€' . number_format($coupon['value'], 2, ',', '.') ?>
                            </td>
                            <td class="py-4 px-4">€<?= number_format($coupon['min_order_amount'], 2, ',', '.') ?></td>
                            <td class="py-4 px-4">
                                <?= $coupon['times_used'] ?> / <?= $coupon['max_uses'] ?: '∞' ?>
                            </td>
                            <td class="py-4 px-4 text-sm">
                                <?php if ($coupon['expires_at']): ?>
                                    <?php 
                                    $isExpired = strtotime($coupon['expires_at']) < time();
                                    ?>
                                    <span class="<?= $isExpired ? 'text-red-500' : '' ?>">
                                        <?= date('d-m-Y', strtotime($coupon['expires_at'])) ?>
                                        <?= $isExpired ? '(Verlopen)' : '' ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--text-muted);">Geen limiet</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4">
                                <?php if ($coupon['is_active']): ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-green-500/20 text-green-600 border-green-500">Actief</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-gray-500/20 text-gray-600 border-gray-500">Inactief</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick='editCoupon(<?= json_encode($coupon) ?>)' 
                                            class="px-3 py-1 rounded-lg glass-hover text-blue-600 font-semibold text-sm" title="Bewerken">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button onclick="toggleCoupon(<?= $coupon['id'] ?>)" 
                                            class="px-3 py-1 rounded-lg glass-hover text-yellow-600 font-semibold text-sm" title="Toggle Status">
                                        <i class="bi bi-toggle-on"></i>
                                    </button>
                                    <button onclick="deleteCoupon(<?= $coupon['id'] ?>)" 
                                            class="px-3 py-1 rounded-lg glass-hover text-red-600 font-semibold text-sm" title="Verwijderen">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center" style="color: var(--text-muted);">
                            <i class="bi bi-tag text-6xl mb-4 block accent-primary"></i>
                            <p class="text-xl font-semibold mb-2">Geen kortingscodes</p>
                            <p>Maak je eerste kortingscode aan</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="couponModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-lg">
        <div class="card-glass p-8">
            <h2 id="modalTitle" class="text-2xl font-bold mb-6" style="color: var(--text-primary);">
                <i class="bi bi-tag accent-primary mr-2"></i>Nieuwe Kortingscode
            </h2>
            
            <form id="couponForm">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="coupon_id" id="couponId" value="">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Code *</label>
                        <input type="text" name="code" id="codeInput" required
                               class="w-full px-4 py-3 rounded-lg glass border text-lg font-mono uppercase" 
                               style="border-color: var(--border-glass);" placeholder="KORTING20">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Type</label>
                            <select name="type" id="typeInput" class="w-full px-4 py-3 rounded-lg glass border" style="border-color: var(--border-glass);">
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Vast Bedrag (€)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Waarde *</label>
                            <input type="number" name="value" id="valueInput" step="0.01" min="0" required
                                   class="w-full px-4 py-3 rounded-lg glass border" style="border-color: var(--border-glass);" placeholder="20">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Min. Bestelling (€)</label>
                            <input type="number" name="min_order" id="minOrderInput" step="0.01" min="0"
                                   class="w-full px-4 py-3 rounded-lg glass border" style="border-color: var(--border-glass);" placeholder="0">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Max Gebruik (0 = onbeperkt)</label>
                            <input type="number" name="max_uses" id="maxUsesInput" min="0"
                                   class="w-full px-4 py-3 rounded-lg glass border" style="border-color: var(--border-glass);" placeholder="0">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Vervaldatum (optioneel)</label>
                        <input type="date" name="expires_at" id="expiresInput"
                               class="w-full px-4 py-3 rounded-lg glass border" style="border-color: var(--border-glass);">
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="isActiveInput" checked 
                               class="w-5 h-5 accent-green-500">
                        <label for="isActiveInput" class="font-semibold">Actief</label>
                    </div>
                </div>
                
                <div class="flex justify-end gap-4 mt-8">
                    <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-lg glass-hover font-semibold">
                        Annuleren
                    </button>
                    <button type="submit" class="accent-bg text-white px-6 py-3 rounded-lg font-bold hover:opacity-90 transition">
                        <i class="bi bi-check-circle mr-2"></i>Opslaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('couponModal').classList.remove('hidden');
    document.getElementById('formAction').value = 'create';
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-tag accent-primary mr-2"></i>Nieuwe Kortingscode';
    document.getElementById('couponForm').reset();
    document.getElementById('isActiveInput').checked = true;
}

function closeModal() {
    document.getElementById('couponModal').classList.add('hidden');
}

function editCoupon(coupon) {
    document.getElementById('couponModal').classList.remove('hidden');
    document.getElementById('formAction').value = 'update';
    document.getElementById('couponId').value = coupon.id;
    document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil accent-primary mr-2"></i>Kortingscode Bewerken';
    
    document.getElementById('codeInput').value = coupon.code;
    document.getElementById('typeInput').value = coupon.type;
    document.getElementById('valueInput').value = coupon.value;
    document.getElementById('minOrderInput').value = coupon.min_order_amount;
    document.getElementById('maxUsesInput').value = coupon.max_uses;
    document.getElementById('expiresInput').value = coupon.expires_at || '';
    document.getElementById('isActiveInput').checked = coupon.is_active == 1;
}

document.getElementById('couponForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        if (result.success) {
            alert('✅ ' + (result.message || 'Opgeslagen'));
            location.reload();
        } else {
            alert('❌ Fout: ' + result.message);
        }
    } catch (error) {
        alert('❌ Er is een fout opgetreden');
    }
});

async function toggleCoupon(id) {
    const formData = new FormData();
    formData.append('action', 'toggle');
    formData.append('coupon_id', id);
    
    await fetch(window.location.href, { method: 'POST', body: formData });
    location.reload();
}

async function deleteCoupon(id) {
    if (!confirm('Weet je zeker dat je deze kortingscode wilt verwijderen?')) return;
    
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('coupon_id', id);
    
    await fetch(window.location.href, { method: 'POST', body: formData });
    location.reload();
}
</script>

<?php require_once '../../includes/footer.php'; ?>
