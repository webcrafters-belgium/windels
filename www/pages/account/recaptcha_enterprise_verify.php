<?php
// FILE: /pages/account/recaptcha_enterprise_verify.php

session_start();
header('Content-Type: application/json');

/* ==============================
   CONFIG
   ============================== */
$gcp_project_id = 'windelsgreen-1730696099406';
$recaptcha_siteKey = '6LcAuk0sAAAAAOBK7FmsIUbqAizzTaSxxrvtnz3Z';
$gcp_api_key = 'HIER_JE_GCP_API_KEY'; // NIET site/secret key

$token = $_POST['g-recaptcha-response'] ?? '';
if (!$token) {
    echo json_encode(['success' => false, 'message' => 'reCAPTCHA ontbreekt']);
    exit;
}

/* ==============================
   REQUEST BODY (request.json)
   ============================== */
$requestBody = [
    'event' => [
        'token'   => $token,
        'siteKey'=> $recaptcha_siteKey,
        'expectedAction' => 'register'
    ]
];

/* ==============================
   HTTP POST naar Google
   ============================== */
$url = sprintf(
    'https://recaptchaenterprise.googleapis.com/v1/projects/%s/assessments?key=%s',
    $gcp_project_id,
    $gcp_api_key
);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($requestBody),
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

/* ==============================
   VALIDATIE
   ============================== */
if (
    empty($data['tokenProperties']['valid']) ||
    $data['tokenProperties']['action'] !== 'register' ||
    ($data['riskAnalysis']['score'] ?? 0) < 0.3
) {
    echo json_encode([
        'success' => false,
        'message' => 'Verdachte registratie',
        'debug'   => $data
    ]);
    exit;
}

echo json_encode(['success' => true]);
