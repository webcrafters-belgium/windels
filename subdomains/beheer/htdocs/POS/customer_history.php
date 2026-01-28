<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
//require_once $_SERVER["DOCUMENT_ROOT"] . '/authenticatelogg.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/POS/LoyverseClient.php';

// Initialiseer LoyverseClient
$api_token = '56f733c909bf417f98a0ff88b1f3a983'; // Jouw API-token
$loyverse = new LoyverseClient($api_token);

// Haal de klant-ID op
$customer_id = $_GET['id'] ?? null;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Maximaal 10 records per pagina
$offset = ($page - 1) * $limit;

if (!$customer_id) {
    echo "<div class='alert alert-danger'>Geen klant-ID opgegeven.</div>";
    exit;
}

// Haal klantinformatie op
try {
    $customer = $loyverse->getCustomer($customer_id);
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Fout bij het ophalen van klantinformatie: " . $e->getMessage() . "</div>";
    exit;
}

// Haal bonnen en puntengeschiedenis op
try {
    // Haal de bonnen op via de API
    $receipts_data = $loyverse->getReceipts();
    $all_receipts = $receipts_data['receipts'] ?? [];

    // Filter bonnen specifiek voor deze klant-ID
    $customer_receipts = array_filter($all_receipts, function ($receipt) use ($customer_id) {
        return isset($receipt['customer_id']) && $receipt['customer_id'] === $customer_id;
    });

    // Verwerk paginering (neem alleen de records voor de huidige pagina)
    $total_receipts = count($customer_receipts);
    $receipts = array_slice($customer_receipts, $offset, $limit);

    // Bereken totaal aantal pagina's
    $total_pages = ceil($total_receipts / $limit);
} catch (Exception $e) {
    echo '<div class="alert alert-danger">Fout bij het ophalen van de klantgeschiedenis: ' . $e->getMessage() . '</div>';
    exit;
}

require $_SERVER["DOCUMENT_ROOT"] . '/header.php'; // Header includen
?>
<div class="container mt-3">
    <a href="klantenbestand.php" class="btn btn-primary">&larr; Terug naar Klantenbestand</a>
</div>
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="card-title">Geschiedenis van <?= htmlspecialchars($customer['name'] ?? '') ?></h2>
        </div>
        <div class="card-body">
            <h4>Persoonlijke gegevens</h4>
            <p><strong>Naam:</strong> <?= htmlspecialchars($customer['name'] ?? '') ?></p>
            <p><strong>E-mail:</strong> <?= htmlspecialchars($customer['email'] ?? '') ?></p>
            <p><strong>Telefoonnummer:</strong> <?= htmlspecialchars($customer['phone_number'] ?? '') ?></p>
            <p><strong>adres:</strong> <?= htmlspecialchars($customer['address'] ?? '') ?> <?= htmlspecialchars($customer['postalcode'] ?? '') ?> <?= htmlspecialchars($customer['city'] ?? '') ?>, <?= htmlspecialchars($customer['region'] ?? '') ?>, <?= htmlspecialchars($customer['country_code'] ?? '') ?></p>
            <p><strong>Notie:</strong> <?= htmlspecialchars($customer['note'] ?? '') ?></p>
            <p><strong>Totaal punten:</strong> <?= htmlspecialchars(number_format((float)($customer['total_points'] ?? 0), 2, ',', '.')) ?></p>
            <hr>

            <h4>Bonnen</h4>
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Bonnummer</th>
                        <th>Datum</th>
                        <th>Totaalbedrag</th>
                        <th>Verdiende Punten</th>
                        <th>Huidig Puntensaldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($receipts)) {
                        foreach ($receipts as $receipt) { ?>
                            <tr>
                                <td><?= htmlspecialchars($receipt['receipt_number'] ?? '') ?></td>
                                <td><?= htmlspecialchars(isset($receipt['receipt_date']) ? date('d-m-Y H:i:s', strtotime($receipt['receipt_date'])) : '') ?></td>
                                <td><?= htmlspecialchars(number_format((float)($receipt['total_money'] ?? 0), 2, ',', '.')) ?></td>
                                <td><?= htmlspecialchars(number_format((float)($receipt['points_earned'] ?? 0), 2, ',', '.')) ?></td>
                                <td><?= htmlspecialchars(number_format((float)($receipt['points_balance'] ?? 0), 2, ',', '.')) ?></td>
                            </tr>
                    <?php }
                    } else {
                        echo '<tr><td colspan="5" class="text-center">Geen geschiedenis beschikbaar voor deze klant.</td></tr>';
                    } ?>
                </tbody>
            </table>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1) { ?>
                        <li class="page-item">
                            <a class="page-link" href="?id=<?= urlencode($customer_id) ?>&page=<?= $page - 1 ?>">Vorige</a>
                        </li>
                    <?php } ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?id=<?= urlencode($customer_id) ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php } ?>
                    
                    <?php if ($page < $total_pages) { ?>
                        <li class="page-item">
                            <a class="page-link" href="?id=<?= urlencode($customer_id) ?>&page=<?= $page + 1 ?>">Volgende</a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php
require $_SERVER["DOCUMENT_ROOT"] . '/footer.php'; // Footer includen
?>
