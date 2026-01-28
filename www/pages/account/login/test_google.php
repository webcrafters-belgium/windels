<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/google_api_client/vendor/autoload.php';

// Google Client configureren
$client = new Google_Client();
$client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . '/config/credentials.json');
$client->setRedirectUri('https://windelsgreen-decoresin.com/API/auth/google.php');
$client->addScope(['openid', 'email', 'profile']); // Aangepaste scopes voor login
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

if (!isset($_GET['code'])) {
    // Geen autorisatiecode? Stuur de gebruiker naar Google voor inloggen
    $auth_url = $client->createAuthUrl();
    header("Location: " . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit();
} else {
    // Ontvang de OAuth 2.0 toegangstoken
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        echo "OAuth 2.0 fout: " . $token['error_description'];
        exit();
    }

    // Token opslaan in de sessie
    $_SESSION['google_access_token'] = $token;

    // Redirect naar de agenda pagina
    header("Location: /pages/account/");
    exit();
}
?>
