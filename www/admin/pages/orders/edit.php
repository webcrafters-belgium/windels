<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

$orderId = intval($_GET['id'] ?? 0);
if (!$orderId) {
    echo '<div class="card-glass p-8 text-center"><p style="color: var(--text-muted);">Geen geldig order-ID.</p></div>';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
$stmt->execute([$orderId]);
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo '<div class="card-glass p-8 text-center"><p style="color: var(--text-muted);">Bestelling niet gevonden.</p></div>';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php';
    exit;
}

$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? $order['status'];
    $tracking = $_POST['tracking_number'] ?? '';
    $note = $_POST['note'] ?? '';
    $payment_method = $_POST['payment_method'] ?? $order['payment_method'];
    $shipping_method = $_POST['shipping_method'] ?? $order['shipping_method'];

    $update = $conn->prepare("UPDATE orders SET status = ?, tracking_number = ?, admin_notes = ?, payment_method = ?, shipping_method = ? WHERE id = ?");
    $update->execute([$status, $tracking, $note, $payment_method, $shipping_method, $orderId]);

    $success = true;
    $order['status'] = $status;
    $order['tracking_number'] = $tracking;
    $order['admin_notes'] = $note;
    $order['payment_method'] = $payment_method;
    $order['shipping_method'] = $shipping_method;
}
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-pencil-square accent-primary mr-3"></i>Bestelling #<?= $order['id'] ?> bewerken
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Pas de orderdetails aan</p>
        </div>
        <div class="flex gap-3">
            <a href="/API/orders/download_invoice.php?order_id=<?= $order['id'] ?>" target="_blank" class="glass px-4 py-2 rounded-xl font-semibold hover:bg-white/10 transition flex items-center gap-2">
                <i class="bi bi-file-earmark-text"></i>Factuur
            </a>
            <a href="/API/orders/packing_slip.php?order_id=<?= $order['id'] ?>" target="_blank" class="glass px-4 py-2 rounded-xl font-semibold hover:bg-white/10 transition flex items-center gap-2 text-emerald-400">
                <i class="bi bi-box-seam"></i>Pakbon
            </a>
            <a href="index.php" class="glass px-4 py-2 rounded-xl font-semibold hover:bg-white/10 transition flex items-center gap-2">
                <i class="bi bi-arrow-left"></i>Terug
            </a>
        </div>
    </div>
</div>

<?php if ($success): ?>
<div class="card-glass p-4 mb-6 border-emerald-500/30 bg-emerald-500/10">
    <div class="flex items-center space-x-3 text-emerald-400">
        <i class="bi bi-check-circle"></i>
        <span>Bestelling succesvol bijgewerkt!</span>
    </div>
</div>
<?php endif; ?>

<!-- FORM -->
<div class="card-glass p-8">
    <form method="post" class="space-y-8">
        <!-- Order Settings -->
        <div>
            <h3 class="font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                <i class="bi bi-gear text-teal-400"></i>Orderinstellingen
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Status</label>
                    <select name="status" class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>In afwachting</option>
                        <option value="paid" <?= $order['status'] === 'paid' ? 'selected' : '' ?>>Betaald</option>
                        <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Verzonden</option>
                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Afgerond</option>
                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Geannuleerd</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Trackingnummer</label>
                    <input type="text" name="tracking_number" value="<?= htmlspecialchars($order['tracking_number'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
                </div>

                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Betaalmethode</label>
                    <select name="payment_method" class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
                        <?php foreach (["bancontact", "creditcard", "mollie", "overschrijving"] as $opt): ?>
                            <option value="<?= $opt ?>" <?= ($order['payment_method'] ?? '') === $opt ? 'selected' : '' ?>><?= ucfirst($opt) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Verzendmethode</label>
                    <input type="text" name="shipping_method" value="<?= htmlspecialchars($order['shipping_method'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);">
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div>
            <label class="text-sm font-semibold mb-2 block" style="color: var(--text-secondary);">Notities (intern)</label>
            <textarea name="note" rows="4" class="w-full px-4 py-3 rounded-xl glass border" style="border-color: var(--border-glass);"><?= htmlspecialchars($order['admin_notes'] ?? '') ?></textarea>
        </div>

        <!-- Customer Info (readonly) -->
        <div>
            <h3 class="font-semibold mb-4 flex items-center gap-2" style="color: var(--text-primary);">
                <i class="bi bi-person text-teal-400"></i>Klantgegevens
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Naam</label>
                    <input class="w-full px-4 py-3 rounded-xl glass border opacity-60" style="border-color: var(--border-glass);" readonly value="<?= htmlspecialchars($order['name']) ?>">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">E-mail</label>
                    <input class="w-full px-4 py-3 rounded-xl glass border opacity-60" style="border-color: var(--border-glass);" readonly value="<?= htmlspecialchars($order['email']) ?>">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Telefoon</label>
                    <input class="w-full px-4 py-3 rounded-xl glass border opacity-60" style="border-color: var(--border-glass);" readonly value="<?= htmlspecialchars($order['phone'] ?? '') ?>">
                </div>
                <div class="flex flex-col md:col-span-2">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Adres</label>
                    <input class="w-full px-4 py-3 rounded-xl glass border opacity-60" style="border-color: var(--border-glass);" readonly value="<?= htmlspecialchars(($order['street'] ?? '') . ' ' . ($order['number'] ?? '')) ?>">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">Postcode & Plaats</label>
                    <input class="w-full px-4 py-3 rounded-xl glass border opacity-60" style="border-color: var(--border-glass);" readonly value="<?= htmlspecialchars(($order['zipcode'] ?? '') . ' ' . ($order['city'] ?? '')) ?>">
                </div>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="text-sm" style="color: var(--text-muted);">
            Aangemaakt op: <?= date('d-m-Y H:i', strtotime($order['created_at'])) ?> |
            Laatst bijgewerkt: <?= date('d-m-Y H:i', strtotime($order['updated_at'] ?? $order['created_at'])) ?>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="accent-bg text-white px-8 py-3 rounded-xl font-semibold hover:opacity-90 transition flex items-center gap-2">
                <i class="bi bi-check-lg"></i>Opslaan
            </button>
            <a href="index.php" class="glass px-6 py-3 rounded-xl font-semibold hover:bg-white/10 transition">
                Annuleren
            </a>
        </div>
    </form>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
