<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');
include($_SERVER['DOCUMENT_ROOT'] . '/header.php');

$user_id = $_SESSION['user_id'] ?? null;

echo "<div class='orders-container min-vh-100 my-5'>";

if (!$user_id) {
    echo "
        <div class='container'>
            <div class='alert alert-danger my-5'>Je moet ingelogd zijn om je bestellingen te bekijken.</div>

            <div class='card mt-4 shadow-sm'>
                <div class='card-header bg-light'>
                    <h5 class='mb-0'>🔐 Log in op je account</h5>
                </div>
                <div class='card-body'>";
                    include $_SERVER['DOCUMENT_ROOT'] . '/partials/forms/login_form.php';
                    echo"
                </div>
            </div>
        </div>
    ";
    include($_SERVER['DOCUMENT_ROOT'] . '/footer.php');
    exit;
}


// Haal bestellingen op van de gebruiker
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>


    <h2 class="mb-4">📦 Mijn Bestellingen</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>Bestelnummer</th>
                    <th>Datum</th>
                    <th>Status</th>
                    <th>Totaal</th>
                    <th>Acties</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($order['order_number']) ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($order['created_at'])) ?></td>
                        <td><?= ucfirst(htmlspecialchars($order['status'])) ?></td>
                        <td>€<?= number_format($order['total'], 2, ',', '.') ?></td>
                        <td>
                            <a href="/pages/account/bestellingen/detail.php?order_id=<?= $order['id'] ?>" class="btn btn-sm btn-primary">Details</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Je hebt nog geen bestellingen geplaatst.</div>
    <?php endif; ?>


<?php include($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); ?>
