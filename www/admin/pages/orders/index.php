<?php
include($_SERVER['DOCUMENT_ROOT'] . '/ini.inc');
include($_SERVER['DOCUMENT_ROOT'] . '/admin/header.php');

// Filters ophalen
$search    = $_GET['search'] ?? '';
$status    = $_GET['status'] ?? '';
$payment   = $_GET['payment'] ?? '';
$startDate = $_GET['start'] ?? '';
$endDate   = $_GET['end'] ?? '';

// QUERY
$q = "SELECT * FROM orders WHERE 1";
$p = [];

if ($search !== '') {
    $q .= " AND (name LIKE ? OR email LIKE ? OR id = ?)";
    $term = "%$search%";
    $p[] = $term; $p[] = $term; $p[] = $search;
}
if ($status !== '') {
    $q .= " AND status = ?";
    $p[] = $status;
}
if ($payment !== '') {
    $q .= " AND payment_method = ?";
    $p[] = $payment;
}
if ($startDate !== '') {
    $q .= " AND created_at >= ?";
    $p[] = $startDate . " 00:00:00";
}
if ($endDate !== '') {
    $q .= " AND created_at <= ?";
    $p[] = $endDate . " 23:59:59";
}

$q .= " ORDER BY created_at DESC LIMIT 200";
$stmt = $conn->prepare($q);
$stmt->execute($p);
$result = $stmt->get_result();
?>

