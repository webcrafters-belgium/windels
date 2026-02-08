<?php
// FILE: /pages/shop/shopping-cart/checkout-success.php
declare(strict_types=1);
session_start();
ini_set('display_errors', '1');
error_reporting(E_ALL);

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/PHPMailer/SMTP.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/fpdf/fpdf.php';
require $_SERVER['DOCUMENT_ROOT'] . '/lib/mollie/vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'] . '/functions/mail/customer_mail.php';
require $_SERVER['DOCUMENT_ROOT'] . '/functions/mail/admin_mail.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/pages/orders/_pdf_lib.php';

use Mollie\Api\MollieApiClient;

$isDev = false; // Zet op false in productie
$debugSteps = [];

// ───────────── Helpers ─────────────
function parseHouseAndBox(string $rawNumber): array {
    $raw = trim($rawNumber);
    $nr = ''; $box = '';
    if (preg_match('/^\s*(\d+)\s*(.*)$/u', $raw, $m)) {
        $nr = $m[1];
        $rest = trim($m[2] ?? '');
        if ($rest !== '') {
            $rest = preg_replace('/\s+/u', ' ', strtolower($rest));
            $rest = preg_replace('/^(bus|box|bte|b|apt|appartement|boîte)\s*/u', '', $rest);
            $rest = ltrim($rest, '/- ');
            $box = trim($rest);
        }
    } else {
        $nr = $raw;
    }
    return [$nr, $box];
}

function json_post(string $url, array $payload, int $timeout = 25): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json; charset=utf-8'],
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            CURLOPT_TIMEOUT        => $timeout,
    ]);
    $resp = curl_exec($ch);
    $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errn = curl_errno($ch);
    $err  = curl_error($ch);
    curl_close($ch);
    if ($errn) return ['ok' => false, 'http' => 0, 'error' => 'Curl error: '.$err];
    $data = json_decode((string)$resp, true);
    if ($http < 200 || $http >= 300) return ['ok' => false, 'http' => $http, 'error' => 'HTTP '.$http, 'raw' => $data ?: $resp];
    return ['ok' => true, 'http' => $http, 'data' => $data];
}

function json_get(string $url, int $timeout = 25): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json; charset=utf-8'],
            CURLOPT_TIMEOUT        => $timeout,
    ]);
    $resp = curl_exec($ch);
    $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errn = curl_errno($ch);
    $err  = curl_error($ch);
    curl_close($ch);
    if ($errn) return ['ok' => false, 'http' => 0, 'error' => 'Curl error: '.$err];
    $data = json_decode((string)$resp, true);
    if ($http < 200 || $http >= 300) return ['ok' => false, 'http' => $http, 'error' => 'HTTP '.$http, 'raw' => $data ?: $resp];
    return ['ok' => true, 'http' => $http, 'data' => $data];
}

// ───────────── Order / betaling ophalen ─────────────
$orderId   = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$paymentId = $_SESSION['payment_id'] ?? null;
$totalPriceSession = $_SESSION['last_total_price'] ?? null;

if (!empty($paymentId)) {
    try {
        $mollie = new MollieApiClient();
        $mollie->setApiKey($mollie_key);
        $payment = $mollie->payments->get($paymentId);
        $paid = method_exists($payment, 'isPaid') ? $payment->isPaid() : ($payment->status === 'paid');
        $debugSteps[] = "Mollie status: ".$payment->status;
        if (!$paid) {
            if ($orderId) {
                $stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
                $stmt->bind_param("i", $orderId);
                $stmt->execute(); $stmt->close();
                $debugSteps[] = "Order #{$orderId} geannuleerd.";
            }
            unset($_SESSION['last_order_id'], $_SESSION['last_total_price'], $_SESSION['payment_id']);
            include $_SERVER['DOCUMENT_ROOT'].'/header.php';
            echo '<div class="container py-5 text-center"><div class="fs-1 mb-3 text-danger">❌</div><h1>Betaling geannuleerd</h1></div>';
            include $_SERVER['DOCUMENT_ROOT'].'/footer.php';
            exit;
        }
    } catch (\Throwable $e) {
        $debugSteps[] = "❌ Mollie error: ".$e->getMessage();
    }
}

