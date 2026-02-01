<?php
require_once '../../includes/header.php';

// Check database connection
$dbAvailable = isset($conn) && $conn !== null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $dbAvailable) {
    header('Content-Type: application/json');
    
    $section = $_POST['section'] ?? '';
    
    // Check/create settings table
    $tableCheck = @$conn->query("SHOW TABLES LIKE 'shop_settings'");
    if ($tableCheck && $tableCheck->num_rows === 0) {
        @$conn->query("
            CREATE TABLE shop_settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(100) UNIQUE NOT NULL,
                setting_value TEXT,
                setting_group VARCHAR(50) DEFAULT 'general',
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    foreach ($_POST as $key => $value) {
        if ($key === 'section' || $key === 'csrf_token') continue;
        
        $stmt = @$conn->prepare("
            INSERT INTO shop_settings (setting_key, setting_value, setting_group) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
        ");
        if ($stmt) {
            $stmt->bind_param('sss', $key, $value, $section);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    echo json_encode(['success' => true, 'message' => 'Instellingen opgeslagen']);
    exit;
}

if ($dbAvailable) {
    // Check/create settings table
    $tableCheck = @$conn->query("SHOW TABLES LIKE 'shop_settings'");
    if ($tableCheck && $tableCheck->num_rows === 0) {
        @$conn->query("
            CREATE TABLE shop_settings (
                id INT AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(100) UNIQUE NOT NULL,
                setting_value TEXT,
                setting_group VARCHAR(50) DEFAULT 'general',
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
}

// Fetch current settings
function getSetting($conn, $key, $default = '') {
    if (!$conn) return $default;
    $stmt = @$conn->prepare("SELECT setting_value FROM shop_settings WHERE setting_key = ?");
    if (!$stmt) return $default;
    $stmt->bind_param('s', $key);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $result['setting_value'] ?? $default;
}

$settings = [
    'shop_name' => getSetting($conn, 'shop_name', 'Windels Green & Deco Resin'),
    'shop_email' => getSetting($conn, 'shop_email', ''),
    'shop_phone' => getSetting($conn, 'shop_phone', ''),
    'shop_address' => getSetting($conn, 'shop_address', ''),
    'shop_btw' => getSetting($conn, 'shop_btw', ''),
    'shop_kvk' => getSetting($conn, 'shop_kvk', ''),
    'shipping_cost' => getSetting($conn, 'shipping_cost', '4.95'),
    'free_shipping_threshold' => getSetting($conn, 'free_shipping_threshold', '50.00'),
    'currency' => getSetting($conn, 'currency', 'EUR'),
    'mollie_api_key' => getSetting($conn, 'mollie_api_key', ''),
    'mollie_test_mode' => getSetting($conn, 'mollie_test_mode', '1'),
    'smtp_host' => getSetting($conn, 'smtp_host', ''),
    'smtp_port' => getSetting($conn, 'smtp_port', '587'),
    'smtp_user' => getSetting($conn, 'smtp_user', ''),
    'smtp_pass' => getSetting($conn, 'smtp_pass', ''),
    'order_email_enabled' => getSetting($conn, 'order_email_enabled', '1'),
    'stock_alert_threshold' => getSetting($conn, 'stock_alert_threshold', '5'),
];
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
            <p class="text-sm" style="color: var(--text-muted);">De database verbinding is niet actief. Instellingen kunnen niet worden opgeslagen.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-2" style="color: var(--text-primary);">
        <i class="bi bi-gear accent-primary mr-3"></i>Instellingen
    </h1>
    <p class="text-lg" style="color: var(--text-muted);">Beheer je webshop instellingen</p>
</div>

<!-- Settings Tabs -->
<div class="mb-8">
    <div class="flex gap-2 border-b" style="border-color: var(--border-glass);">
        <button onclick="showTab('general')" id="tab-general" class="tab-btn px-6 py-3 font-semibold border-b-2 border-transparent hover:border-green-500 transition active">
            <i class="bi bi-shop mr-2"></i>Algemeen
        </button>
        <button onclick="showTab('shipping')" id="tab-shipping" class="tab-btn px-6 py-3 font-semibold border-b-2 border-transparent hover:border-green-500 transition">
            <i class="bi bi-truck mr-2"></i>Verzending
        </button>
        <button onclick="showTab('payment')" id="tab-payment" class="tab-btn px-6 py-3 font-semibold border-b-2 border-transparent hover:border-green-500 transition">
            <i class="bi bi-credit-card mr-2"></i>Betalingen
        </button>
        <button onclick="showTab('email')" id="tab-email" class="tab-btn px-6 py-3 font-semibold border-b-2 border-transparent hover:border-green-500 transition">
            <i class="bi bi-envelope mr-2"></i>E-mail
        </button>
        <button onclick="showTab('notifications')" id="tab-notifications" class="tab-btn px-6 py-3 font-semibold border-b-2 border-transparent hover:border-green-500 transition">
            <i class="bi bi-bell mr-2"></i>Meldingen
        </button>
    </div>
</div>

<!-- General Settings -->
<div id="panel-general" class="settings-panel">
    <form class="settings-form" data-section="general">
        <div class="card-glass p-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: var(--text-primary);">
                <i class="bi bi-shop accent-primary mr-3"></i>Winkel Informatie
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Winkelnaam</label>
                    <input type="text" name="shop_name" value="<?= htmlspecialchars($settings['shop_name']) ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">E-mail</label>
                    <input type="email" name="shop_email" value="<?= htmlspecialchars($settings['shop_email']) ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Telefoon</label>
                    <input type="text" name="shop_phone" value="<?= htmlspecialchars($settings['shop_phone']) ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Adres</label>
                    <textarea name="shop_address" rows="2"
                              class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"><?= htmlspecialchars($settings['shop_address']) ?></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">BTW Nummer</label>
                    <input type="text" name="shop_btw" value="<?= htmlspecialchars($settings['shop_btw']) ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">KVK Nummer</label>
                    <input type="text" name="shop_kvk" value="<?= htmlspecialchars($settings['shop_kvk']) ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="accent-bg text-white px-6 py-3 rounded-lg font-bold hover:opacity-90 transition">
                    <i class="bi bi-check-circle mr-2"></i>Opslaan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Shipping Settings -->
<div id="panel-shipping" class="settings-panel hidden">
    <form class="settings-form" data-section="shipping">
        <div class="card-glass p-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: var(--text-primary);">
                <i class="bi bi-truck accent-primary mr-3"></i>Verzending Instellingen
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Verzendkosten (€)</label>
                    <input type="number" name="shipping_cost" step="0.01" value="<?= htmlspecialchars($settings['shipping_cost']) ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Gratis verzending vanaf (€)</label>
                    <input type="number" name="free_shipping_threshold" step="0.01" value="<?= htmlspecialchars($settings['free_shipping_threshold']) ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                    <p class="text-sm mt-1" style="color: var(--text-muted);">Stel op 0 om gratis verzending uit te schakelen</p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Valuta</label>
                    <select name="currency" class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                        <option value="EUR" <?= $settings['currency'] === 'EUR' ? 'selected' : '' ?>>Euro (€)</option>
                        <option value="USD" <?= $settings['currency'] === 'USD' ? 'selected' : '' ?>>US Dollar ($)</option>
                        <option value="GBP" <?= $settings['currency'] === 'GBP' ? 'selected' : '' ?>>British Pound (£)</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="accent-bg text-white px-6 py-3 rounded-lg font-bold hover:opacity-90 transition">
                    <i class="bi bi-check-circle mr-2"></i>Opslaan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Payment Settings -->
<div id="panel-payment" class="settings-panel hidden">
    <form class="settings-form" data-section="payment">
        <div class="card-glass p-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: var(--text-primary);">
                <i class="bi bi-credit-card accent-primary mr-3"></i>Mollie Betalingen
            </h2>
            
            <div class="mb-6 p-4 rounded-lg" style="background: var(--bg-glass); border: 1px solid var(--border-glass);">
                <div class="flex items-center">
                    <img src="https://www.mollie.com/external/icons/mollie-logo.svg" alt="Mollie" class="h-8 mr-4" style="filter: brightness(0) invert(1);">
                    <div>
                        <p class="font-semibold">Mollie Payment Gateway</p>
                        <p class="text-sm" style="color: var(--text-muted);">Accepteer iDEAL, Creditcard, Bancontact en meer</p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Mollie API Key</label>
                    <input type="password" name="mollie_api_key" value="<?= htmlspecialchars($settings['mollie_api_key']) ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg font-mono" style="border-color: var(--border-glass);"
                           placeholder="live_xxxx of test_xxxx">
                    <p class="text-sm mt-1" style="color: var(--text-muted);">
                        Vind je API keys op <a href="https://my.mollie.com/dashboard/developers/api-keys" target="_blank" class="accent-primary">Mollie Dashboard</a>
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Test Modus</label>
                    <select name="mollie_test_mode" class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                        <option value="1" <?= $settings['mollie_test_mode'] === '1' ? 'selected' : '' ?>>Aan (Test API Key)</option>
                        <option value="0" <?= $settings['mollie_test_mode'] === '0' ? 'selected' : '' ?>>Uit (Live API Key)</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="accent-bg text-white px-6 py-3 rounded-lg font-bold hover:opacity-90 transition">
                    <i class="bi bi-check-circle mr-2"></i>Opslaan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Email Settings -->
<div id="panel-email" class="settings-panel hidden">
    <form class="settings-form" data-section="email">
        <div class="card-glass p-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: var(--text-primary);">
                <i class="bi bi-envelope accent-primary mr-3"></i>E-mail Instellingen (SMTP)
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">SMTP Host</label>
                    <input type="text" name="smtp_host" value="<?= htmlspecialchars($settings['smtp_host']) ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                           placeholder="smtp.example.com">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">SMTP Port</label>
                    <input type="text" name="smtp_port" value="<?= htmlspecialchars($settings['smtp_port']) ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);"
                           placeholder="587">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">SMTP Gebruikersnaam</label>
                    <input type="text" name="smtp_user" value="<?= htmlspecialchars($settings['smtp_user']) ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">SMTP Wachtwoord</label>
                    <input type="password" name="smtp_pass" value="<?= htmlspecialchars($settings['smtp_pass']) ?>"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="accent-bg text-white px-6 py-3 rounded-lg font-bold hover:opacity-90 transition">
                    <i class="bi bi-check-circle mr-2"></i>Opslaan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Notifications Settings -->
<div id="panel-notifications" class="settings-panel hidden">
    <form class="settings-form" data-section="notifications">
        <div class="card-glass p-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center" style="color: var(--text-primary);">
                <i class="bi bi-bell accent-primary mr-3"></i>Melding Instellingen
            </h2>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 rounded-lg" style="background: var(--bg-glass); border: 1px solid var(--border-glass);">
                    <div>
                        <h3 class="font-semibold text-lg">Order E-mails</h3>
                        <p class="text-sm" style="color: var(--text-muted);">Stuur automatisch bevestigingsmails bij nieuwe bestellingen</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="order_email_enabled" value="1" 
                               <?= $settings['order_email_enabled'] === '1' ? 'checked' : '' ?>
                               class="sr-only peer">
                        <div class="w-14 h-8 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-6 peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: var(--text-secondary);">Lage Voorraad Alert (stuks)</label>
                    <input type="number" name="stock_alert_threshold" value="<?= htmlspecialchars($settings['stock_alert_threshold']) ?>" min="0"
                           class="w-full px-4 py-3 rounded-lg glass border text-lg" style="border-color: var(--border-glass);">
                    <p class="text-sm mt-1" style="color: var(--text-muted);">Producten met voorraad onder dit aantal worden gemarkeerd</p>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="accent-bg text-white px-6 py-3 rounded-lg font-bold hover:opacity-90 transition">
                    <i class="bi bi-check-circle mr-2"></i>Opslaan
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.tab-btn.active {
    border-bottom-color: var(--accent) !important;
    color: var(--accent);
}
</style>

<script>
function showTab(tabName) {
    // Hide all panels
    document.querySelectorAll('.settings-panel').forEach(panel => panel.classList.add('hidden'));
    // Remove active from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    // Show selected panel and activate tab
    document.getElementById('panel-' + tabName).classList.remove('hidden');
    document.getElementById('tab-' + tabName).classList.add('active');
}

// Form submission
document.querySelectorAll('.settings-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('section', this.dataset.section);
        
        try {
            const response = await fetch(window.location.href, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            if (result.success) {
                alert('✅ ' + result.message);
            } else {
                alert('❌ Fout: ' + result.message);
            }
        } catch (error) {
            alert('❌ Er is een fout opgetreden');
        }
    });
});
</script>

<?php require_once '../../includes/footer.php'; ?>
