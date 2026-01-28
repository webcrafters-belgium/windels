<?php
// /admin/tools/onfact/index.php
declare(strict_types=1);

session_start();
if (!isset($_SESSION['partner_id'])) {
    header("Location: login.php");
    exit;
}

include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
include dirname($_SERVER['DOCUMENT_ROOT']). '/partials/header.php';

$config = [
    'onfact_api_key'    => 'kajbgdm!zcc#wrhf0p8ej76sb3ymwlwdacp%m#n#1n!vzaundgvgoqwht8imzquu',
    'onfact_company_id' => '738f8835-c129-4e3f-9341-edb4ba3b7a7f',
    'base_url'          => 'https://api5.onfact.be/v1'
];

$email = isset($_GET['email']) ? trim($_GET['email']) : '';

function apiGet(string $url, array $headers): array {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 25,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    $response = curl_exec($ch);
    $curlErr  = curl_error($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($curlErr || $response === false) return ['ok'=>false,'error'=>$curlErr,'http'=>$httpCode];
    if ($httpCode >= 400) return ['ok'=>false,'error'=>'HTTP '.$httpCode,'http'=>$httpCode,'raw'=>$response];
    return ['ok'=>true,'json'=>json_decode($response,true),'http'=>$httpCode];
}
function pick(array $arr, array $paths) {
    foreach ($paths as $path) {
        $cur = $arr; $ok = true;
        foreach (explode('.', $path) as $seg) {
            if (is_array($cur) && array_key_exists($seg, $cur)) { $cur = $cur[$seg]; }
            else { $ok = false; break; }
        }
        if ($ok && $cur !== null && $cur !== '') return $cur;
    }
    return null;
}

function moneyValue($v, ?string $currency = null): ?float {
    if ($v === null) return null;
    if (is_numeric($v)) return (float)$v;
    if (is_array($v)) {
        if (isset($v['_']) && is_numeric($v['_'])) return (float)$v['_'];
        if ($currency && isset($v[$currency]) && is_numeric($v[$currency])) return (float)$v[$currency];
        foreach ($v as $val) if (is_numeric($val)) return (float)$val;
    }
    return null;
}
function moneyFormat(?float $num): string {
    if ($num === null) return '—';
    if ($num > 99999 && fmod($num, 1.0) === 0.0) $num = $num / 100.0;
    return number_format($num, 2, ',', '.');
}

function extractTotalIncl(array $inv): ?float {
    $currency = (string) (pick($inv, ['currency_id']) ?? 'EUR');
    $candidates = [
        'total_amount_incl',
        'base_total_amount_incl',
        'totals.total_incl',
        'totals.total_amount_incl',
        'totals.gross_incl',
        'total_incl',
        'grand_total',
        'amount_total',
        'total',
    ];
    foreach ($candidates as $path) {
        $raw = pick($inv, [$path]);
        $val = moneyValue($raw, $currency);
        if ($val !== null) return $val;
    }
    $lines = pick($inv, ['invoice_lines','lines','items']) ?? [];
    if (is_array($lines) && $lines) {
        $sum = 0.0; $found = false;
        foreach ($lines as $ln) {
            $v = moneyValue(pick($ln, ['total_amount_incl','base_total_amount_incl','total_incl']), $currency);
            if ($v !== null) { $sum += $v; $found = true; continue; }
            $qty  = pick($ln, ['quantity','qty']);
            $unit = moneyValue(pick($ln, ['price_incl','unit_price_incl','price','base_price_incl']), $currency);
            if (is_numeric($qty) && $unit !== null) { $sum += ((float)$qty) * $unit; $found = true; continue; }
            $v2 = moneyValue(pick($ln, ['total_price_incl','base_total_amount_incl','price_incl']), $currency);
            if ($v2 !== null) { $sum += $v2; $found = true; }
        }
        if ($found) return $sum;
    }
    return null;
}

function fetchInvoiceTotal(string $id, array $headers, string $baseUrl): ?float {
    $url = rtrim($baseUrl, '/') . '/invoices/' . rawurlencode($id) . '.json';
    $res = apiGet($url, $headers);
    if (!$res['ok']) return null;
    $payload = $res['json'] ?? [];
    $inv = $payload['data'] ?? ($payload['item'] ?? $payload);
    return extractTotalIncl(is_array($inv) ? $inv : []);
}

function printInvoicesTable(array $invoices): void {
    if (empty($invoices)) {
        echo "<div class='alert alert-warning'>Geen facturen gevonden voor deze klant.</div>";
        return;
    }
    echo "<div class='table-responsive'>";
    echo "<table class='table table-striped align-middle'>";
    echo "<thead><tr>";
    echo "<th>#</th><th>Datum</th><th>Vervaldatum</th><th>Status</th><th class='text-end'>Totaal</th><th></th>";
    echo "</tr></thead><tbody>";
    foreach ($invoices as $inv) {
        $nr    = pick($inv, ['document_number','number_formatted','number','code','reference']) ?? '—';
        $date  = pick($inv, ['document_date','invoice_date','date','created']) ?? '—';
        $due   = pick($inv, ['due_date','expiration_date','expiry_date','payment_due_date']) ?? '—';
        $status= pick($inv, ['state','status']) ?? '—';
        $total = moneyFormat($inv['__total'] ?? null);
        $id = pick($inv, ['id','invoice_id','eid','uuid']);
        $idParam = $id !== null ? urlencode((string)$id) : '';

        echo "<tr>";
        echo "<td>".htmlspecialchars((string)$nr)."</td>";
        echo "<td>".htmlspecialchars((string)$date)."</td>";
        echo "<td>".htmlspecialchars((string)$due)."</td>";
        echo "<td>".htmlspecialchars((string)$status)."</td>";
        echo "<td class='text-end'>€ {$total}</td>";
        echo "<td class='text-end'>".($idParam ? "<a class='btn btn-sm btn-outline-primary' href='/admin/tools/onfact/invoice.php?id={$idParam}'>Details</a>" : '')."</td>";
        echo "</tr>";
    }
    echo "</tbody></table></div>";
}

$headers = [
    'Accept: application/json',
    'X-SESSION-KEY: ' . $config['onfact_api_key'],
    'X-COMPANY-UUID: ' . $config['onfact_company_id'],
];

$contact = null;
$invoices = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $email !== '') {
    $emailNorm = strtolower($email);
    $q = 'email:"' . $emailNorm . '"';
    $contactsUrl = $config['base_url'] . '/contacts.json?q=' . urlencode($q) . '&per_page=100';
    $cRes = apiGet($contactsUrl, $headers);
    if ($cRes['ok']) {
        $payload  = $cRes['json'] ?? [];
        $contacts = $payload['data'] ?? ($payload['items'] ?? []);
        if (is_array($contacts)) {
            foreach ($contacts as $c) {
                if (strtolower($c['email'] ?? '') === $emailNorm) { $contact = $c; break; }
            }
            if (!$contact && !empty($contacts)) $contact = $contacts[0];
        }
    }
    if ($contact && !empty($contact['id'])) {
        $contactId    = (string)$contact['id'];
        $contactEid   = (string)($contact['eid'] ?? '');
        $contactEmail = strtolower($contact['email'] ?? '');
        $invUrl = $config['base_url'] . '/invoices.json?contact_id=' . rawurlencode($contactId) . '&per_page=100';
        $iRes = apiGet($invUrl, $headers);
        $all = [];
        if ($iRes['ok']) {
            $invPayload = $iRes['json'] ?? [];
            $all        = $invPayload['data'] ?? ($invPayload['items'] ?? []);
        }
        $filtered = array_values(array_filter($all, function($inv) use ($contactId, $contactEid, $contactEmail) {
            $invContactId  = (string)(pick($inv, ['contact_id','customer_id','contact.id','customer.id']) ?? '');
            $invContactEid = (string)(pick($inv, ['contact_eid','customer_eid','contact.eid','customer.eid']) ?? '');
            $invEmail      = strtolower((string)(pick($inv, ['customer.email','contact.email','email']) ?? ''));
            if ($invContactId !== '' && $invContactId === $contactId) return true;
            if ($invContactEid !== '' && $invContactEid === $contactEid) return true;
            if ($invEmail !== '' && $contactEmail !== '' && $invEmail === $contactEmail) return true;
            return false;
        }));
        foreach ($filtered as &$inv) {
            $id = pick($inv, ['id','invoice_id','eid','uuid']);
            if ($id !== null) {
                $total = fetchInvoiceTotal((string)$id, $headers, $config['base_url']);
                $inv['__total'] = $total;
            }
        }
        unset($inv);
        $invoices = $filtered;
    }
}
?>
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
</style>

<main class="dashboard-page container">
    <h1>📄 Facturatie</h1>
    <p>Welkom in het facturatiegedeelte van je account. Hier kan je eenvoudig facturen bekijken en opvolgen zoals opgemaakt door onze administratie.</p>

    <form method="get" class="mb-4">
        <label for="email" class="form-label">Zoek op klant-e-mailadres</label>
        <input id="email" type="email" name="email" class="form-control" placeholder="klant@example.com" value="<?= htmlspecialchars($email) ?>" required>
        <button type="submit" class="btn btn-success mt-2">Zoeken</button>
    </form>

    <?php if ($email && !$contact): ?>
        <div class="alert alert-warning">Geen klant/contact gevonden voor <strong><?= htmlspecialchars($email) ?></strong>.</div>
    <?php endif; ?>

    <?php if ($contact): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-1"><?= htmlspecialchars($contact['name'] ?? 'Onbekende klant') ?></h5>
                <div class="text-muted">
                    <?= htmlspecialchars($contact['email'] ?? '') ?>
                    <?php if (!empty($contact['vat'])): ?>
                        · BTW: <?= htmlspecialchars($contact['vat']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php printInvoicesTable(is_array($invoices) ? $invoices : []); ?>
    <?php endif; ?>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
