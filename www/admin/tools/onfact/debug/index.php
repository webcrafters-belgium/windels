<?php
// /admin/tools/onfact/index.php
declare(strict_types=1);

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';

$config = [
    'onfact_api_key'    => 'kajbgdm!zcc#wrhf0p8ej76sb3ymwlwdacp%m#n#1n!vzaundgvgoqwht8imzquu',
    'onfact_company_id' => '738f8835-c129-4e3f-9341-edb4ba3b7a7f',
    'base_url'          => 'https://api5.onfact.be/v1'
];

$email = isset($_GET['email']) ? trim($_GET['email']) : '';
$debug = isset($_GET['debug']) && $_GET['debug'] == '1';

function apiRequest(string $url, array $headers): array {
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

    return [
        'ok'        => ($curlErr === '' && $response !== false),
        'httpCode'  => $httpCode,
        'curlError' => $curlErr,
        'raw'       => $response,
        'json'      => $response ? json_decode($response, true) : null,
        'url'       => $url,
    ];
}

function dd(bool $debug, string $title, array $res): void {
    if (!$debug) return;
    $snippet = is_string($res['raw']) ? substr($res['raw'], 0, 1200) : '';
    echo "<details class='mb-3' open><summary><strong>DEBUG:</strong> ".htmlspecialchars($title)."</summary>";
    echo "<pre style='white-space:pre-wrap;background:#111;color:#eee;padding:12px;border-radius:8px;'>";
    echo htmlspecialchars("URL: {$res['url']}\nHTTP: {$res['httpCode']}\nCURL: {$res['curlError']}\nRAW (first 1200):\n{$snippet}");
    echo "</pre></details>";
}

function printInvoices(array $invoices, string $who): void {
    if (empty($invoices)) {
        echo "<div class='alert alert-warning'>Geen facturen gevonden voor <strong>".htmlspecialchars($who)."</strong></div>";
        return;
    }
    echo "<h3>📄 Facturen voor ".htmlspecialchars($who).":</h3>";
    echo "<div class='table-responsive'><table class='table table-striped align-middle'>";
    echo "<thead><tr><th>Factuurnr</th><th>Datum</th><th>Bedrag</th></tr></thead><tbody>";
    foreach ($invoices as $inv) {
        $nr    = htmlspecialchars($inv['document_number'] ?? '—');
        $date  = htmlspecialchars($inv['document_date'] ?? '—');
        $total = $inv['total']['value'] ?? ($inv['total']['amount'] ?? null);
        $total = is_numeric($total) ? number_format((float)$total, 2, ',', '.') : '—';
        echo "<tr><td>{$nr}</td><td>{$date}</td><td>€{$total}</td></tr>";
    }
    echo "</tbody></table></div>";
}

$headers = [
    'Accept: application/json',
    'X-SESSION-KEY: ' . $config['onfact_api_key'],
    'X-COMPANY-UUID: ' . $config['onfact_company_id'],
];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $email) {
    $emailNorm = strtolower($email);

    // 1) Zoek de klant in /contacts.json (hier zit 'klant' bij Onfact)
    $q = 'email:"' . $emailNorm . '"';
    $contactsUrl = $config['base_url'] . '/contacts.json?q=' . urlencode($q) . '&per_page=100';
    $cRes = apiRequest($contactsUrl, $headers);
    dd($debug, "Contacts query ($q)", $cRes);

    if (!$cRes['ok'] || $cRes['httpCode'] >= 400) {
        echo "<div class='alert alert-danger'>Fout bij contact-zoekopdracht.</div>";
    } else {
        $payload  = $cRes['json'] ?? [];
        $contacts = $payload['data'] ?? ($payload['items'] ?? []);
        // beste match: exact e‑mail
        $contact  = null;
        if (is_array($contacts)) {
            foreach ($contacts as $c) {
                if (strtolower($c['email'] ?? '') === $emailNorm) { $contact = $c; break; }
            }
            if (!$contact && !empty($contacts)) $contact = $contacts[0];
        }

        if (!$contact) {
            echo "<div class='alert alert-warning'>Geen klant gevonden met dit e-mailadres.</div>";
        } else {
            $contactId       = $contact['id'] ?? null;                  // integer
            $contactEid      = $contact['eid'] ?? null;                 // ONFACT_UUID
            $contactNumber   = $contact['number_formatted'] ?? ($contact['number'] ?? null);
            $who             = $contact['name'] ?? $emailNorm;

            // 2) Probeer meerdere zekere routes om facturen op te halen
            $tries = [];

            if ($contactId) {
                $tries[] = [ "label" => "contacts/{id}/invoices.json",
                    "url"   => $config['base_url'] . '/contacts/' . rawurlencode((string)$contactId) . '/invoices.json?per_page=100' ];
                $tries[] = [ "label" => "invoices?contact_id",
                    "url"   => $config['base_url'] . '/invoices.json?contact_id=' . rawurlencode((string)$contactId) . '&per_page=100' ];
                $tries[] = [ "label" => "invoices?customer_id",
                    "url"   => $config['base_url'] . '/invoices.json?customer_id=' . rawurlencode((string)$contactId) . '&per_page=100' ];
            }
            if ($contactEid) {
                $tries[] = [ "label" => "invoices?contact_eid",
                    "url"   => $config['base_url'] . '/invoices.json?contact_eid=' . rawurlencode($contactEid) . '&per_page=100' ];
                $tries[] = [ "label" => "invoices?customer_eid",
                    "url"   => $config['base_url'] . '/invoices.json?customer_eid=' . rawurlencode($contactEid) . '&per_page=100' ];
            }
            if ($contactNumber) {
                $tries[] = [ "label" => "contacts/{number}/invoices.json (best effort)",
                    "url"   => $config['base_url'] . '/contacts/' . rawurlencode((string)$contactNumber) . '/invoices.json?per_page=100' ];
            }

            $invoices = [];
            foreach ($tries as $t) {
                $r = apiRequest($t['url'], $headers);
                dd($debug, "Try ".$t['label'], $r);
                if (!$r['ok'] || $r['httpCode'] >= 400) continue;

                $pay = $r['json'] ?? [];
                $data = $pay['data'] ?? ($pay['items'] ?? []);
                if (is_array($data) && !empty($data)) { $invoices = $data; break; }
            }

            printInvoices($invoices, $who . " ({$emailNorm})");
        }
    }
}
?>

<div class="container my-4">
    <h1>🔎 Facturatie Test (Onfact)</h1>
    <form method="get" class="mb-4">
        <label for="email" class="form-label">Zoek op klant-e-mailadres:</label>
        <input id="email" type="email" name="email" class="form-control" placeholder="klant@example.com" value="<?= htmlspecialchars($email) ?>" required>
        <div class="d-flex gap-3 mt-2 align-items-center">
            <button type="submit" class="btn btn-success">Zoeken</button>
            <label class="form-check ms-2">
                <input type="checkbox" name="debug" value="1" <?= $debug ? 'checked' : '' ?>> Debug tonen
            </label>
        </div>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
