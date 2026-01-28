<?php
// /admin/tools/onfact/view.php
declare(strict_types=1);

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$config = [
    'onfact_api_key'    => 'kajbgdm!zcc#wrhf0p8ej76sb3ymwlwdacp%m#n#1n!vzaundgvgoqwht8imzquu',
    'onfact_company_id' => '738f8835-c129-4e3f-9341-edb4ba3b7a7f',
    'base_url'          => 'https://api5.onfact.be/v1'
];

$id = isset($_GET['id']) ? trim((string)$_GET['id']) : '';

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

$headers = [
    'Accept: application/json',
    'X-SESSION-KEY: ' . $config['onfact_api_key'],
    'X-COMPANY-UUID: ' . $config['onfact_company_id'],
];

$docUrl = null;
$nr     = 'Factuur';

if ($id !== '') {
    $detailUrl = rtrim($config['base_url'], '/') . '/invoices/' . rawurlencode($id) . '.json';
    $res = apiGet($detailUrl, $headers);
    if ($res['ok']) {
        $payload = $res['json'] ?? [];
        $inv = $payload['data'] ?? ($payload['item'] ?? $payload);
        $docUrl = pick($inv, ['document_url','pdf_url','download_url']);
        $nr     = pick($inv, ['document_number','number_formatted','number','code','reference']) ?? $nr;
    }
}
?>
<div class="container my-4">
    <a href="/admin/tools/onfact/" class="btn btn-link">&larr; Terug naar overzicht</a>
    <h1><?= htmlspecialchars($nr) ?></h1>

    <?php if (!$docUrl): ?>
        <div class="alert alert-warning">
            Geen documentlink beschikbaar voor deze factuur.
        </div>
    <?php else: ?>
        <div class="mb-3 d-flex gap-2">
            <a class="btn btn-primary" href="<?= htmlspecialchars($docUrl) ?>" target="_blank" rel="noopener">
                Open in nieuw tabblad
            </a>
            <a class="btn btn-success" href="<?= htmlspecialchars($docUrl) ?>" target="_blank" rel="noopener">
                Download
            </a>
        </div>
        <div style="height:75vh;border:1px solid #e5e5e5;border-radius:12px;overflow:hidden">
            <iframe
                src="<?= htmlspecialchars($docUrl) ?>"
                title="Factuur document"
                width="100%"
                height="100%"
                style="border:0"
                allow="fullscreen"
            ></iframe>
        </div>
    <?php endif; ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
