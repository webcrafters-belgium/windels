<?php
// admin/customers/coupons/index.php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Alleen admin
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /pages/account/login");
    exit;
}

// Coupon toevoegen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = (int)$_POST['customer_id'];
    if ($customer_id === 0) {
        $customer_id = null; // globale coupon
    }
    $code = trim($_POST['code']);
    $discount = (float)$_POST['discount_percentage'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if ($code && $discount > 0 && $start_date && $end_date) {
        $stmt = $conn->prepare("
            INSERT INTO customer_coupons (customer_id, code, discount_percentage, start_date, end_date)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("isdss", $customer_id, $code, $discount, $start_date, $end_date);
        $stmt->execute();
        $stmt->close();
        $successMsg = "Coupon succesvol toegevoegd.";
    } else {
        $errorMsg = "Vul alle velden correct in.";
    }
}


// Klanten ophalen
$customers = [];
$res = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, email FROM customers ORDER BY first_name ASC");
while ($row = $res->fetch_assoc()) {
    $customers[] = $row;
}

// Bestaande coupons ophalen
$coupons = [];
$res = $conn->query("
    SELECT c.id, CONCAT(cu.first_name, ' ', cu.last_name) AS klantnaam, cu.email, c.code, c.discount_percentage, c.start_date, c.end_date
    FROM customer_coupons c
    JOIN customers cu ON cu.id = c.customer_id
    ORDER BY c.start_date DESC
");
while ($row = $res->fetch_assoc()) {
    $coupons[] = $row;
}
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>

<div class="container py-4">
    <h1 class="mb-4">🎟️ Klantcoupons beheren</h1>

    <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
    <?php elseif (!empty($errorMsg)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-3 mb-4 shadow-sm">
        <h5 class="mb-3">Nieuwe coupon toevoegen</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Klant</label>
                <select name="customer_id" class="form-select" required>
                    <option value="0">-- Iedereen (globale coupon) --</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?= $customer['id'] ?>">
                            <?= htmlspecialchars($customer['full_name']) ?> (<?= htmlspecialchars($customer['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Code</label>
                <input type="text" name="code" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Korting (%)</label>
                <input type="number" name="discount_percentage" class="form-control" min="1" max="100" step="0.01" required>
            </div>
            <div class="col-md-1">
                <label class="form-label">Start</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="col-md-1">
                <label class="form-label">Einde</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Opslaan</button>
            </div>
        </div>
    </form>

    <h5>Bestaande coupons</h5>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Klant</th>
            <th>Email</th>
            <th>Code</th>
            <th>Korting</th>
            <th>Start</th>
            <th>Einde</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($coupons): ?>
            <?php foreach ($coupons as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['klantnaam']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= htmlspecialchars($c['code']) ?></td>
                    <td><?= number_format($c['discount_percentage'], 2) ?>%</td>
                    <td><?= htmlspecialchars($c['start_date']) ?></td>
                    <td><?= htmlspecialchars($c['end_date']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6" class="text-center text-muted">Geen coupons gevonden.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
