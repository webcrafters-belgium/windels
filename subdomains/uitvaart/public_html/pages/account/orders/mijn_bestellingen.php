<?php
session_start();
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

// Alleen toegankelijk voor ingelogde uitvaartdiensten
if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/login.php");
    exit;
}

// Functie om klantdata te ontsleutelen
function decryptField($data, $key) {
    $cipher = "AES-256-CBC";
    $data = base64_decode($data);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}

// Haal alle bestellingen van deze uitvaartdienst op
$orders = [];
if ($use_db) {
    $search = trim($_GET['q'] ?? '');

    $sql = "
        SELECT o.id AS order_id, o.order_number, o.klantnummer_partner,
               o.created_at, o.status,
               ANY_VALUE(op.klant_naam) AS klant_naam,
               ANY_VALUE(op.klant_email) AS klant_email,
               ANY_VALUE(op.klant_telefoon) AS klant_telefoon,
               ANY_VALUE(op.klant_adres) AS klant_adres
        FROM orders o
        LEFT JOIN order_private op ON o.id = op.order_id
        WHERE o.funeral_partner_id = ?
    ";

    if ($search !== '') {
        $sql .= " AND (o.order_number LIKE ? OR o.klantnummer_partner LIKE ?)";
    }

    $sql .= "
        GROUP BY o.id, o.order_number, o.klantnummer_partner, o.created_at, o.status
        ORDER BY o.created_at DESC
    ";

    $stmt = $mysqli->prepare($sql);

    if ($search !== '') {
        $like = "%$search%";
        $stmt->bind_param('iss', $_SESSION['partner_id'], $like, $like);
    } else {
        $stmt->bind_param('i', $_SESSION['partner_id']);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['klant_naam'] = decryptField($row['klant_naam'], $encryption_key);
        $row['klant_email'] = decryptField($row['klant_email'], $encryption_key);
        $row['klant_telefoon'] = decryptField($row['klant_telefoon'], $encryption_key);
        $row['klant_adres'] = decryptField($row['klant_adres'], $encryption_key);
        $orders[] = $row;
    }
    $stmt->close();
}
?>
<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>
<style>
  body {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
}

.orders-page {
    background-color: rgba(255, 255, 255, 0.92);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 3rem auto 2rem auto;
    max-width: 720px;
    width: 90%;
    box-sizing: border-box;
}

/* ✅ Mobiele optimalisatie */
@media (max-width: 768px) {
    .orders-page {
        padding: 2rem 1rem;
        margin: 2rem 1rem;
        border-radius: 8px;
    }
}

</style>
<main class="orders-page">
    <div class="container">
        <h2>Mijn bestellingen</h2>

        <!-- Zoekbalk -->
        <form method="get" class="search-bar">
            <input type="text" name="q" placeholder="Zoek op ordernr. of klantnr."
                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button type="submit" class="btn btn-primary">Zoeken</button>
            <?php if (!empty($_GET['q'])): ?>
                <a href="mijn_bestellingen.php" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </form>
        <?php if (empty($orders)): ?>
            <?php if (!empty($_GET['q'])): ?>
                <p><strong>Geen bestellingen gevonden voor deze zoekopdracht.</strong></p>
            <?php else: ?>
                <p>Er zijn nog geen bestellingen geplaatst.</p>
            <?php endif; ?>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Ordernummer</th>
                        <th>Datum</th>
                        <th>Klantnr.</th>
                        <th>Klant</th>
                        <th>Status</th>
                        <th>Actie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): 
                        $status_class = 'status-' . $order['status']; 
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_number']) ?></td>
                            <td><?= htmlspecialchars($order['created_at']) ?></td>
                            <td><?= htmlspecialchars($order['klantnummer_partner'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($order['klant_naam']) ?></td>
                           <td>
                                <span class="status-label <?= $status_class ?>">
                                    <?= ucfirst(str_replace('_',' ',htmlspecialchars($order['status']))) ?>
                                </span>
                            </td>
                            <td>
                                <a href="view_order.php?id=<?= (int)$order['order_id'] ?>" class="btn">Bekijken</a><br>
                                <a href="/pages/account/orders/pdf/pakbon_<?= (int)$order['order_id'] ?>.pdf" target="_blank" class="btn" download>📄 Pakbon downloaden</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <p style="margin-top: 2rem;">
            <a href="/pages/account/dashboard.php" class="btn btn-primary">← Terug naar dashboard</a>
        </p>
    </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
