<?php

session_start();

$admin_role = 'admin';
$_SESSION['admin_role'] = $admin_role;

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

// Haal bestellingen op per status
$conceptOrders = [];
$sentOrders = [];
$endOrders = [];

if ($stmt = $conn->prepare("SELECT * FROM orders WHERE status = 'Concept'")) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $conceptOrders[] = $row;
    }
    $stmt->close();
}

if ($stmt = $conn->prepare("SELECT * FROM orders WHERE status IN ('Verzonden', 'Verwerken')")) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $sentOrders[] = $row;
    }
    $stmt->close();
}

if ($stmt = $conn->prepare("SELECT * FROM orders WHERE status = 'Afgehandeld'")) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $endOrders[] = $row;
    }
    $stmt->close();
}
?>

<div class="container mt-5">
    <h1 class="mb-4">Bestellingen Overzicht</h1>

    <!-- Navigatieknoppen -->
    <div class="mb-4">
        <?php if($_SESSION['admin_role'] == "Admin" || $_SESSION['admin_role'] == "Filiaalmanager" || $_SESSION['admin_role'] == "Kantoormedewerker"): ?>
            <a href="/admin/pages/winkel/new_order.php" class="btn btn-primary mr-2">Nieuw Order</a>
            <a href="/admin/pages/winkel/concept_orders.php" class="btn btn-secondary mr-2">Concept Orders</a>
        <?php endif; ?>
        <a href="/admin/pages/winkel/sent_orders.php" class="btn btn-secondary mr-2">Verzonden Orders</a>
        <a href="/admin/pages/winkel/index.php" class="btn btn-secondary">Terug naar Winkel</a>
    </div>

    <?php if($_SESSION['admin_role'] == "Admin" || $_SESSION['admin_role'] == "Filiaalmanager" || $_SESSION['admin_role'] == "Kantoormedewerker"): ?>
        <!-- Concept Orders -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h2 class="mb-0">Concept Bestellingen</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Order Code</th>
                        <th>Datum Aangemaakt</th>
                        <th>Acties</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($conceptOrders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_code']) ?></td>
                            <td><?= htmlspecialchars($order['created_at']) ?></td>
                            <td>
                                <a href="/andy/winkel/view_order.php?order_id=<?= $order['id'] ?>" class="btn btn-info btn-sm">Bekijk Order</a>
                                <a href="/andy/winkel/edit_order.php?id=<?= $order['id'] ?>" class="btn btn-warning btn-sm">Bewerk</a>
                                <a href="/andy/winkel/send_to_warehouse.php?id=<?= $order['id'] ?>" class="btn btn-success btn-sm">Verzend naar Magazijn</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Verzonden Orders -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h2 class="mb-0">Verzonden Bestellingen</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Order Code</th>
                    <th>Datum Verzonden</th>
                    <th>Status</th>
                    <th>Acties</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($sentOrders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_code']) ?></td>
                        <td><?= htmlspecialchars($order['updated_at']) ?></td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td>
                            <a href="/admin/pages/winkel/view_order.php?order_id=<?= $order['id'] ?>" class="btn btn-info btn-sm">Bekijk Order</a>
                            <?php if($order['status']=='Concept'): ?>
                                <a href="/admin/pages/winkel/edit_order.php?id=<?= $order['id'] ?>" class="btn btn-success btn-sm">Bewerken</a>
                            <?php endif; ?>
                            <?php if($order['status']=='Verzonden'): ?>
                                <a href="/admin/pages/winkel/update_order_status.php?id=<?= $order['id'] ?>" class="btn btn-success btn-sm">Bewerk order status</a>
                                <a href="/admin/pages/winkel/cancel_order.php?id=<?= $order['id'] ?>" class="btn btn-danger btn-sm">Annuleer</a>
                            <?php endif; ?>
                            <?php if($order['status']=='Verwerken'): ?>
                                <a href="/admin/pages/winkel/update_order_status.php?id=<?= $order['id'] ?>" class="btn btn-success btn-sm">Bewerk order status</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Afgeronde Orders -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h2 class="mb-0">Afgeronde Bestellingen</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Order Code</th>
                    <th>Datum Afgerond</th>
                    <th>Status</th>
                    <th>Acties</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($endOrders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['order_code']) ?></td>
                        <td><?= htmlspecialchars($order['updated_at']) ?></td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td>
                            <a href="/admin/pages/winkel/view_order.php?order_id=<?= $order['id'] ?>" class="btn btn-info btn-sm">Bekijk Order</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