<div class="flex min-h-screen bg-[#0d0d0d] text-gray-200">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#141414] border-r border-gray-800 p-6 hidden md:block">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/partials/sidebar.php'; ?>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-10 space-y-10">

        <!-- HEADER -->
        <div class="bg-[#141414] border border-gray-800 rounded-2xl p-8 shadow-xl flex justify-between">
            <div>
                <h1 class="text-3xl font-bold">Bestellingen</h1>
                <p class="text-gray-400 text-sm mt-1">Overzicht van recente orders</p>
            </div>
        </div>

        <!-- FILTERS -->
        <div class="bg-[#141414] border border-gray-800 rounded-xl p-8 shadow space-y-6">
            <form method="get" class="grid grid-cols-1 md:grid-cols-6 gap-6">

                <!-- Zoekbalk -->
                <div class="flex flex-col col-span-2">
                    <label class="text-sm text-gray-400 mb-1">Zoek klant / e-mail / ID</label>
                    <input type="text" name="search"
                           value="<?= htmlspecialchars($search) ?>"
                           placeholder="Bijv: Jan, 123, email"
                           class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2">
                </div>

                <!-- Status -->
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Status</label>
                    <select name="status"
                            class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2">
                        <option value="">Alle</option>
                        <option value="in behandeling" <?= $status=='in behandeling'?'selected':'' ?>>In behandeling</option>
                        <option value="betaald" <?= $status=='betaald'?'selected':'' ?>>Betaald</option>
                        <option value="verzonden" <?= $status=='verzonden'?'selected':'' ?>>Verzonden</option>
                        <option value="geannuleerd" <?= $status=='geannuleerd'?'selected':'' ?>>Geannuleerd</option>
                    </select>
                </div>

                <!-- Betaalmethode -->
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Betaalwijze</label>
                    <select name="payment"
                            class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2">
                        <option value="">Alle</option>
                        <option value="bancontact" <?= $payment=='bancontact'?'selected':'' ?>>Bancontact</option>
                        <option value="creditcard" <?= $payment=='creditcard'?'selected':'' ?>>Creditcard</option>
                        <option value="mollie" <?= $payment=='mollie'?'selected':'' ?>>Mollie</option>
                    </select>
                </div>

                <!-- Startdatum -->
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Vanaf</label>
                    <input type="date" name="start"
                           value="<?= htmlspecialchars($startDate) ?>"
                           class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2">
                </div>

                <!-- Einddatum -->
                <div class="flex flex-col">
                    <label class="text-sm text-gray-400 mb-1">Tot</label>
                    <input type="date" name="end"
                           value="<?= htmlspecialchars($endDate) ?>"
                           class="bg-[#0f0f0f] border border-gray-800 rounded-lg px-3 py-2">
                </div>
            </form>
        </div>

        <!-- TABEL -->
        <div class="bg-[#141414] border border-gray-800 rounded-2xl p-8 shadow-xl">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Resultaten</h2>
                <span class="text-gray-400 bg-[#0f0f0f] px-3 py-1 rounded-md border border-gray-700">
                    <?= $result->num_rows ?> bestellingen
                </span>
            </div>

            <?php if ($result->num_rows > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="text-gray-400 text-sm border-b border-gray-800">
                            <th class="py-3"><input type="checkbox" id="select-all"></th>
                            <th>ID</th>
                            <th>Klant</th>
                            <th>E-mail</th>
                            <th>Datum</th>
                            <th>Status</th>
                            <th>Betaalwijze</th>
                            <th>Totaal</th>
                            <th>Notities</th>
                            <th>Acties</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-800">

                        <?php while ($order = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-[#1a1a1a] transition">

                                <td class="py-3">
                                    <input type="checkbox" class="order-checkbox"
                                           value="<?= $order['id'] ?>">
                                </td>

                                <td>#<?= $order['id'] ?></td>

                                <td class="text-gray-300">
                                    <?= htmlspecialchars($order['name']) ?>
                                </td>

                                <td class="text-gray-400">
                                    <?= htmlspecialchars($order['email']) ?>
                                </td>

                                <td class="text-gray-400">
                                    <?= date('d-m-Y H:i', strtotime($order['created_at'])) ?>
                                </td>

                                <td class="text-gray-200">
                                    <?= ucfirst($order['status']) ?>
                                </td>

                                <td class="text-gray-300">
                                    <?= htmlspecialchars($order['payment_method'] ?: '-') ?>
                                </td>

                                <td class="text-green-400">
                                    €<?= number_format($order['total_price'], 2, ',', '.') ?>
                                </td>

                                <td class="text-gray-400 max-w-xs">
                                    <?= nl2br(htmlspecialchars($order['admin_notes'] ?: '-')) ?>
                                </td>

                                <td class="flex gap-2 py-3">

                                    <a href="detail.php?id=<?= $order['id'] ?>"
                                       class="text-blue-400 hover:text-blue-300 text-xl">🔍</a>

                                    <a href="/API/orders/resend_confirmation/resend_confirmation.php?order_id=<?= $order['id'] ?>"
                                       class="text-indigo-400 hover:text-indigo-300 text-xl">📤</a>

                                    <a href="/API/orders/download_invoice.php?order_id=<?= $order['id'] ?>"
                                       class="text-gray-300 hover:text-gray-200 text-xl">🧾</a>

                                    <a href="/API/orders/packing_slip.php?order_id=<?= $order['id'] ?>"
                                       class="text-green-400 hover:text-green-300 text-xl">📦</a>

                                    <a href="edit.php?id=<?= $order['id'] ?>"
                                       class="text-yellow-400 hover:text-yellow-300 text-xl">✏️</a>

                                    <button onclick="deleteOrder(<?= $order['id'] ?>)"
                                            class="text-red-500 hover:text-red-400 text-xl">🗑️</button>

                                </td>

                            </tr>
                        <?php endwhile; ?>

                        </tbody>
                    </table>
                </div>

            <?php else: ?>
                <div class="text-gray-400 py-6 text-center">
                    Geen bestellingen gevonden.
                </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<script>
    function deleteOrder(id) {
        if (!confirm("Weet je zeker dat je bestelling #" + id + " wilt verwijderen?")) return;

        fetch('/API/orders/delete.php?id=' + id)
            .then(r => r.json())
            .then(data => {
                alert(data.message);
                if (data.success) location.reload();
            });
    }

    document.getElementById('select-all')?.addEventListener('click', function () {
        document.querySelectorAll('.order-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });
</script>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'); ?>
