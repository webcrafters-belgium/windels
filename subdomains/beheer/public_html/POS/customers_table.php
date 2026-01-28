<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/ini.inc';
//require_once $_SERVER["DOCUMENT_ROOT"] . '/authenticatelogg.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/POS/LoyverseClient.php';

// Initialiseer LoyverseClient
$api_token = '56f733c909bf417f98a0ff88b1f3a983'; // Jouw API-token
$loyverse = new LoyverseClient($api_token);

// Haal de zoekterm op (indien aanwezig)
$search_query = $_GET['query'] ?? '';

// Stel paginering in
$items_per_page = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

try {
    // Haal klanten op via de Loyverse API
    $customers_data = $loyverse->getCustomers();

    // Verwerk de API-respons als er meerdere pagina's klanten zijn
    $customers = $customers_data['customers'] ?? [];
    while (!empty($customers_data['cursor'])) {
        $customers_data = $loyverse->getCustomers($customers_data['cursor']);
        $customers = array_merge($customers, $customers_data['customers']);
    }

     // Totaal aantal klanten berekenen
     $total_customers = count($customers);
     $total_pages = ceil($total_customers / $items_per_page);
 
     // Beperk klanten tot de huidige pagina
     $customers = array_slice($customers, $offset, $items_per_page);

    // Filter resultaten op zoekterm als er een query is
    if (!empty($search_query)) {
        $customers = array_filter($customers, function ($customer) use ($search_query) {
            $name = $customer['name'] ?? '';
            $email = $customer['email'] ?? '';
            $phone_number = $customer['phone_number'] ?? '';

            return stripos($name, $search_query) !== false ||
                   stripos($email, $search_query) !== false ||
                   stripos($phone_number, $search_query) !== false;
        });
    }
    ?>
<table class="table table-bordered table-hover scroll">
    <thead class="thead-green">
        <tr><th colspan="3">Knoppen</th>
            <th>ID</th>
            <th>Naam</th>
            <th>E-mail</th>
            <th>Telefoonnummer</th>
            <th>Notitie</th>
            <th>Klantenkaartnummer</th>
            <th>Totaal Punten</th>
            <th>Aangemaakt</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($customers)) {
            foreach ($customers as $customer) { ?>
        <tr>
            <td>
                <a href="customer_history.php?id=<?= htmlspecialchars($customer['id'] ?? '') ?>" class="btn btn-primary">
                    <i class="fa fa-search"></i>
                </a>
            </td>
            <td>
                <!-- Bewerk knop -->
                <button class="btn btn-warning" data-toggle="modal" data-target="#editCustomerModal" 
                        data-id="<?= htmlspecialchars($customer['id'] ?? '') ?>" 
                        data-name="<?= htmlspecialchars($customer['name'] ?? '') ?>" 
                        data-email="<?= htmlspecialchars($customer['email'] ?? '') ?>" 
                        data-phone="<?= htmlspecialchars($customer['phone_number'] ?? '') ?>" 
                        data-note="<?= htmlspecialchars($customer['note'] ?? '') ?>" 
                        data-points="<?= htmlspecialchars(number_format((float)($customer['total_points'] ?? 0), 2, ',', '.')) ?>"
                        data-customer-code="<?= htmlspecialchars($customer['customer_code'] ?? '') ?>">
                    <i class="fa fa-edit"></i>
                </button>
            </td>
            <td>
                <!-- Verwijder knop -->
                <form method="POST" action="delete_customer.php" onsubmit="return confirm('Weet je zeker dat je deze klant wilt verwijderen?');">
                    <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer['id'] ?? '') ?>">
                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                </form>
            </td>
            <td><?= htmlspecialchars($customer['id'] ?? 'Onbekend') ?></td>
            <td><?= htmlspecialchars($customer['name'] ?? 'Onbekend') ?></td>
            <td><?= htmlspecialchars($customer['email'] ?? 'Onbekend') ?></td>
            <td><?= htmlspecialchars($customer['phone_number'] ?? 'Onbekend') ?></td>
            <td><?= htmlspecialchars($customer['note'] ?? '') ?></td>
            <td><?= htmlspecialchars($customer['customer_code'] ?? '') ?></td>
            <td><?= htmlspecialchars(number_format((float)($customer['total_points'] ?? 0), 2, ',', '.')) ?></td>
            <td><?= htmlspecialchars(isset($customer['created_at']) ? date('d-m-Y H:i:s', strtotime($customer['created_at'])) : 'Onbekend') ?></td>
        </tr>
        <?php }
        } else {
            echo '<tr><td colspan="10" class="text-center">Geen klanten gevonden.</td></tr>';
        } ?>
    </tbody>
</table>
    <!-- Paginering -->
<nav aria-label="Klantenpaginering">
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Vorige">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <?php if ($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Volgende">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php
} catch (Exception $e) {
    echo '<div class="alert alert-danger">Fout bij het ophalen van klanten: ' . $e->getMessage() . '</div>';
}
?>
