<?php
// admin/tools/onfact/onfact_client.php
declare(strict_types=1);

// Vereist: ini.inc levert mysqli-verbinding(en) én Onfact config.
// - $mysqli_uitvaart of $mysqli_medewerkers (gebruik welke je wil)
// - $config['onfact_api_key'], $config['onfact_company_id'], $config['base_url']
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';

if (!isset($config['base_url'])) {
    $config['base_url'] = 'https://api5.onfact.be/v1';
}

function onfactHeaders(array $config): array {
    return [
        'Accept: application/json',
        'Content-Type: application/json',
        // Onfact gebruikt API key + company id headers (conform eerdere codebasis bij jou)
        'X-API-KEY: ' . $config['onfact_api_key'],
        'X-COMPANY-ID: ' . $config['onfact_company_id'],
    ];
}

function onfactGet(string $endpoint, array $query, array $headers): array {
    $url = rtrim($GLOBALS['config']['base_url'], '/') . '/' . ltrim($endpoint, '/');
    if ($query) {
        $url .= '?' . http_build_query($query);
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    $raw = curl_exec($ch);
    $http = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($raw === false || $http >= 400) {
        throw new RuntimeException("Onfact GET $endpoint failed (HTTP $http): $err / $raw");
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        throw new RuntimeException("Onfact response decode failed for $endpoint: $raw");
    }
    return $data;
}

/**
 * Haal alle customers paginated op.
 * Retourneert een generator die per batch een array van klanten yield.
 */
function onfactCustomersAll(int $pageSize = 100): Generator {
    $headers = onfactHeaders($GLOBALS['config']);
    $page    = 1;

    while (true) {
        // Endpoints kunnen /customers of /contacts/customers zijn; bij jou is dit "customers".
        $payload = onfactGet('/customers', [
            'page'  => $page,
            'limit' => $pageSize,
            // Voeg filters toe indien gewenst, bv. 'is_supplier' => 0
        ], $headers);

        // Defensive mapping: zoek naar 'data' of direct array
        $items = [];
        if (isset($payload['data']) && is_array($payload['data'])) {
            $items = $payload['data'];
        } elseif (isset($payload[0]) || $payload === []) {
            $items = $payload;
        }

        if (!$items) break;

        yield $items;

        // Stop als minder dan pageSize terugkomt
        if (count($items) < $pageSize) break;
        $page++;
    }
}

/**
 * Normaliseer 1 Onfact customer record naar onze tabel velden.
 */
function normalizeCustomer(array $c): array {
    // Onfact veldnamen kunnen verschillen; we vangen varianten af.
    $id         = (string)($c['id'] ?? $c['customer_id'] ?? '');
    $code       = (string)($c['code'] ?? $c['number'] ?? '');
    $company    = (string)($c['company_name'] ?? $c['company'] ?? $c['name'] ?? '');
    $contact    = (string)($c['contact_name'] ?? $c['person'] ?? $c['full_name'] ?? '');
    $email      = (string)($c['email'] ?? '');
    $phone      = (string)($c['phone'] ?? $c['telephone'] ?? '');
    $vat        = (string)($c['vat_number'] ?? $c['btw'] ?? $c['vat'] ?? '');

    $addr       = $c['address'] ?? $c['billing_address'] ?? [];
    $street     = (string)($addr['street'] ?? '');
    $number     = (string)($addr['number'] ?? $addr['housenumber'] ?? '');
    $zip        = (string)($addr['postal_code'] ?? $addr['zip'] ?? '');
    $city       = (string)($addr['city'] ?? '');
    $country    = (string)($addr['country'] ?? $addr['country_code'] ?? '');

    return [
        'onfact_id'      => $id,
        'code'           => $code,
        'bedrijf_naam'   => $company,
        'contact_naam'   => $contact,
        'adres_straat'   => $street,
        'adres_nummer'   => $number,
        'adres_postcode' => $zip,
        'adres_gemeente' => $city,
        'adres_land'     => $country,
        'telefoon'       => $phone,
        'email'          => $email,
        'btw_nummer'     => $vat,
        'is_actief'      => 1,
    ];
}