if ($orderId <= 0) {
    include $_SERVER['DOCUMENT_ROOT'].'/header.php';
    echo '<div class="container py-5 text-center"><h1>Geen order gevonden</h1></div>';
    include $_SERVER['DOCUMENT_ROOT'].'/footer.php';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();
if (!$order) {
    include $_SERVER['DOCUMENT_ROOT'].'/header.php';
    echo '<div class="container py-5 text-center"><h1>Order niet gevonden</h1></div>';
    include $_SERVER['DOCUMENT_ROOT'].'/footer.php';
    exit;
}

if ($order['status'] !== 'paid') {
    $stmt = $conn->prepare("UPDATE orders SET status = 'paid' WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute(); $stmt->close();
    $order['status'] = 'paid';
    $debugSteps[] = "✅ Orderstatus op 'paid' gezet.";
}

if (empty($order['shipping_method'])) {
    $stmt = $conn->prepare("UPDATE orders SET shipping_method = 'DPD' WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute(); $stmt->close();
    $order['shipping_method'] = 'DPD';
}

$totalPrice = $totalPriceSession !== null ? (float)$totalPriceSession : (float)($order['total_price'] ?? 0.0);

// ───────────── Order-items ophalen ─────────────
$stmt = $conn->prepare("SELECT oi.*, p.name AS product_name, COALESCE(p.weight_grams,0) AS weight_grams FROM order_items oi LEFT JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$totalWeightG = 0;
foreach ($items as $it) $totalWeightG += max(1,(int)$it['quantity']) * max(0,(int)$it['weight_grams']);
if ($totalWeightG <= 0) $totalWeightG = 1000;

// ───────────── MyParcel shipment aanmaken ─────────────
$cc = strtoupper($order['country'] ?: 'BE');
[$houseNr, $boxNr] = parseHouseAndBox((string)$order['number']);
$recipient = [
        'cc'          => $cc,
        'postal_code' => $order['zipcode'] ?: '1000',
        'city'        => $order['city'] ?: 'Brussel',
        'street'      => $order['street'] ?: 'Onbekend',
        'number'      => $houseNr ?: '1',
        'box_number'  => $boxNr ?? '',
        'person'      => $order['name'] ?: 'Online klant',
        'email'       => $order['email'] ?: 'no-reply@example.com',
        'phone'       => $order['phone'] ?: null,
];
$carrier = (strcasecmp($order['shipping_method'], 'DPD') === 0) ? 4 : 2;
$payload = [
        'recipient'      => $recipient,
        'weight_g'       => $totalWeightG,
        'carrier'        => $carrier,
        'package_type'   => 1,
        'reference'      => 'ORDER-' . $orderId,
        'options'        => ['package_type'=>1],
        'request_label'=>true
];
$endpoint = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/API/myparcel/create_shipment.php';
$result = json_post($endpoint, $payload, 30);
$debugSteps[] = "📡 MyParcel API response: " . json_encode($result);
$barcode = '';

if ($result['ok'] ?? false) {
    $shipmentId = (int)($result['data']['shipment_id'] ?? 0);
    if ($shipmentId > 0) {
        $stmt = $conn->prepare("UPDATE orders SET shipment_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $shipmentId, $orderId);
        $stmt->execute(); $stmt->close();
        $debugSteps[] = "✅ MyParcel zending aangemaakt met ID {$shipmentId}.";

        // Barcode ophalen met retry
        $infoUrl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/API/myparcel/get_shipment_info.php?id='.$shipmentId;
        for ($i=0; $i<5; $i++) {
            usleep(500000); // 0.5 sec
            $info = json_get($infoUrl, 20);
            if (($info['ok'] ?? false) && !empty($info['data']['data']['shipments'][0]['barcode'])) {
                $barcode = $info['data']['data']['shipments'][0]['barcode'];
                break;
            }
        }
        $debugSteps[] = "✅ Barcode: ".$barcode;
    }
} else {
    $debugSteps[] = "❌ MyParcel aanmaak mislukt";
}

// ───────────── Factuur + pakbon ─────────────
$tempDir = $_SERVER['DOCUMENT_ROOT'] . '/temp';
if (!is_dir($tempDir)) mkdir($tempDir, 0775, true);
$invoicePath = $tempDir . "/factuur_{$orderId}.pdf";
$packingPath = $tempDir . "/pakbon_{$orderId}.pdf";
file_put_contents($invoicePath, buildInvoicePdfString($order, $items));
file_put_contents($packingPath, buildPackingPdfString($order, $items));
$xmlPath = $tempDir . "/factuur_{$orderId}.xml";
file_put_contents($xmlPath, buildInvoiceXmlString($order, $items));
$debugSteps[] = "📄 Factuur en pakbon aangemaakt.";
$debugSteps[] = "🧾 XML-factuur aangemaakt.";

// ───────────── Mails ─────────────
try {
    sendAdminMail($orderId, $totalPrice, (float)$order['shipping_cost'], $invoicePath, $packingPath, $xmlPath);
    $debugSteps[] = "📧 Adminmail verzonden.";
    if (!empty($order['email'])) {
        sendConfirmationEmail($orderId, $totalPrice, (float)$order['shipping_cost'], $order['email'], $invoicePath, $barcode);
        $debugSteps[] = "📧 Klantmail verzonden.";
    }
} catch (\Throwable $e) {
    $debugSteps[] = "❌ Mail error: ".$e->getMessage();
} finally {
    @unlink($invoicePath); @unlink($packingPath);
    @unlink($xmlPath);
}

// ───────────── HTML ─────────────
include $_SERVER['DOCUMENT_ROOT'].'/header.php';
?>
    <div class="container py-5">
        <div class="text-center">
            <div class="fs-1 mb-3">✅</div>
            <h1>Betaling gelukt!</h1>
            <p>Bedankt voor je bestelling <strong>#<?= htmlspecialchars((string)$orderId) ?></strong>.</p>
            <?php if ($totalPrice): ?>
                <p class="lead">Totaalbedrag: &euro;<?= number_format($totalPrice, 2, ',', '.') ?></p>
            <?php endif; ?>
            <?php if ($barcode): ?>
                <p><strong>Track & Trace:</strong> <?= htmlspecialchars($barcode) ?></p>
            <?php endif; ?>
            <?php if ($isDev): ?>
                <div class="alert alert-info text-start mt-4" style="max-width:700px;margin:auto;">
                    <h5>Debug informatie:</h5>
                    <ul class="mb-0">
                        <?php foreach ($debugSteps as $step): ?>
                            <li><?= htmlspecialchars($step) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/footer.php';
unset($_SESSION['last_order_id'], $_SESSION['last_total_price'], $_SESSION['payment_id']);
