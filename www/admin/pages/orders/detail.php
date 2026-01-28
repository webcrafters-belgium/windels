<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Ongeldig order-ID.</p>";
    include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
    exit;
}

$order_id = (int)$_GET['id'];

// Order ophalen
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    echo "<p>Order niet gevonden.</p>";
    include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
    exit;
}

$order = $order_result->fetch_assoc();

// Order items ophalen
$item_stmt = $conn->prepare("SELECT oi.*, p.name AS product_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$order_items = $item_stmt->get_result();
?>

    <div class="order-detail">
        <a href="/admin/pages/customers/" class="btn-small">Terug naar klanten</a>
        <h2> Order #<?= $order['id'] ?> (<?= htmlspecialchars($order['status'] ?? '') ?>)</h2>

        <form method="post" action="update_status.php" style="margin-bottom: 1rem;">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <label for="status">Status wijzigen:</label>
            <select name="status" id="status">
                <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>In afwachting</option>
                <option value="paid" <?= $order['status'] == 'paid' ? 'selected' : '' ?>>Betaald</option>
                <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Verzonden</option>
                <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Geannuleerd</option>
            </select>
            <button type="submit">Bijwerken</button>
        </form>

        <form method="post" action="resend_invoice.php" style="margin-bottom: 1rem;">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <button type="submit">Factuur opnieuw verzenden</button>
        </form>

        <a href="/admin/pages/orders/pdf_invoice.php?id=<?= $order['id'] ?>" class="btn-small" target="_blank">Download PDF-factuur</a>

        <h3> Klantinformatie</h3>
        <table class="detail-table">
            <tr><th>Naam:</th><td><?= htmlspecialchars($order['name'] ?? 'Onbekend') ?></td></tr>
            <tr><th>Email:</th><td><?= htmlspecialchars($order['email'] ?? '-') ?></td></tr>
            <tr><th>Telefoon:</th><td><?= htmlspecialchars($order['phone'] ?? '-') ?></td></tr>
            <tr><th>Adres:</th>
                <td>
                    <?= htmlspecialchars(trim(($order['street'] ?? '') . ' ' . ($order['number'] ?? ''))) ?>,
                    <?= htmlspecialchars(trim(($order['zipcode'] ?? '') . ' ' . ($order['city'] ?? ''))) ?>
                    (<?= htmlspecialchars($order['country'] ?? '-') ?>)
                </td>
            </tr>
            <tr><th>Besteldatum:</th><td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td></tr>
        </table>

        <h3>Orderinhoud</h3>
        <?php if ($order_items->num_rows === 0): ?>
            <p>Geen producten gevonden.</p>
        <?php else: ?>
            <table class="order-items">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Aantal</th>
                    <th>Prijs per stuk</th>
                    <th>Subtotaal</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $totaal = 0;
                while ($item = $order_items->fetch_assoc()):
                    // total_price bevat al het totaalbedrag voor deze regel
                    $sub = $item['total_price'];
                    $totaal += $sub;
                    ?>
                    <tr>
                        <td><?= $item['product_name'] ? htmlspecialchars($item['product_name']) : '<i>Product verwijderd</i>' ?></td>
                        <td><?= (int) $item['quantity'] ?></td>
                        <td>€ <?= number_format(($item['quantity'] > 0 ? $item['total_price'] / $item['quantity'] : 0), 2, ',', '.') ?></td>
                        <td>€ <?= number_format($sub, 2, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>

                </tbody>
            </table>
        <?php endif; ?>

        <h3> Overzicht</h3>
        <table class="detail-table">
            <tr><th>Subtotaal:</th><td>€ <?= number_format($totaal, 2, ',', '.') ?></td></tr>
            <tr><th>Verzendkosten:</th><td>€ <?= number_format($order['shipping_cost'], 2, ',', '.') ?></td></tr>
            <tr><th><strong>Totaal:</strong></th><td><strong>€ <?= number_format($order['total_price'], 2, ',', '.') ?></strong></td></tr>
            <tr><th>Factuur verzonden:</th>
                <td><?= isset($order['invoice_sent']) && $order['invoice_sent'] ? '✅ Ja' : '❌ Nee' ?></td>
            </tr>
        </table>

        <h3>Tracking</h3>
        <?php if (!empty($order['tracking_url'])): ?>
            <p>
                <a href="<?= htmlspecialchars($order['tracking_url']) ?>" target="_blank">
                     <?= htmlspecialchars($order['tracking_code'] ?? 'Volg zending') ?>
                </a>
            </p>
        <?php else: ?>
            <p><span style="color: #888;">Geen tracking beschikbaar.</span></p>
        <?php endif; ?>
    </div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>