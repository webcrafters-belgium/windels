<?php
// /admin/tools/onfact/invoice.php
declare(strict_types=1);

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$config = [
        'onfact_api_key'    => 'kajbgdm!zcc#wrhf0p8ej76sb3ymwlwdacp%m#n#1n!vzaundgvgoqwht8imzquu',
        'onfact_company_id' => '738f8835-c129-4e3f-9341-edb4ba3b7a7f',
        'base_url'          => 'https://api5.onfact.be/v1'
];

$id = isset($_GET['id']) ? $_GET['id'] : '';

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
    foreach ([
                     'total_amount_incl',
                     'base_total_amount_incl',
                     'totals.total_incl',
                     'totals.total_amount_incl',
                     'totals.gross_incl',
                     'total_incl',
                     'grand_total',
                     'amount_total',
                     'total',
             ] as $p) {
        $val = moneyValue(pick($inv, [$p]), $currency);
        if ($val !== null) return $val;
    }
    $lines = pick($inv, ['invoice_lines','lines','items']) ?? [];
    if (is_array($lines) && $lines) {
        $sum = 0.0; $found=false;
        foreach ($lines as $ln) {
            $v = moneyValue(pick($ln, ['total_amount_incl','base_total_amount_incl','total_incl']), $currency);
            if ($v !== null) { $sum += $v; $found=true; continue; }
            $qty  = pick($ln, ['quantity','qty']);
            $unit = moneyValue(pick($ln, ['price_incl','unit_price_incl','price','base_price_incl']), $currency);
            if (is_numeric($qty) && $unit !== null) { $sum += ((float)$qty) * $unit; $found=true; continue; }
            $v2 = moneyValue(pick($ln, ['total_price_incl','base_total_amount_incl','price_incl']), $currency);
            if ($v2 !== null) { $sum += $v2; $found=true; }
        }
        if ($found) return $sum;
    }
    return null;
}

$headers = [
        'Accept: application/json',
        'X-SESSION-KEY: ' . $config['onfact_api_key'],
        'X-COMPANY-UUID: ' . $config['onfact_company_id'],
];

$invoice = null;
if ($id !== '') {
    $url = $config['base_url'] . '/invoices/' . rawurlencode((string)$id) . '.json';
    $res = apiGet($url, $headers);
    if ($res['ok']) {
        $payload = $res['json'] ?? [];
        $invoice = $payload['data'] ?? ($payload['item'] ?? $payload);
    }
}
?>
<div class="container my-4">
    <a href="/admin/tools/onfact/" class="btn btn-link">&larr; Terug naar overzicht</a>
    <h1>Factuur</h1>

    <?php if (!$invoice): ?>
        <div class="alert alert-danger">Factuur niet gevonden.</div>
    <?php else: ?>
        <?php
        $nr     = pick($invoice, ['document_number','number_formatted','number','code','reference']) ?? '—';
        $date   = pick($invoice, ['document_date','invoice_date','date','created']) ?? '—';
        $due    = pick($invoice, ['due_date','expiration_date','expiry_date','payment_due_date']) ?? '—';
        $status = pick($invoice, ['state','status']) ?? '—';
        $totalV = extractTotalIncl($invoice);
        $total  = moneyFormat($totalV);
        $docUrl = pick($invoice, ['document_url','pdf_url','download_url']);
        $customer = $invoice['customer'] ?? ($invoice['contact'] ?? null);
        ?>
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Factuurgegevens</h5>
                        <dl class="row mb-0">
                            <dt class="col-5">Factuurnummer</dt><dd class="col-7"><?= htmlspecialchars($nr) ?></dd>
                            <dt class="col-5">Datum</dt><dd class="col-7"><?= htmlspecialchars($date) ?></dd>
                            <dt class="col-5">Vervaldatum</dt><dd class="col-7"><?= htmlspecialchars($due) ?></dd>
                            <dt class="col-5">Status</dt><dd class="col-7"><?= htmlspecialchars($status) ?></dd>
                            <dt class="col-5">Totaal</dt><dd class="col-7">€ <?= $total ?></dd>
                        </dl>
                        <?php if ($docUrl): ?>
                            <a href="<?= htmlspecialchars($docUrl) ?>" target="_blank" class="btn btn-primary mt-3">Open document</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <?php if (is_array($customer)): ?>
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Klant</h5>
                            <div><strong><?= htmlspecialchars($customer['name'] ?? '') ?></strong></div>
                            <div class="text-muted">
                                <?= htmlspecialchars($customer['email'] ?? '') ?>
                                <?php if (!empty($customer['vat'])): ?>
                                    · BTW: <?= htmlspecialchars($customer['vat']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
