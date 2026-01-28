<?php
session_start();
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

// Alleen toegankelijk voor ingelogde uitvaartdiensten
if (!isset($_SESSION['partner_id'])) {
    header("Location: /pages/login.php");
    exit;
}

function decryptField($data, $key) {
    $cipher = "AES-256-CBC";
    $data = base64_decode($data);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}

// Order ID ophalen
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) {
    die("Ongeldig order.");
}

// Haal order en klantgegevens op
$sql = "
    SELECT o.id, o.order_number, o.created_at, status,
           op.klant_naam, op.klant_email, op.klant_telefoon, op.klant_adres, op.klantnummer_partner
    FROM orders o
    LEFT JOIN order_private op ON o.id = op.order_id
    WHERE o.id = ? AND o.funeral_partner_id = ?
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('ii', $order_id, $_SESSION['partner_id']);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Bestelling niet gevonden.");
}

// Decrypt klantgegevens
$order['klant_naam'] = decryptField($order['klant_naam'], $encryption_key);
$order['klant_email'] = decryptField($order['klant_email'], $encryption_key);
$order['klant_telefoon'] = decryptField($order['klant_telefoon'], $encryption_key);
$order['klant_adres'] = decryptField($order['klant_adres'], $encryption_key);

$sql = "SELECT product_id, quantity FROM order_products WHERE order_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $order_id);
$stmt->execute();
$result = $stmt->get_result();
$orderItems = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Haal producten op
$producten = [];
foreach ($orderItems as $item) {
    $id = (int)$item['product_id'];
    $qty = (int)$item['quantity'];
    $gevonden = false;

    foreach (['epoxy_products', 'kaarsen_products', 'inkoop_products'] as $table) {
        $sql = "SELECT title, total_product_price FROM $table WHERE id = ? AND sub_category = 'uitvaart'";
        $stmt = $mysqli_medewerkers->prepare($sql);
        $stmt->bind_param('is', $id, $table);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $producten[] = [
                'name' => $row['title'],
                'price' => $row['total_product_price'],
                'quantity' => $qty
            ];
            $gevonden = true;
            break;
        }
    }
}

include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';
?>
<style>
         body {
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
     }
     .orderview {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 3rem 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    margin: 3rem auto 2rem auto;
}
</style>
<main>
    <div class="orderview">
        <h2>Bestelling <?= htmlspecialchars($order['order_number']) ?></h2>
        <p><strong>Datum:</strong> <?= htmlspecialchars($order['created_at']) ?></p>

        <h3>Klantgegevens</h3>
        <p>
            <strong>Klantnummer (uitvaartdienst):</strong> <?= htmlspecialchars($order['klantnummer_partner'] ?? '-') ?><br>
            <strong>Naam:</strong> <?= htmlspecialchars($order['klant_naam']) ?><br>
            <strong>Email:</strong> <?= htmlspecialchars($order['klant_email']) ?><br>
            <strong>Telefoon:</strong> <?= htmlspecialchars($order['klant_telefoon']) ?><br>
            <strong>Adres:</strong><br><?= nl2br(htmlspecialchars($order['klant_adres'])) ?>
        </p>

        <h3>Bestelde producten</h3>
        <table>
            <thead>
                <tr>
                    <th style="text-align:left;">Product</th>
                    <th>Aantal</th>
                    <th>Prijs (per stuk)</th>
                    <th>Totaal</th>
                </tr>
            </thead>
            <tbody>
                <?php $totaal = 0; foreach ($producten as $p): 
                    $subtotaal = $p['price'] * $p['quantity'];
                    $totaal += $subtotaal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td style="text-align:center;"><?= (int)$p['quantity'] ?></td>
                        <td style="text-align:center;">€<?= number_format($p['price'], 2, ',', '.') ?></td>
                        <td style="text-align:center;">€<?= number_format($subtotaal, 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:right;">Totaal:</th>
                    <th>€<?= number_format($totaal, 2, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
    <h3>Status</h3>
    <?php 
    $status_class = 'status-' . $order['status']; 
    $status_label = ucfirst(str_replace('_', ' ', $order['status']));
    ?>
    <p><strong>Huidige status:</strong>
        <span class="status-label <?= $status_class ?>"><?= htmlspecialchars($status_label) ?></span>
    </p>
    <a href="/pages/account/orders/pdf/pakbon_<?= (int)$order['id'] ?>.pdf" target="_blank" class="btn" download>📄 Pakbon downloaden</a>
                            
    <?php if ($order['status'] === 'in_verwerking'): ?>
        <!-- Knop om te annuleren -->
        <form method="post" action="update_order_status.php" style="display:inline-block;" 
            onsubmit="return confirm('Weet je zeker dat je deze bestelling wilt annuleren?');">
            <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
            <input type="hidden" name="status" value="geannuleerd">
            <button type="submit" class="btn btn-danger">Bestelling annuleren</button>
        </form>

        <!-- Knop om als 'as verzonden' te markeren -->
        <form method="post" action="update_order_status.php" style="display:inline-block;" 
            onsubmit="return confirm('Bevestig dat de as is verzonden.');">
            <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
            <input type="hidden" name="status" value="as_verzonden">
            <button type="submit" class="btn btn-primary">Markeer als 'As verzonden'</button>
        </form>
    <?php endif; ?>


        <p><a href="mijn_bestellingen.php" class="btn">← Terug naar overzicht</a></p>
    </div>
</main>
<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
