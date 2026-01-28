<?php
/**
 * test_myparcel.php
 *
 * Basic test to create a shipment on SendMyParcel (BE).
 * Run in CLI: php test_myparcel.php
 * or put on your server and call it in browser.
 */

$apiKey = '0fa71abbba6ed9cd7fe1b757fc590c899c9180ec'; // Zet hier je echte key uit SendMyParcel.be
$ch = curl_init('https://api.sendmyparcel.be/shipments');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Accept: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if ($response === false) {
    die('cURL error: ' . curl_error($ch));
}
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP $httpCode\n";
echo $response;