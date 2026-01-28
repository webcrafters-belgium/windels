<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

// **Laad Google API Client**
require $_SERVER['DOCUMENT_ROOT'] . '/lib/google_api_client/vendor/autoload.php';

// **Google OAuth Client Configureren**
$client = new Google_Client();
$client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/config/credentials.json');
$client->setRedirectUri('https://windelsgreen-decoresin.com/API/auth/google.php');
$client->addScope(['openid', 'email', 'profile']);
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

try {
    $errorMessage = "Google Login Gelukt";
    error_log($errorMessage);
    //file_put_contents(ADMIN_ERROR_LOG,date('[d:m:Y H:i:s]'));
    // **Redirect naar Google OAuth login**
    $auth_url = $client->createAuthUrl();
    header("Location: " . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit;
} catch (Exception $e) {
    $errorMessage = "Google Login Error: " . $e->getMessage();

    // **Log naar de server-log**
    error_log($errorMessage);

    // **Log naar de admin-log**
    //file_put_contents(ADMIN_ERROR_LOG, date('[Y-m-d H:i:s] ') . $errorMessage . PHP_EOL, FILE_APPEND);

    // **Toon foutmelding aan gebruiker**
    $_SESSION['error_message'] = "Er is een fout opgetreden bij Google-login. Probeer het later opnieuw.";
    header("Location: /pages/account/login/");
    exit;
}
