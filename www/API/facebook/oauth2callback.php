<?php
// FILE: /API/facebook/oauth2callback.php

session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

error_reporting(E_ALL);
ini_set('display_errors', 0);

// ================================
// CONFIG — MOET OVERAL IDENTIEK ZIJN
// ================================
$fbAppId     = '969637121765527';
$fbAppSecret = 'cb9f9b4dfb7ac3a4493894c3caa11859';
$redirectUri = 'https://windelsgreen-decoresin.com/API/facebook/oauth2callback.php';

// ================================
// 1. CODE AANWEZIG?
// ================================
if (empty($_GET['code'])) {
    header("Location: /pages/account/login/?error=auth_missing");
    exit;
}

$code = $_GET['code'];

// ================================
// 2. ACCESS TOKEN OPHALEN
// ================================
$tokenUrl = 'https://graph.facebook.com/v18.0/oauth/access_token?' . http_build_query([
        'client_id'     => $fbAppId,
        'client_secret' => $fbAppSecret,
        'redirect_uri'  => $redirectUri, // ⚠️ EXACT GELIJK
        'code'          => $code
    ]);

$tokenResponse = file_get_contents($tokenUrl);
$response = json_decode($tokenResponse, true);

if (empty($response['access_token'])) {
    file_put_contents(
        __DIR__ . '/fb_token_error.log',
        date('c') . "\n" . $tokenResponse . "\n\n",
        FILE_APPEND
    );
    header("Location: /pages/account/login/?error=token_failed");
    exit;
}

$accessToken = $response['access_token'];

// ================================
// 3. USER INFO OPHALEN
// ================================
$userInfoUrl = 'https://graph.facebook.com/me?fields=id,name,email&access_token=' . urlencode($accessToken);
$userDataRaw = file_get_contents($userInfoUrl);
$userData = json_decode($userDataRaw, true);

if (empty($userData['id']) || empty($userData['email'])) {
    file_put_contents(
        __DIR__ . '/fb_user_error.log',
        date('c') . "\n" . $userDataRaw . "\n\n",
        FILE_APPEND
    );
    header("Location: /pages/account/login/?error=no_email");
    exit;
}

$facebookId = $userData['id'];
$name       = $userData['name'];
$email      = $userData['email'];

// ================================
// 4. USER ZOEKEN OF AANMAKEN
// ================================
$stmt = $conn->prepare("
    SELECT id, username 
    FROM users 
    WHERE facebook_id = ? OR email = ?
    LIMIT 1
");
$stmt->bind_param("ss", $facebookId, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    $parts     = explode(' ', $name, 2);
    $firstName = $parts[0];
    $lastName  = $parts[1] ?? '';
    $username  = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $firstName)) . rand(1000, 9999);

    $stmt = $conn->prepare("
        INSERT INTO users 
        (username, email, facebook_id, first_name, last_name, is_confirmed) 
        VALUES (?, ?, ?, ?, ?, 1)
    ");
    $stmt->bind_param("sssss", $username, $email, $facebookId, $firstName, $lastName);
    $stmt->execute();

    $user = [
        'id'       => $stmt->insert_id,
        'username' => $username
    ];
}

// ================================
// 5. SESSION ZETTEN
// ================================
$_SESSION['user'] = [
    'id'    => $user['id'],
    'name'  => $user['username'],
    'email' => $email,
    'login' => 'facebook'
];

// ================================
// 6. SUCCES → ACCOUNT
// ================================
header("Location: /pages/account/");
exit;
