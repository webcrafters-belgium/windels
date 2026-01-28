<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');
include($_SERVER['DOCUMENT_ROOT'] . '/header.php');

$orderId = intval($_GET['id'] ?? 0);
if (!$orderId) die('Geen geldig order-ID.');

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
$stmt->execute([$orderId]);
$order = $stmt->get_result()->fetch_assoc();
if (!$order) die('Bestelling niet gevonden.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? $order['status'];
    $tracking = $_POST['tracking_number'] ?? '';
    $note = $_POST['note'] ?? '';
    $payment_method = $_POST['payment_method'] ?? $order['payment_method'];
    $shipping_method = $_POST['shipping_method'] ?? $order['shipping_method'];

    $update = $conn->prepare("UPDATE orders SET status = ?, tracking_number = ?, note = ?, payment_method = ?, shipping_method = ? WHERE id = ?");
    $update->execute([$status, $tracking, $note, $payment_method, $shipping_method, $orderId]);

    echo '<div class="alert alert-success">✅ Bestelling succesvol bijgewerkt!</div>';
    $order['status'] = $status;
    $order['tracking_number'] = $tracking;
    $order['note'] = $note;
    $order['payment_method'] = $payment_method;
    $order['shipping_method'] = $shipping_method;
}
?>

<style>
    .form-label{
        font-weight:bold;
    }
</style>

<div class="container mt-5 mb-5">
    <h1 class="mb-4">✏️ Bewerken bestelling #<?= $order['id'] ?></h1>

    <div class="mb-3">
        <a href="/API/orders/download_invoice.php?order_id=<?= $order['id'] ?>" target="_blank" class="btn btn-sm btn-outline-dark">🧾 Factuur</a>
        <a href="/API/orders/packing_slip.php?order_id=<?= $order['id'] ?>" target="_blank" class="btn btn-sm btn-outline-success">📦 Pakbon</a>
    </div>

    <form method="post" class="row g-3">

        <div class="col-md-4">
            <label class="form-label" for="status">Status</label>
            <select name="status" class="form-select">
                <?php foreach (["in behandeling", "betaald", "verzonden", "geannuleerd"] as $opt): ?>
                    <option value="<?= $opt ?>" <?= $order['status'] === $opt ? 'selected' : '' ?>><?= ucfirst($opt) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Trackingnummer</label>
            <input type="text" name="tracking_number" value="<?= htmlspecialchars($order['tracking_number'] ?? '') ?>" class="form-control">
        </div>

        <div class="col-md-4">
            <label class="form-label">Betaalmethode</label>
            <select name="payment_method" class="form-select">
                <?php foreach (["bancontact", "creditcard", "mollie", "overschrijving"] as $opt): ?>
                    <option value="<?= $opt ?>" <?= $order['payment_method'] === $opt ? 'selected' : '' ?>><?= ucfirst($opt) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Verzendmethode</label>
            <input type="text" name="shipping_method" value="<?= htmlspecialchars($order['shipping_method'] ?? '') ?>" class="form-control">
        </div>

        <div class="col-md-8">
            <label class="form-label">Notities (intern)</label>
            <textarea name="note" class="form-control" rows="4"><?= htmlspecialchars($order['admin_notes'] ?? '') ?></textarea>
        </div>

        <div class="col-12 mt-2 mb-2">
            <strong>Klantgegevens</strong>
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Naam</label>
                    <input class="form-control" readonly value="<?= htmlspecialchars($order['name']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">E-mail</label>
                    <input class="form-control" readonly value="<?= htmlspecialchars($order['email']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Telefoon</label>
                    <input class="form-control" readonly value="<?= htmlspecialchars($order['phone']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Adres</label>
                    <input class="form-control" readonly value="<?= htmlspecialchars($order['street'] . ' ' . $order['number']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Postcode</label>
                    <input class="form-control" readonly value="<?= htmlspecialchars($order['zipcode']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Plaats</label>
                    <input class="form-control" readonly value="<?= htmlspecialchars($order['city']) ?>">
                </div>
            </div>
        </div>

        <div class="col-12 text-muted">
            <small>
                Aangemaakt op: <?= date('d-m-Y H:i', strtotime($order['created_at'])) ?><br>
                Laatst bijgewerkt: <?= date('d-m-Y H:i', strtotime($order['updated_at'] ?? $order['created_at'])) ?>
            </small>
        </div>

        <div class="col-12">
            <button class="btn btn-success">💾 Opslaan</button>
            <a href="index.php" class="btn btn-secondary">Annuleren</a>
        </div>
    </form>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); ?>
