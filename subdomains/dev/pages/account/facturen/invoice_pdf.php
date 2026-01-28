<?php
require dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/onfact_config.inc.php';

$idParam = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($idParam === '') { http_response_code(400); exit('Geen factuur-ID.'); }

$base = rtrim($config['base_url'] ?? '', '/');
if ($base === '') { http_response_code(500); exit('Onfact base_url ontbreekt.'); }

$headers = [
  'X-SESSION-KEY: ' . $config['onfact_api_key'],
  'X-COMPANY-UUID: ' . $config['onfact_company_id'],
];

function httpGet($url, $headers, $accept='application/json'){
  $h = array_merge(['Accept: '.$accept], $headers);
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_HTTPHEADER => $h,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
  ]);
  $body = curl_exec($ch);
  $err  = curl_error($ch);
  $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $ct   = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
  curl_close($ch);
  return ['ok'=>($err==='' && $code>=200 && $code<300), 'code'=>$code, 'err'=>$err, 'body'=>$body, 'ct'=>$ct];
}

function tryPdf($base, $headers, $candidates){
  foreach($candidates as $path){
    $res = httpGet($base.$path, $headers, 'application/pdf');
    if ($res['ok'] && stripos((string)$res['ct'], 'pdf')!==false && $res['body']) {
      return $res;
    }
  }
  return null;
}

// 1) Haal detail op (bevestigt dat ID geldig is én geeft evt. eid/uuid én mogelijke links)
$detailRes = httpGet($base.'/invoices/'.rawurlencode($idParam).'.json', $headers, 'application/json');
if (!$detailRes['ok']) {
  // Misschien was $idParam een EID – probeer omgekeerd: eerst zoeken via /invoices?q=
  // (optioneel) Kort fallback: geef 404 terug met debug
  http_response_code(404);
  exit('Detail niet gevonden voor ID: '.$idParam.' (HTTP '.$detailRes['code'].')');
}

$payload = json_decode((string)$detailRes['body'], true) ?: [];
$inv = $payload['data'] ?? ($payload['item'] ?? $payload);
$id  = (string)($inv['id'] ?? $idParam);
$eid = (string)($inv['eid'] ?? '');
$uuid= (string)($inv['uuid'] ?? '');
$pdfHints = [];
// Sommige Onfact-instanties geven directe links/exports mee:
foreach (['pdf_url','pdf','download_url','links','_links'] as $k) {
  if (!empty($inv[$k])) $pdfHints[] = $inv[$k];
}

// 2) Stel probeerpaden samen (verschillende Onfact varianten)
$candidates = [];

// directe hints eerst
foreach ($pdfHints as $hint) {
  if (is_string($hint) && stripos($hint, '.pdf') !== false) {
    $candidates[] = (strpos($hint, 'http') === 0) ? $hint : ($base.'/'.ltrim($hint,'/'));
  } elseif (is_array($hint)) {
    foreach ($hint as $v) {
      if (is_string($v) && stripos($v, '.pdf') !== false) {
        $candidates[] = (strpos($v, 'http') === 0) ? $v : ($base.'/'.ltrim($v,'/'));
      }
    }
  }
}

// gangbare API-routes (proberen id, eid, uuid)
$ids = array_values(array_unique(array_filter([$id, $eid, $uuid, $idParam])));
foreach ($ids as $x) {
  $x = rawurlencode($x);
  $candidates[] = $base.'/invoices/'.$x.'.pdf';
  $candidates[] = $base.'/invoices/'.$x.'/print.pdf';
  $candidates[] = $base.'/invoices/'.$x.'/download.pdf';
  $candidates[] = $base.'/invoices/'.$x.'.pdf?download=1';
}

// 3) Probeer ophalen
// Verwijder dubbele base-prefix; tryPdf verwacht paden met $base al ingebouwd
$candidates = array_values(array_unique($candidates));
$hit = null;
foreach ($candidates as $fullUrl) {
  // Splits base en pad voor tryPdf helper-signatuur
  if (strpos($fullUrl, $base) === 0) {
    $path = substr($fullUrl, strlen($base));
    $path = '/'.ltrim($path,'/');
    $hit = tryPdf($base, $headers, [$path]);
    if ($hit) break;
  } else {
    // absolute URL buiten base: direct GET
    $r = httpGet($fullUrl, $headers, 'application/pdf');
    if ($r['ok'] && stripos((string)$r['ct'], 'pdf')!==false && $r['body']) { $hit = $r; break; }
  }
}

if (!$hit) {
  http_response_code(404);
  // korte debug (handig bij testen; in productie kun je dit weglaten)
  exit('PDF niet gevonden voor ID '.$idParam.'. Geprobeerd: '.PHP_EOL.implode(PHP_EOL, $candidates));
}

// 4) Serve PDF
$filename = 'onfact_factuur_' . preg_replace('/[^A-Za-z0-9_-]+/','', ($inv['number_formatted'] ?? $id)) . '.pdf';
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="'.$filename.'"');
echo $hit['body'];
