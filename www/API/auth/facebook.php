<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// Laad Facebook SDK handmatig
require $_SERVER['DOCUMENT_ROOT'] . '/classes/Facebook/autoload.php';

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

// Configureer Facebook OAuth Client
$fb = new Facebook([
    'app_id' => '969637121765527',
    'app_secret' => 'cb9f9b4dfb7ac3a4493894c3caa11859',
    'default_graph_version' => 'v18.0',
]);

$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken();
} catch (FacebookResponseException $e) {
    error_log("Facebook Response Error: " . $e->getMessage());
    header("Location: /pages/account/login.php?error=facebook_response_error");
    exit;
} catch (FacebookSDKException $e) {
    error_log("Facebook SDK Error: " . $e->getMessage());
    header("Location: /pages/account/login.php?error=facebook_sdk_error");
    exit;
}

if (!isset($accessToken)) {
    header("Location: /pages/account/login.php?error=facebook_no_access_token");
    exit;
}

// Haal gebruikersgegevens op
try {
    $response = $fb->get('/me?fields=id,name,email', $accessToken);
    $userInfo = $response->getGraphUser();

    $email = $userInfo['email'];
    $name = $userInfo['name'];

    // Controleer of de gebruiker al in de database staat
    $query = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Gebruiker bestaat al, log in
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $name;
    } else {
        // Nieuwe gebruiker, voeg toe aan database
        $query = $conn->prepare("INSERT INTO users (username, email, is_confirmed) VALUES (?, ?, 1)");
        $query->bind_param("ss", $name, $email);
        $query->execute();
        $_SESSION['user_id'] = $query->insert_id;
        $_SESSION['username'] = $name;
    }

    // Redirect naar dashboard
    header("Location: /pages/account/dashboard.php");
    exit;
} catch (FacebookResponseException $e) {
    error_log("Facebook API Error: " . $e->getMessage());
    header("Location: /pages/account/login.php?error=facebook_api_error");
    exit;
} catch (FacebookSDKException $e) {
    error_log("Facebook SDK Exception: " . $e->getMessage());
    header("Location: /pages/account/login.php?error=facebook_sdk_exception");
    exit;
}
