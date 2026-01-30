<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>❌ Ongeldig klant-ID.</p>";
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php';
    exit;
}

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>❌ Klant niet gevonden.</p>";
    require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php';
    exit;
}

$klant = $result->fetch_assoc();


$totaal_stmt = $conn->prepare("SELECT SUM(total_price) AS totaal_spent 
  FROM orders 
  WHERE email = ?");
$totaal_stmt->bind_param("s", $klant['email']);
$totaal_stmt->execute();
$totaal_result = $totaal_stmt->get_result();
$totaal_row = $totaal_result->fetch_assoc();
$totaal_bedrag = $totaal_row['totaal_spent'] ?? 0;



// Orders ophalen op basis van e-mail
$order_stmt = $conn->prepare("SELECT id, total_price, shipping_cost, status, created_at, tracking_code, tracking_url 
  FROM orders 
  WHERE email = ? 
  ORDER BY created_at DESC");
$order_stmt->bind_param("s", $klant['email']);
$order_stmt->execute();
$orders = $order_stmt->get_result();

?>

<div class="klant-detail-pagina">
  <a href="/admin/pages/customers/" class="btn-terug">⬅ Terug naar overzicht</a>
  <h2>👤 Klantdetails: <?= htmlspecialchars($klant['first_name'] . ' ' . $klant['last_name']) ?></h2>

  <table class="klant-info">
    <tr><th>Email:</th><td><?= htmlspecialchars($klant['email']) ?></td></tr>
    <tr><th>Telefoon:</th><td><?= htmlspecialchars($klant['phone'] ?? '-') ?></td></tr>
    <tr><th>Adres:</th><td><?= htmlspecialchars($klant['address'] ?? '-') ?></td></tr>
    <tr><th>Postcode / Stad:</th><td><?= htmlspecialchars($klant['zipcode'] . ' ' . $klant['city']) ?></td></tr>
    <tr><th>Land:</th><td><?= htmlspecialchars($klant['country'] ?? '-') ?></td></tr>
    <tr><th>Account aangemaakt op:</th><td><?= date('d/m/Y H:i', strtotime($klant['created_at'])) ?></td></tr>
    <tr><th>Laatste login:</th><td><?= $klant['last_login'] ? date('d/m/Y H:i', strtotime($klant['last_login'])) : '-' ?></td></tr>
    <tr><th>Status:</th><td><?= ucfirst($klant['status']) ?></td></tr>
  </table>

    <hr>
    <h3>🧾 Bestellingen</h3>
    <h5>💶 Totaal uitgegeven</h5>
    <p><strong>€ <?= number_format($totaal_bedrag, 2, ',', '.') ?></strong></p>


    <?php if ($orders->num_rows === 0): ?>
        <p>Geen bestellingen gevonden.</p>
    <?php else: ?>
        <table class="orders-tabel">
            <thead>
            <tr>
                <th>Order_id</th>
                <th>Datum</th>
                <th>Status</th>
                <th>Bedrag</th>
                <th>Verzending</th>
                <th>Tracking</th>
                <th>Actie</th>

            </tr>
            </thead>
            <tbody>
            <?php while ($order = $orders->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                    <td><?= ucfirst($order['status']) ?></td>
                    <td>€ <?= number_format($order['total_price'], 2, ',', '.') ?></td>
                    <td>€ <?= number_format($order['shipping_cost'], 2, ',', '.') ?></td>
                    <td>
                        <?php if (!empty($order['tracking_url'])): ?>
                            <a href="<?= htmlspecialchars($order['tracking_url']) ?>" target="_blank">
                                📦 <?= htmlspecialchars($order['tracking_code']) ?>
                            </a>
                        <?php else: ?>
                            <span style="color: #888;">n.v.t.</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="/admin/pages/orders/detail.php?id=<?= $order['id'] ?>" class="btn-small">👁 Bekijk</a>
                    </td>

                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
