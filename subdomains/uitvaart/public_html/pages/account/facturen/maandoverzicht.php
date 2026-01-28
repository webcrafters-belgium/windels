<?php
session_start();
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/account/login.php");
    exit;
}

$partner_id = $_SESSION['partner_id'];
$maand = date('m');
$jaar = date('Y');

$factuurregels = [];
$totaal_bedrag = 0.0;

$sql = "
    SELECT o.id AS order_id, o.order_number, o.created_at, op.product_id, op.quantity
    FROM orders o
    INNER JOIN order_products op ON o.id = op.order_id
    WHERE o.funeral_partner_id = ?
      AND MONTH(o.created_at) = ?
      AND YEAR(o.created_at) = ?
    ORDER BY o.created_at DESC
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('iii', $partner_id, $maand, $jaar);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $pid = (int)$row['product_id'];
    $qty = (int)$row['quantity'];
    $product = null;
    $prijs = 0;

    $res = $mysqli_medewerkers->query("SELECT title, total_product_price FROM epoxy_products WHERE id = $pid AND sub_category = 'uitvaart'");
    if ($res && $res->num_rows > 0) {
        $data = $res->fetch_assoc();
        $product = $data['title'];
        $prijs = (float)$data['total_product_price'];
    }

    if (!$product) {
        $res = $mysqli_medewerkers->query("SELECT title, total_product_price FROM kaarsen_products WHERE id = $pid");
        if ($res && $res->num_rows > 0) {
            $data = $res->fetch_assoc();
            $product = $data['title'];
            $prijs = (float)$data['total_product_price'];
        }
    }

    if (!$product) continue;

    $subtotaal = $prijs * $qty;
    $totaal_bedrag += $subtotaal;

    $factuurregels[] = [
        'datum' => date('d/m/Y', strtotime($row['created_at'])),
        'ordernummer' => $row['order_number'],
        'product' => $product,
        'aantal' => $qty,
        'prijs' => number_format($prijs, 2, ',', '.'),
        'subtotaal' => number_format($subtotaal, 2, ',', '.'),
    ];
}
$stmt->close();
?>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php'; ?>
<style>
         body {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
     }
     .dashboard-page {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 3rem auto 2rem auto;
}
    .info-box {
    background-color: #f5f5f5;
    border-left: 4px solid #5a7d5a; /* donkergroen zoals in huisstijl */
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    font-size: 1rem;
    color: #333;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

</style>
<main class="dashboard-page container">
    <div class="dashboard-welcome">
        <h1>Maandoverzicht – <?= date('F Y') ?></h1>
        <p>Hieronder vind je een overzicht van alle bestellingen die deze maand werden geregistreerd voor jouw account.</p>
    </div>

    <?php if (empty($factuurregels)): ?>
        <div class="info-box">
            <p>Er werden deze maand nog geen bestellingen geplaatst.</p>
        </div>
    <?php else: ?>
        <div class="dashboard-table">
            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Ordernummer</th>
                        <th>Product</th>
                        <th>Aantal</th>
                        <th>Prijs (excl.)</th>
                        <th>Subtotaal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($factuurregels as $regel): ?>
                        <tr>
                            <td><?= $regel['datum'] ?></td>
                            <td><?= htmlspecialchars($regel['ordernummer']) ?></td>
                            <td><?= htmlspecialchars($regel['product']) ?></td>
                            <td style="text-align: center;"><?= $regel['aantal'] ?></td>
                            <td style="text-align: right;">€<?= $regel['prijs'] ?></td>
                            <td style="text-align: right;">€<?= $regel['subtotaal'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align: right;">Totaal:</th>
                        <th style="text-align: right;">€<?= number_format($totaal_bedrag, 2, ',', '.') ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>

    <div class="dashboard-actions">
        <div class="dashboard-card">
            <h2>Terug naar Facturen</h2>
            <p>Keer terug naar het factuuroverzicht van je account.</p>
            <a href="index.php" class="btn">← Terug</a>
        </div>
    </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
